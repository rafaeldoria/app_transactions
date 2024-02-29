<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Exception;
use Throwable;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Services\Transactions\TransactionService;
use App\Resources\Transactions\TransactionResource;
use App\Services\Transactions\TransactionConfirmer;
use App\Services\Transactions\CreateTransactionService;
use App\Resources\Transactions\TransactionResourceCollection;

class TransactionController extends Controller
{
    public function index()
    {
        try {
            $transactions = (new TransactionService)->index();
        } catch (Throwable |Exception $exception) {
            return (new ResponseService)->exception('transaction.index', null, $exception);
        }
        return new TransactionResourceCollection($transactions);
    }

    public function show(int $transactionId) 
    {   
        try {
            $transaction = (new TransactionService)->show($transactionId);
            if (!$transaction) {
                throw new Exception('Not found', -404);
            }
        } catch (Throwable|Exception $exception) {
            return (new ResponseService)->exception('transaction.show', $transactionId, $exception);
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
        } catch (Throwable|Exception $exception) {
            return (new ResponseService)->exception('transaction.store',null,$exception);
        }
        return new TransactionResource($transaction,[
            'type' => 'store',
            'route' => 'transaction.store'
        ]);
    }

    public function update(Int $transactionId, Request $request)
    {
        try {
            $transaction = (new TransactionService)->update($transactionId, $request->all());
            if (!$transaction) {
                throw new Exception('Not found', -404);
            }
            $transaction->update($request->all());
        } catch (Throwable|Exception $exception) {
            return (new ResponseService)->exception('transaction.update',$transactionId,$exception);
        }
        return new TransactionResource($transaction,[
            'type' => 'update',
            'route' => 'transaction.update'
        ]);
    }

    public function transactionConfirmer(Int $transactionId)
    {
        try {
            $transaction = (new TransactionService)->show($transactionId);
            if (!$transaction) {
                throw new Exception('Not found', -404);
            }
            $transaction = (new TransactionConfirmer)->transactionConfirmer($transaction);
        } catch (Throwable|Exception $exception) {
            return (new ResponseService)->exception('transaction.confirmer',$transactionId,$exception);
        }
        return new TransactionResource($transaction,[
            'type' => 'update',
            'route' => 'transaction.confirmer'
        ]);
    }

    public function getTransactionByUser(Int $userId)
    {
        try {
            $transactions = (new TransactionService)->getTransacationByUser($userId);
        } catch (Throwable|Exception $exception) {
            return (new ResponseService)->exception('transaction.get_by_user',$userId,$exception);
        }
        return new TransactionResourceCollection($transactions);
    }
}
