<?php

namespace WiserWebSolutions\Lobbyist\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\MapInputName;
use WiserWebSolutions\LaravelLegiscan\Enums\Support\StateEnum;

final class Session extends Data
{
    #[Computed]
    public int $id;

    #[Computed]
    public string $name;

    #[Computed]
    public string $title;

    #[Computed]
    public StateEnum $state;

    public function __construct( public array $meta ) {
        $this->id = $this->meta['session_id'];
        $this->title = $this->meta['session_title'];
        $this->name = $this->meta['session_name']; // Can we also include the state?
        $this->state = StateEnum::tryFrom($this->meta['state_id']) ?? StateEnum::US;
    }

    public static function fromLegiscan(array $payload): self
    {
        return new self(
            meta: [
                'id' => (int) ($payload['session_id'] ?? 0),
                'state_id' => (int) ($payload['state_id'] ?? 0),
                'year_start' => (int) ($payload['year_start'] ?? 0),
                'year_end' => (int) ($payload['year_end'] ?? 0),
                'prefile' => (bool) ($payload['prefile'] ?? false),
                'sine_die' => (bool) ($payload['sine_die'] ?? false),
                'prior' => (bool) ($payload['prior'] ?? false),
                'special' => (bool) ($payload['special'] ?? false),
                'session_tag' => (string) ($payload['session_tag'] ?? ''),
                'session_title' => (string) ($payload['session_title'] ?? ''),
                'session_name' => (string) ($payload['session_name'] ?? ''),
                'dataset_hash' => (string) ($payload['dataset_hash'] ?? ''),
            ],
        );
    }
}