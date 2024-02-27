<?php

namespace App\Resources\Documents;

use App\Models\Document;
use App\Services\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    private $config;
    private $responseService;

    public function __construct($resource, $config = [])
    {
        parent::__construct($resource);

        $this->config = $config;
        $this->responseService = new ResponseService;
    }

    public function toArray($request) : array {
        if(empty($request)){
            return [];
        }
        return [
            'id' => $this->id,
            'type' => $this->type,
            'type_description' => (new Document)->returnTypeDescriptionDocument($this->type),
            'value' => $this->value,
            'user_id' => $this->user_id
        ];
    }

    public function with($request) {
        $method = $request->method();
        return $this->responseService->default($this->config, $this->id, $method);
    }

    public function withResponse($response)
    {
        $response->setStatusCode($this->responseService->setStatudCode($this->config['type']));
    }
}
