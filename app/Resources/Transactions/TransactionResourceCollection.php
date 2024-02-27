<?php

namespace App\Resources\Transactions;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class TransactionResourceCollection extends ResourceCollection
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
            'msg' => 'Listing transactions',
            'url' => route('transaction.index'),
            'method' => $request->method()
        ];
    }

    public function withResponse($response) : void
    {
        $response->setStatusCode(Response::HTTP_OK);
    }
}