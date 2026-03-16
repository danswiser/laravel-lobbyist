<?php

namespace WiserWebSolutions\Lobbyist\Facades;

use Illuminate\Support\Facades\Facade;

class Lobbyist extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'lobbyist';
    }
}