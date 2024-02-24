<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
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
        $transaction = $this->transaction->create($request->all());
        return response()->json($transaction, Response::HTTP_CREATED);
    }

    public function update(Transaction $transaction, Request $request) : JsonResponse
    {
        $transaction->update($request->all());
        return response()->json($transaction);
    }
}
