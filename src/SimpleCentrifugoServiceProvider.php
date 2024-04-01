<?php

namespace Larahook\SimpleCentrifugo;

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
            return new SimpleCentrifugoBroadcaster();
        });
    }
}
