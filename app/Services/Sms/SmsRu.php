<?php


namespace App\Services\Sms;

use GuzzleHttp\Client;
use InvalidArgumentException;

class SmsRu implements SmsSender
{
    private string $appId;
    private Client $client;
    private string $url;

    public function __construct(string $appId, string $url)
    {
        if (empty($appId)) {
            throw new InvalidArgumentException('Sms appId must be set.');
        }
        $this->appId = $appId;
        $this->url = $url;
        $this->client = new Client([]);
    }

    public function send(string $number, string $text): void
    {
        $this->client->post($this->url, [
            'form_params' => ['app_id' => $this->appId, 'to' => '+' . ltrim($number, '+'), 'text' => $text],
        ]);
    }
}

