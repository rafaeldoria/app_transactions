<?php

namespace App\Resources\Wallets;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class WalletResourceCollection extends ResourceCollection
{
    public function toArray($request) : array 
    {
        if(empty($request)){
            return [];
        }
        return ['data' => $this->collection];
    }

    public function with($request) : array 
    {
        return [
            'status' => true,
            'msg' => 'Listing wallets',
            'url' => route('wallet.index'),
            'method' => $request->method()
        ];
    }

    public function withResponse($response) : void
    {
        $response->setStatusCode(Response::HTTP_OK);
    }
}