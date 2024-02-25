<?php

namespace App\Resources\Wallet;

use App\Services\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
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
            'user_id' => $this->user_id,
            'amount' => $this->amount
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
