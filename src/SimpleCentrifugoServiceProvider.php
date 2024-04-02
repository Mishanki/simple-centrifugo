<?php

namespace Larahook\SimpleCentrifugo;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Broadcasting\BroadcastManager;
use Larahook\SanctumRefreshToken\Model\PersonalAccessToken;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class SimpleCentrifugoServiceProvider extends ServiceProvider
{
    /**
     * @param BroadcastManager $broadcastManager
     *
     * @return void
     */
    public function boot(BroadcastManager $broadcastManager)
    {
        $broadcastManager->extend('simple-centrifugo', function ($app) {
            return new SimpleCentrifugoBroadcaster($app->make(SimpleCentrifugo::class));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SimpleCentrifugo::class, function ($app) {
            $config = $app->make('config')->get('broadcasting.connections.centrifugo');
            $http = new HttpClient();

            return new SimpleCentrifugo($config, $http);
        });
    }
}
