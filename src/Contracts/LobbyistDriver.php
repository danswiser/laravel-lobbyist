<?php

namespace WiserWebSolutions\Lobbyist\Contracts;

use Illuminate\Http\Client\PendingRequest;

interface LobbyistDriver
{
    /**
     * Set the state context for the current driver.
     */
    public function setStateContext(string $state): self;

    /**
     * Get information about a specific bill.
     */
    public function getBill(string|int $identifier): array;

    /**
     * Get a list of current bills for the active state context.
     */
    public function getBills(): array;

    /**
     * Get vote/roll call details.
     */
    public function getVoteDetails(string|int $identifier): array;
}