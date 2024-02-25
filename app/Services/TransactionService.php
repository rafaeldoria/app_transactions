<?php

namespace App\Services;

use Exception;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Transaction;
use App\Jobs\TransactionJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

class TransactionService
{
    public function createTransaction(Request $request)
    {
        try {
            $payer = User::findOrFail($request->payer_id);

            if($payer->type == User::__SHOPMAN__){
                throw new Exception('you dont have permission to effect this transaction.');
            }
            
            if($payer->wallet->amount <= $request->amount){
                throw new Exception('insufficient funds to payer.');
            }
            
            $transaction = (new Transaction)->create($request->all());
            TransactionJob::dispatch($transaction)
                ->delay(now()->addSeconds(10))
                ->onQueue('transactions');
            return $transaction;
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function toConfirmTransaction(Transaction $transaction)
    {
        try {
            $payer = User::findOrFail($transaction->payer_id);
            $payer->wallet()->update([
                'amount' => $payer->wallet->amount - $transaction->amount
            ]);
            
            $payee = User::findOrFail($transaction->payee_id);
            $payee->wallet()->update([
                'amount' => $payee->wallet->amount + $transaction->amount
            ]);

            $transaction->confirmed = 1;
            $transaction->update($transaction->toArray());
            return $transaction;
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function toAuthorizeTransaction()
    {
        $authorize = false;
        $client = new Client();
        $authorize_ulr = env('AUTHORIZE_URL_MOCK');
        $response = $client->getAsync($authorize_ulr)->wait();
        $httpStatusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $response = json_decode($body, true);

        if($response['message'] == 'Autorizado' && $httpStatusCode == HttpResponse::HTTP_OK){
            $authorize = true;
        }
        return $authorize;
    }

    
}