<?php

namespace WiserWebSolutions\Lobbyist\Drivers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use WiserWebSolutions\Lobbyist\Contracts\LobbyistDriver;
use Exception;
use WiserWebSolutions\Lobbyist\Exceptions\LobbyistException;

class LegiscanDriver implements LobbyistDriver
{
    private array $endpoint;
    private array $request;
    private array $cache;
    protected ?string $stateContext = null;

    public function __construct()
    {
        if (! $this->endpoint['api_key'] = config('lobbyist.drivers.legiscan.endpoint.api_key')) {
            throw LobbyistException::missingKey();
        }

        if (! $this->endpoint['base_uri'] = config('lobbyist.drivers.legiscan.endpoint.base_uri')) {
            throw LobbyistException::missingBaseUri();
        }

        $this->endpoint = config('lobbyist.drivers.legiscan.endpoint');
        $this->request = config('lobbyist.drivers.legiscan.request');
        $this->cache = config('lobbyist.drivers.legiscan.cache');
    }

    /**
     * Creates and configures an HTTP client instance.
     *
     * @return \Illuminate\Http\Client\PendingRequest Configured HTTP client
     */
    private function http(): PendingRequest
    {
        return Http::baseUrl($this->endpoint['base_uri'])
            ->timeout($this->request['timeout'])
            ->retry($this->request['retry_times'], $this->request['retry_sleep_ms']);
    }

    /**
     * Makes an API call to the LegiScan service.
     *
     * @param  string  $op  The operation to perform
     * @param  array  $params  Additional parameters for the API call
     * @param  int|null  $ttl  Optional cache time-to-live in seconds
     * @return array The API response
     *
     * @throws \App\Exceptions\LegiScanException When response is invalid
     */
    protected function call(string $operation, array $params = [], ?int $ttl = null): array
    {
        $query = array_filter([
            'key' => $this->endpoint['api_key'],
            'op' => $operation,
        ] + $params, fn ($v) => $v !== null && $v !== '');

        $cacheKey = 'legiscan:'.md5($this->endpoint['base_uri'].'|'.http_build_query($query));

        if ($this->cache['enabled']) {
            if (Cache::store($this->cache['store'])->has($cacheKey)) {
                // Log::debug('LegiScan Request (from Cache): '.str_replace($this->endpoint['api_key'], 'REDACTED', $this->endpoint['base_uri'].http_build_query($query)));
            }

            return Cache::store($this->cache['store'])
                ->remember($cacheKey, $ttl ?? $this->cache['ttl'], fn () => $this->send($query));
        }

        return $this->send($query);
    }
    /**
     * Sends the HTTP request to the LegiScan API.
     *
     * @param  array  $query  The query parameters for the request
     * @return array The parsed JSON response
     *
     * @throws \App\Exceptions\LegiScanException When response is invalid or contains error
     * @throws \Illuminate\Http\Client\RequestException When HTTP request fails
     */
    protected function send(array $query): array
    {
        $res = $this->http()->get('/', $query);
        $res->throw();

        $json = $res->json();

        if (! is_array($json)) {
            throw new LobbyistException('Invalid JSON response');
        }

        // LegiScan status contract
        if (($json['status'] ?? null) !== 'OK') {
            $message = $json['alert']['message'] ?? 'Unknown error';
            throw LobbyistException::apiError($message);
        }

        return $json;
    }

    /**
     * Set the state context for the current driver.
     * This allows the driver to know which state to query for when making API calls that require a state context.
     * If no state is provided, it defaults to 'US' for federal queries.
     * 
     * @param  string|null  $state  The state abbreviation code (e.g., 'CA', 'NY', 'US'), or null for federal context
     * 
     * @return self Returns the driver instance for method chaining
     */
    public function setStateContext(?string $state = null): self
    {
        $this->stateContext = $state !== null ? (string) Str::of($state)->upper()->trim() : 'US';

        return $this;
    }
    
    /**
     * This operation returns a list of sessions that are available for access in the given state abbreviation, or all
     * sessions if no state is given.
     *
     * @see call() For the underlying API call implementation
     * @see docs/LegiScan_API_User_Manual_2025-08-15.pdf (Page 8)
     *
     * @param  string|null  $state  The state abbreviation code (e.g., 'CA', 'NY', 'US'), or null for all states and federal
     * @return array{
     *   status: string,
     *   sessions: array<array{
     *     session_id: int,
     *     state_id: int,
     *     year_start: int,
     *     year_end: int,
     *     prefile: int,
     *     sine_die: int,
     *     prior: int,
     *     special: int,
     *     session_tag: string,
     *     session_title: string,
     *     session_name: string,
     *     dataset_hash: string
     *   }>
     * }
     *
     * @throws \Exception If the API request fails
     *
     * @example
     * [
     *   "status" => "OK",
     *   "sessions" => [
     *     "0" => [
     *       "session_id" => 1791,
     *       "state_id" => 5,
     *       "year_start" => 2021,
     *       "year_end" => 2022,
     *       "prefile" => 0,
     *       "sine_die" => 0,
     *       "prior" => 0,
     *       "special" => 0,
     *       "session_tag" => "Regular Session",
     *       "session_title" => "2021-2022 Regular Session",
     *       "session_name" => "2021-2022 Session",
     *       "dataset_hash" => "ead44c3c2a0055a7ecbc5c13ebd71f70"
     *     ],
     *     // ... more
     *   ]
     * ]
     */
    public function getSessionList(?string $state = null): array
    {
        $params = [
            'state' => $this->stateContext,
        ];

        $response = $this->call(operation: 'getSessionList', params: array_filter($params), ttl: 60 * 60 * 24);

        if (! isset($response['sessions'])) {
            throw LobbyistException::apiError('Invalid response structure for getSessionList');
        }

        return $response;
    }

    /**
     * This operation returns a master list of summary bill data in the given session_id or current state session.
     *
     * @see call() For the underlying API call implementation
     * @see docs/LegiScan_API_User_Manual_2025-08-15.pdf (Page 9)
     *
     * @param  string|null  $session_id  Retrieve bill master list for the session_id.
     * @param  string|null  $state  The state abbreviation code (e.g., 'CA', 'NY', 'US').
     * @return array{
     *   status: string,
     *   masterlist: array<array{
     *     bill_id: int,
     *     number: string,
     *     change_hash: int,
     *     url: string,
     *     status_date: string,         // format: Y-m-d
     *     status: string,
     *     last_action_date: string,    // format: Y-m-d
     *     last_action: string,
     *     title: string,
     *     description: string,
     *   }>
     * }
     *
     * @throws \Exception If the API request fails
     *
     * @example
     * [
     *   "status" => "OK",
     *   "masterlist" => [
     *       [
     *           "bill_id" => 1132030,
     *           "number" => AB1,
     *           "change_hash" => "d72444d8f2026219e38cb2179dcc67a0",
     *           "url" => "https://legiscan.com/CA/bill/AB1/2019",
     *           "status_date" => "2018-12-03",
     *           "status" => "1",
     *           "last_action_date" => "2018-12-04",
     *           "last_action" => "From printer. May be heard in committee January 3.",
     *           "title" => "Youth athletics: California Youth Football Act.",
     *           "description" => "An act to add Article 2.7 to the Health and Safety",
     *       ],
     *       // ... more
     *   ]
     * ]
     */
    public function getMasterList(?int $session_id = null, ?bool $detail = null): array
    {
        if($session_id === null) {
            $params = ['state' => $this->stateContext];
        } else {
            $params = ['id' => $session_id];
        }

        $response = $this->call(operation: 'getMasterList', params: array_filter($params), ttl: 60 * 60);

        if (! isset($response['masterlist'])) {
            throw LobbyistException::apiError('Invalid response structure for getMasterList');
        }

        return $response;
    }


















    public function getBill(string|int $identifier): array
    {
        if (is_numeric($identifier) && !$this->stateContext) {
            return $this->request('getBill', ['id' => $identifier]);
        }

        if (!$this->stateContext) {
            throw new Exception("State context is required to query by bill number.");
        }

        return $this->request('getBill', [
            'state' => $this->stateContext,
            'bill' => $identifier
        ]);
    }

    public function getVoteDetails(string|int $identifier): array
    {
        return $this->request('getRollCall', ['id' => $identifier]);
    }

    public function getBills(): array
    {
        if (!$this->stateContext) {
            throw new Exception("State context must be set to retrieve a master list.");
        }

        return $this->request('getMasterList', ['state' => $this->stateContext]);
    }
}