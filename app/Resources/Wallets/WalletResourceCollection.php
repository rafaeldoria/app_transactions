<?php

namespace App\Resources\Wallets;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class WalletResourceCollection extends ResourceCollection
{
    public function toArray($request) : array 
    {
        return ['data' => $this->collection];
    }

    public function with($request) : array 
    {
        return [
            'status' => true,
            'msg' => 'Listing wallets',
            'url' => route('wallet.index')
        ];
    }

    public function withResponse($request, $response) : void
    {
        $response->setStatusCode(Response::HTTP_OK);
    }
}