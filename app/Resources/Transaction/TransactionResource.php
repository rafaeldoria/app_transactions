<?php

namespace App\Resources\Transaction;

use App\Services\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'payer_id' => $this->payer_id,
            'payee_id' => $this->payee_id,
            'amount' => $this->amount,
            'confirmed' => $this->confirmed
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
