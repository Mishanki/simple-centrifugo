<?php

namespace Larahook\SimpleCentrifugo;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Broadcasting\BroadcastException;
use Larahook\SanctumRefreshToken\Model\PersonalAccessToken;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;
use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster;
use Illuminate\Contracts\Broadcasting\Broadcaster as BroadcasterInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SimpleCentrifugo
{
    const API_PATH = '/api';

    /**
     * Create a new Centrifugo instance.
     *
     * @param array      $config
     * @param HttpClient $httpClient
     */
    public function __construct(array $config, public HttpClient $httpClient)
    {
        $this->config = $this->initConfiguration($config);
    }

    /**
     * Init centrifugo configuration.
     *
     * @param array $config
     *
     * @return array
     */
    protected function initConfiguration(array $config)
    {
        $defaults = [
            'url'                   => 'http://localhost:8000',
            'token_hmac_secret_key' => null,
            'api_key'               => null,
            'ssl_key'               => null,
            'verify'                => true,
        ];

        foreach ($config as $key => $value) {
            if (array_key_exists($key, $defaults)) {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }

    /**
     * Broadcast the same data into multiple channels.
     *
     * @param array $channels
     * @param array $data
     * @param bool  $skipHistory (optional)
     *
     * @return mixed
     */
    public function broadcast(array $channels, array $data, $skipHistory = false)
    {
        $params = [
            'channels'     => $channels,
            'data'         => $data,
            'skip_history' => $skipHistory,
        ];

        return $this->send('broadcast', $params);
    }

    /**
     * Send message to centrifugo server.
     *
     * @param string $method
     * @param array  $params
     *
     * @return mixed
     */
    protected function send($method, array $params = [])
    {
        $json = json_encode(['method' => $method, 'params' => $params]);

        $headers = [
            'Content-type'  => 'application/json',
            'Authorization' => 'apikey '.$this->getApiKey(),
        ];

        try {
            $url = parse_url($this->prepareUrl());

            $config = collect([
                'headers'     => $headers,
                'body'        => $json,
                'http_errors' => true,
            ]);

            if (isset($url['scheme']) && $url['scheme'] == 'https') {
                $config->put('verify', collect($this->config)->get('verify', false));

                if (collect($this->config)->get('ssl_key')) {
                    $config->put('ssl_key', collect($this->config)->get('ssl_key'));
                }
            }

            $response = $this->httpClient->post($this->prepareUrl(), $config->toArray());

            $result = json_decode((string) $response->getBody(), true);
        } catch (ClientException $e) {
            $result = [
                'method' => $method,
                'error'  => $e->getMessage(),
                'body'   => $params,
            ];
        }

        return $result;
    }

    /**
     * Prepare URL to send the http request.
     *
     * @return string
     */
    protected function prepareUrl()
    {
        $address = rtrim($this->config['url'], '/');

        if (substr_compare($address, static::API_PATH, -strlen(static::API_PATH)) !== 0) {
            $address .= static::API_PATH;
        }

        return $address;
    }

    /**
     * Get token hmac secret key.
     *
     * @return string
     */
    protected function getSecret(): string
    {
        return $this->config['token_hmac_secret_key'];
    }

    /**
     * Get Api Key.
     *
     * @return string
     */
    protected function getApiKey(): string
    {
        return $this->config['api_key'];
    }
}
