<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test getting an API all transactions.
     */
    public function test_get_all_transaction_endpoint(): void
    {
        Transaction::factory(5)->create();
        $response = $this->getJson('/api/transaction');
        $response->assertStatus(Response::HTTP_OK)
                ->assertJsonCount(5)
                ->assertSee(['id','payee','payer','amount','confirmed']);

        $response->assertJson(function (AssertableJson $json){
            $json->whereType('0.id', 'integer');
            $json->whereType('0.payer_id', 'integer');
            $json->whereType('0.payee_id', 'integer');
            $json->whereType('0.amount', 'integer');
            $json->whereType('0.confirmed', 'integer');
        });
    }

    /**
     * Test getting an API transaction.
     */
    public function test_get_user_endpoint(): void
    {
        $transaction = Transaction::factory()->create();
        $response = $this->getJson('api/transaction/' . $transaction->id);
        $response->assertStatus(Response::HTTP_OK)
                ->assertJsonStructure(['id','payee_id','payer_id','amount','confirmed']);;
    }

    /**
     * Test storing an API transaction 
     */
    public function test_post_transaction_endpoint(): void
    {
        $user_id_payee = User::factory()->create()->id;
        $user_id_payer = User::factory()->create()->id;

        $transactionData = [
            'amount' => fake()->randomNumber(5, true),
            'confirmed' => 0,
            'payer_id' => $user_id_payer,
            'payee_id' => $user_id_payee
        ];

        $response = $this->postJson('api/transaction', $transactionData);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('transactions', [
            'amount' => $transactionData['amount'],
            'confirmed' => 0,
            'payer_id' => $user_id_payer,
            'payee_id' => $user_id_payee
        ]);

        $transaction = $response->json();
        $transaction = Transaction::findOrFail($transaction['id']);
        $this->assertEquals($transactionData['amount'], $transaction['amount']);
    }

    /**
     *  Test updating an API transaction
     */
    public function test_update_user_endpoint(): void
    {
        $transaction = Transaction::factory()->create();
        $updatedTransaction = [
            'amount' => fake()->randomNumber(7, true)
        ];

        $response = $this->json('PUT', 'api/transaction/' . $transaction->id, $updatedTransaction);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'amount' => $updatedTransaction['amount'],
        ]);

        $transaction = $this->getJson('api/transaction/' . $transaction->id);
        $this->assertEquals($updatedTransaction['amount'], $transaction['amount']);
        $this->assertEquals($transaction['confirmed'], 0);
    }
}