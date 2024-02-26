<?php

namespace App\Resources\Wallets;

use App\Services\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
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
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'amount' => $this->amount
        ];
    }

    public function with($request) {
        return $this->responseService->default($this->config, $this->id);
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->responseService->setStatudCode($this->config['type']));
    }
}
