<?php

namespace WiserWebSolutions\Lobbyist;

use Illuminate\Support\ServiceProvider;

class LobbyistServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/lobbyist/core.php', 'lobbyist'
        );

        $this->app->singleton('lobbyist', function ($app) {
            return new LobbyistManager($app);
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/lobbyist/core.php' => config_path('lobbyist/core.php'),
            ], 'lobbyist-config');
        }
    }
}