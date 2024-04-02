<?php

namespace Larahook\SimpleCentrifugo;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Support\ServiceProvider;

class SimpleCentrifugoServiceProvider extends ServiceProvider
{
    /**
     * @param BroadcastManager $broadcastManager
     */
    public function boot(BroadcastManager $broadcastManager)
    {
        $broadcastManager->extend('simple-centrifugo', static function ($app) {
            return new SimpleCentrifugoBroadcaster($app->make(SimpleCentrifugo::class));
        });
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind(SimpleCentrifugo::class, static function ($app) {
            $config = $app->make('config')->get('broadcasting.connections.centrifugo');
            $http = new HttpClient();

            return new SimpleCentrifugo($config, $http);
        });
    }
}
