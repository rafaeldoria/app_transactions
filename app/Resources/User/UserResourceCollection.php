<?php

namespace App\Resources\User;

use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserResourceCollection extends ResourceCollection
{
    public function toArray($request) : array 
    {
        return ['data' => $this->collection];
    }

    public function with($request) : array 
    {
        return [
            'status' => true,
            'msg' => 'Listing users',
            'url' => route('user.index')
        ];
    }

    public function withResponse($request, $response) : void
    {
        $response->setStatusCode(Response::HTTP_OK);
    }
}