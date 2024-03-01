<?php

namespace App\Resources\Documents;

use App\Models\Document;
use Illuminate\Http\Response;
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

        if(!$this->resource instanceof Document){
            $dataArray = $this->resource;
            if(!is_array($this->resource)){
                $dataArray = json_decode($this->resource, true);
            }
            $this->resource = new Document();
            $this->resource->fill($dataArray);
            $this->resource->id = $dataArray['id'];
        }

        return [
            'id' => $this->id,
            'type' => $this->type,
            'type_description' => (new Document)->returnTypeDescriptionDocument($this->type),
            'value' => $this->value,
            'user_id' => $this->user_id,
            'user' => $this->responseService->getUrl('user.show', $this->id)
        ];
    }

    public function with($request) {
        $method = $request->method();
        return $this->responseService->default($this->config, $this->id, $method);
    }

    public function withResponse($request, $response) : void
    {
        if(empty($request)){
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return;
        }
        $response->setStatusCode($this->responseService->setStatudCode($this->config['type']));
    }
}
