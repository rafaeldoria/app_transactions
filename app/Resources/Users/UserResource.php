<?php

namespace App\Resources\Users;

use App\Models\User;
use App\Services\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type,
            'type_description' => (new User)->returnTypeDescriptionUser($this->type),
            'wallet' => $this->responseService->getUrl('wallet.get_by_user', $this->id),
            'document' => $this->responseService->getUrl('document.get_by_user', $this->id)
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
