<?php

namespace App\Resources\Documents;

use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentResourceCollection extends ResourceCollection
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
            'msg' => 'Listing documents',
            'url' => route('document.index'),
            'method' => $request->method()
        ];
    }

    public function withResponse($response) : void
    {
        $response->setStatusCode(Response::HTTP_OK);
    }
}