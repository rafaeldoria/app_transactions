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
        $uri = explode("/", $request->getRequestUri());

        $url = route('transaction.index');
        if(isset($uri[3])){
            $url = match ($uri[3]) {
                'getByUser' => route('transaction.get_by_user',$uri[4])
            };
        }
        
        return [
            'status' => true,
            'msg' => 'Listing transactions',
            'url' => $url,
            'method' => $request->method()
        ];
    }

    public function withResponse($request, $response) : void
    {
        if(empty($request)){
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return;
        }
        $response->setStatusCode(Response::HTTP_OK);
    }
}