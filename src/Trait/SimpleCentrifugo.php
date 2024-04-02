<?php

namespace Larahook\SimpleCentrifugo\Trait;

use Carbon\Carbon;
use Firebase\JWT\JWT;

trait SimpleCentrifugo
{
    /**
     * @param int $userId
     * @param Carbon $exp
     *
     * @return array
     */
    public function getConnectionToken(int $userId, Carbon $exp): array
    {
        $jwt = (new JWT())::encode([
            'sub' => (string) $userId,
            'exp' => $exp->timestamp,
        ], getenv('CENTRIFUGO_TOKEN_HMAC_SECRET_KEY'), 'HS256');

        return [
            'token' => $jwt,
            'expired' => $exp->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param int $userId
     * @param array $channels
     * @param Carbon $exp
     *
     * @return array
     */
    public function getSubscriptionToken(int $userId, array $channels, Carbon $exp): array
    {
        foreach ($channels as $channel) {
            $result[] = [
                'channel' => $channel,
                'token' => (new JWT())::encode(
                    [
                        'sub' => (string) $userId,
                        'channel' => $channel,
                        'exp' => $exp->timestamp,
                    ],
                    getenv('CENTRIFUGO_TOKEN_HMAC_SECRET_KEY'),
                    'HS256',
                ),
                'expired' => $exp->format('Y-m-d H:i:s'),
            ];
        }

        return $result ?? [];
    }
}
