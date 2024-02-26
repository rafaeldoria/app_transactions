<?php

namespace App\Jobs;

use App\Events\ConfirmedTransactionEvent;
use App\Models\Transaction;
use App\Services\Transactions\TransactionAuthorizer;
use App\Services\Transactions\TransactionConfirmer;
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
        if((new TransactionAuthorizer)->transactionAuthorizer()){
            Log::info('Confirming transaction #' . $this->transaction->id);
            (new TransactionConfirmer)->transactionConfirmer($this->transaction);
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
