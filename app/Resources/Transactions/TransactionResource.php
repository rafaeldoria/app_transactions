<?php

namespace App\Resources\Transactions;

use App\Models\Transaction;
use Illuminate\Http\Response;
use App\Services\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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

        if(!$this->resource instanceof Transaction){
            $dataArray = $this->resource;
            if(!is_array($this->resource)){
                $dataArray = json_decode($this->resource, true);
            }
            $this->resource = new Transaction();
            $this->resource->fill($dataArray);
            $this->resource->id = $dataArray['id'];
        }

        return [
            'id' => $this->id,
            'payer_id' => $this->payer_id,
            'payee_id' => $this->payee_id,
            'amount' => $this->amount,
            'confirmed' => $this->confirmed,
            'payer' => $this->responseService->getUrl('user.show', $this->payer_id),
            'payee' => $this->responseService->getUrl('user.show', $this->payee_id),
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
