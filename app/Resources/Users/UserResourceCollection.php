<?php

namespace App\Resources\Users;

use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserResourceCollection extends ResourceCollection
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
            'msg' => 'Listing users',
            'url' => route('user.index'),
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