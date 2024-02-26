<?php

namespace App\Services\Transactions;

use GuzzleHttp\Client;
use Illuminate\Http\Response as HttpResponse;

class TransactionAuthorizer
{
    public function transactionAuthorizer()
    {
        $authorize = false;
        $client = new Client();
        $authorize_ulr = env('AUTHORIZE_URL_MOCK');
        $response = $client->getAsync($authorize_ulr)->wait();
        $httpStatusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $response = json_decode($body, true);

        if($response['message'] == 'Autorizado' && $httpStatusCode == HttpResponse::HTTP_OK){
            $authorize = true;
        }
        return $authorize;
    }
}