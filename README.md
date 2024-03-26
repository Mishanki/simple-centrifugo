# Connection and subscription tokens for centrifugo

## Install
```composer
composer require larahook/simple-centrifugo
```

## Environment
Add `HMAC_SECRET_KEY` to .env file
```
CENTRIFUGO_TOKEN_HMAC_SECRET_KEY=YourKey
```

## Usage
```php
use Carbon\Carbon;
use Larahook\SimpleCentrifugo\Trait\SimpleCentrifugo;

class ChannelService
{
    use SimpleCentrifugo;

    /**
     * @param int $userId
     * @param Carbon $exp
     *
     * @return array
     */
    public function getConnToken(int $userId, Carbon $exp): array
    {
        return $this->getConnectionToken($userId, $exp);
    }

    /**
     * @param int $userId
     * @param array $channels
     * @param Carbon $exp
     *
     * @return array
     */
    public function getSubsToken(int $userId, array $channels, Carbon $exp): array
    {
        return $this->getSubscriptionToken($userId, $channels, $exp);
    }
}
```