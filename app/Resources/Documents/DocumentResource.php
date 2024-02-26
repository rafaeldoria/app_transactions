<?php

namespace App\Resources\Documents;

use App\Models\Document;
use App\Services\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    private $config;

    public function __construct($resource, $config = [])
    {
        parent::__construct($resource);

        $this->config = $config;
    }

    public function toArray($request) : array {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'type_description' => Document::returnTypeDescriptionDocument($this->type),
            'value' => $this->value,
            'user_id' => $this->user_id
        ];
    }

    public function with($request) {
        return ResponseService::default($this->config, $this->id);
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode(ResponseService::setStatudCode($this->config['type']));
    }
}
