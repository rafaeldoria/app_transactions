<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Services\Transactions\TransactionService;
use App\Services\Transactions\CreateTransactionService;
use App\Services\Transactions\TransactionConfirmer;
use App\Resources\Transactions\TransactionResource;
use App\Resources\Transactions\TransactionResourceCollection;

class TransactionController extends Controller
{
    public function index()
    {
        try {
            $transactions = (new TransactionService)->index();
        } catch (\Throwable |\Exception $e) {
            return ResponseService::exception('transaction.index', null, $e);
        }
        return new TransactionResourceCollection($transactions);
    }

    public function show(int $id) 
    {   
        try {
            $transaction = (new TransactionService)->show($id);
            if (!$transaction) {
                throw new \Exception('Not found', -404);
            }
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('transaction.show', $id, $e);
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

    public function update(Int $id, Request $request)
    {
        try {
            $transaction = (new TransactionService)->update($id, $request->all());
            if (!$transaction) {
                throw new \Exception('Not found', -404);
            }
            $transaction->update($request->all());
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('transaction.update',$id,$e);
        }
        return new TransactionResource($transaction,[
            'type' => 'update',
            'route' => 'transaction.update'
        ]);
    }

    public function transactionConfirmer(Int $id)
    {
        try {
            $transaction = (new TransactionService)->show($id);
            if (!$transaction) {
                throw new \Exception('Not found', -404);
            }
            $transaction = (new TransactionConfirmer)->transactionConfirmer($transaction);
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('transaction.confirmer',$id,$e);
        }
        return new TransactionResource($transaction,[
            'type' => 'update',
            'route' => 'transaction.confirmer'
        ]);
    }
}
