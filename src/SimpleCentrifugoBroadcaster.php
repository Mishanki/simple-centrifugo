<?php

namespace Larahook\SimpleCentrifugo;

use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Http\Request;
use Larahook\SanctumRefreshToken\Model\PersonalAccessToken;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;
use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster;
use Illuminate\Contracts\Broadcasting\Broadcaster as BroadcasterInterface;

class SimpleCentrifugoBroadcaster extends Broadcaster implements BroadcasterInterface
{
    public function __construct()
    {}

    /**
     * Authenticate the incoming request for a given channel.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function auth($request)
    {}

    /**
     * Return the valid authentication response.
     *
     * @param  Request  $request
     * @param  mixed  $result
     * @return mixed
     */
    public function validAuthenticationResponse($request, $result)
    {
        return $result;
    }

    /**
     * Broadcast the given event.
     *
     * @param  array  $channels
     * @param  string  $event
     * @param  array  $payload
     * @return void
     *
     * @throws BroadcastException
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {}
}
