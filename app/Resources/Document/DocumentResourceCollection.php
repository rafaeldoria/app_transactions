<?php

namespace App\Resources\Document;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentResourceCollection extends ResourceCollection
{
    public function toArray($request) : array 
    {
        return ['data' => $this->collection];
    }

    public function with($request) : array 
    {
        return [
            'status' => true,
            'msg' => 'Listing documents',
            'url' => route('document.index')
        ];
    }

    public function withResponse($request, $response) : void
    {
        $response->setStatusCode(200);
    }
}