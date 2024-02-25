<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Services\TransactionService;
use App\Resources\Transaction\TransactionResource;
use App\Resources\Transaction\TransactionResourceCollection;

class TransactionController extends Controller
{
    protected $transaction;
    protected $transactionService;

    public function __construct(Transaction $transaction,TransactionService $transactionService)
    {
        $this->transaction = $transaction;
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        try {
            $transactions = $this->transaction->all();
        } catch (\Throwable |\Exception $e) {
            return ResponseService::exception('transaction.index', null, $e);
        }
        return new TransactionResourceCollection($transactions);
    }

    public function show(Transaction $transaction) 
    {   
        try {
            
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('transaction.show', null, $e);
        }
        return new TransactionResource($transaction,[
            'type' => 'show',
            'route' => 'transaction.show'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $transaction = $this->transactionService->createTransaction($request);
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('transaction.store',null,$e);
        }
        return new TransactionResource($transaction,[
            'type' => 'store',
            'route' => 'transaction.store'
        ]);
    }

    public function update(Transaction $transaction, Request $request)
    {
        try {
            $transaction->update($request->all());
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('transaction.update',$transaction->id,$e);
        }
        return new TransactionResource($transaction,[
            'type' => 'update',
            'route' => 'transaction.update'
        ]);
    }

    public function toConfirmTransaction(Transaction $transaction)
    {
        try {
            $transaction = $this->transactionService->toConfirmTransaction($transaction);
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('transaction.to_confirm',$transaction->id,$e);
        }
        return new TransactionResource($transaction,[
            'type' => 'update',
            'route' => 'transaction.to_confirm'
        ]);
    }
}
