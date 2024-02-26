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

    public function build(string $to)
    {
        $client = new Client();
        $url_send_email = env('MAIL_URL_MOCK');
        $response = $client->getAsync($url_send_email)->wait();
        $body = $response->getBody()->getContents();

        return json_decode($body, true);
    }

    
}
