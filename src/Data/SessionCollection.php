<?php

namespace WiserWebSolutions\Lobbyist\Data;

use Illuminate\Support\Collection;

/**
 * @extends Collection<int, Session>
 */
class SessionCollection extends Collection
{
    public function active(): static
    {
        return $this->filter(fn (Session $s) => !$s->prior && !$s->sineDie);
    }

    public function special(): static
    {
        return $this->filter(fn (Session $s) => $s->special);
    }
}