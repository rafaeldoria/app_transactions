<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Services\TransactionService;
use App\Services\Transactions\CreateTransactionService;
use App\Services\Transactions\TransactionConfirmer;
use App\Resources\Transactions\TransactionResource;
use App\Resources\Transactions\TransactionResourceCollection;

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
            $transaction = (new CreateTransactionService)->createTransaction($request);
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

    public function transactionConfirmer(Transaction $transaction)
    {
        try {
            $transaction = (new TransactionConfirmer)->transactionConfirmer($transaction);
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('transaction.confirmer',$transaction->id,$e);
        }
        return new TransactionResource($transaction,[
            'type' => 'update',
            'route' => 'transaction.confirmer'
        ]);
    }
}
