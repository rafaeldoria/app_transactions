<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    protected $transaction;
    protected $transactionService;

    public function __construct(Transaction $transaction,TransactionService $transactionService)
    {
        $this->transaction = $transaction;
        $this->transactionService = $transactionService;
    }

    public function index() : JsonResponse
    {
        return response()->json($this->transaction->all());
    }

    public function show(Transaction $transaction) : JsonResponse 
    {   
        return response()->json($transaction);
    }

    public function store(Request $request) : JsonResponse
    {
        $transaction = $this->transactionService->createTransaction($request);
        return response()->json($transaction, Response::HTTP_CREATED);
    }

    public function update(Transaction $transaction, Request $request) : JsonResponse
    {
        $transaction->update($request->all());
        return response()->json($transaction);
    }

    public function toConfirmTransaction(Transaction $transaction) : JsonResponse
    {
        $transaction = $this->transactionService->toConfirmTransaction($transaction);
        return response()->json($transaction);
    }
}
