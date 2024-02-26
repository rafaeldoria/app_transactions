<?php

namespace App\Resources\Transactions;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class TransactionResourceCollection extends ResourceCollection
{
    public function toArray($request) : array 
    {
        return ['data' => $this->collection];
    }

    public function with($request) : array 
    {
        return [
            'status' => true,
            'msg' => 'Listing transactions',
            'url' => route('transaction.index')
        ];
    }

    public function withResponse($request, $response) : void
    {
        $response->setStatusCode(Response::HTTP_OK);
    }
}