<?php

namespace App\Services\Emails;

use GuzzleHttp\Client;

class EmailService
{
    protected $content;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function build()
    {
        $client = new Client();
        $urlSendEmail = env('MAIL_URL_MOCK');
        $response = $client->getAsync($urlSendEmail)->wait();
        $body = $response->getBody()->getContents();

        return json_decode($body, true);
    }

    
}
