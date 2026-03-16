<?php

namespace WiserWebSolutions\Lobbyist;

use Illuminate\Support\Manager;
use WiserWebSolutions\Lobbyist\Contracts\LobbyistDriver;
use WiserWebSolutions\Lobbyist\Drivers\LegiscanDriver;
use WiserWebSolutions\Lobbyist\Exceptions\LobbyistException;

class LobbyistManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return $this->config->get('lobbyist.core.default') ?? 'legiscan';
    }

    public function createLegiscanDriver(): LegiscanDriver
    {
        return new LegiscanDriver(
            $this->config->get('lobbyist.legiscan')
        );
    }

    /**
     * Route the request to a state-specific driver if installed,
     * otherwise fall back to the default driver.
     */
    public function state(string $state): LobbyistDriver
    {
        $driverName = strtolower($state);

        try {
            // Attempt to load the driver mapped to the state abbreviation (e.g., 'tx', 'ny')
            $driver = $this->driver($driverName);
        } catch (\InvalidArgumentException $e) {
            // Driver not supported/installed. Fall back to the default (Legiscan)
            $driver = $this->driver($this->getDefaultDriver());
        }

        // Pass the state context down so the active driver knows what to query
        return $driver->setStateContext($state);
    }
}