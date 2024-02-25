<?php

namespace App\Jobs;

use App\Events\ConfirmedTransactionEvent;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // protected $transaction;
    /**
     * Create a new job instance.
     */
    public function __construct(private Transaction $transaction)
    {
        // $this->transaction = $transaction;
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $transactionService = new TransactionService();
        if($transactionService->toAuthorizeTransaction()){
            Log::info('Confirming transaction #' . $this->transaction->id);
            $transactionService->toConfirmTransaction($this->transaction);
            Log::info('Sending email to user - transaction #' . $this->transaction->id);
            ConfirmedTransactionEvent::dispatch($this->transaction);
        }else{
            $this->fail('Failed to confirm transaction : ' . $this->transaction->id);
        }
    }

    public function failed()
    {
        echo 'failed';
    }
}
