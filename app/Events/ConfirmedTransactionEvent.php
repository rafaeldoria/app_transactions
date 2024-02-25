<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

class ConfirmedTransactionEvent implements ShouldDispatchAfterCommit
{
    use Dispatchable,  SerializesModels;

    public $transaction;
    /**
     * Create a new event instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
