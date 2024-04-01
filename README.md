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

## Driver 
Add `centrifugo` connection with `simple-centrifugo` driver to `config/broadcasting.php`
```php
'connections' => [
    'centrifugo' => [
        'driver' => 'simple-centrifugo',
        'token_hmac_secret_key' => env('CENTRIFUGO_TOKEN_HMAC_SECRET_KEY', ''),
        'api_key' => env('CENTRIFUGO_API_KEY', ''),
        'url' => env('CENTRIFUGO_URL', 'http://localhost:8000'), // centrifugo api url
        'verify' => env('CENTRIFUGO_VERIFY', false), // Verify host ssl if centrifugo uses this
        'ssl_key' => env('CENTRIFUGO_SSL_KEY', null), // Self-Signed SSl Key for Host (require verify=true)
    ],
]
```

## Usage

### Get tokens for client
- Add `SimpleCentrifugo` trait to your class
- Use method `getConnectionToken` for get connection token
- And use method `getSubscriptionToken` for get subscription tokens
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

### Event example
```php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class PersonalEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @param array $message
     */
    public function __construct(public array $message) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['personal:#'.Auth::id()];
    }

    public function broadcastAs()
    {
        return 'PersonalEvent';
    }
}
```

### Event execute
```php
PersonalEvent::dispatch(['info']);
```

