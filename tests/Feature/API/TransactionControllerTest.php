<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
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
    public function test_get_transaction_endpoint(): void
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
        $user_payer = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'type' => User::__COMMOM__,
            'email_verified_at' => now(),
            'password' => Hash::make('11223344'),
            'remember_token' => Str::random(10),
        ];
        $user_payer = $this->postJson('api/user', $user_payer);
        $wallet = $this->getJson('api/wallet/getByUser/' . $user_payer['data']['id']);
        $updatedWallet = [
            'user_id' => $user_payer['data']['id'],
            'amount' => 52525
        ];
        $this->json('PUT', 'api/wallet/' . $wallet['id'], $updatedWallet);

        $user_payee = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'type' => User::__COMMOM__,
            'email_verified_at' => now(),
            'password' => Hash::make('11223344'),
            'remember_token' => Str::random(10),
        ];
        $user_payee = $this->postJson('api/user', $user_payee);
        $wallet = $this->getJson('api/wallet/getByUser/' . $user_payee['data']['id']);
        $updatedWallet = [
            'user_id' => $user_payee['data']['id'],
            'amount' => 12121
        ];
        $this->json('PUT', 'api/wallet/' . $wallet['id'], $updatedWallet);

        $transactionData = [
            'amount' => 10000,
            'confirmed' => 0,
            'payer_id' => $user_payer['data']['id'],
            'payee_id' => $user_payee['data']['id']
        ];

        $response = $this->postJson('api/transaction', $transactionData);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('transactions', [
            'amount' => $transactionData['amount'],
            'payer_id' => $user_payer['data']['id'],
            'payee_id' => $user_payee['data']['id']
        ]);

        $transaction = $response->json();
        $transaction = Transaction::findOrFail($transaction['id']);
        $this->assertEquals($transactionData['amount'], $transaction['amount']);
    }

    /**
     *  Test updating an API transaction
     */
    public function test_update_transaction_endpoint(): void
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

     /**
     *  Test to confirm transaction
     */
    public function test_to_confirm_transaction_endpoint(): void
    {
        $user_payer = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'type' => User::__COMMOM__,
            'email_verified_at' => now(),
            'password' => Hash::make('11223344'),
            'remember_token' => Str::random(10),
        ];
        $user_payer = $this->postJson('api/user', $user_payer);
        $wallet = $this->getJson('api/wallet/getByUser/' . $user_payer['data']['id']);
        $updatedWallet = [
            'user_id' => $user_payer['data']['id'],
            'amount' => 52525
        ];
        $this->json('PUT', 'api/wallet/' . $wallet['id'], $updatedWallet);

        $user_payee = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'type' => User::__COMMOM__,
            'email_verified_at' => now(),
            'password' => Hash::make('11223344'),
            'remember_token' => Str::random(10),
        ];
        $user_payee = $this->postJson('api/user', $user_payee);
        $wallet = $this->getJson('api/wallet/getByUser/' . $user_payee['data']['id']);
        $updatedWallet = [
            'user_id' => $user_payee['data']['id'],
            'amount' => 12121
        ];
        $this->json('PUT', 'api/wallet/' . $wallet['id'], $updatedWallet);
        $transactionData = [
            'amount' => 10000,
            'payer_id' => $user_payer['data']['id'],
            'payee_id' => $user_payee['data']['id']
        ];

        $transaction = $this->postJson('api/transaction', $transactionData);

        $response = $this->json('PUT', 'api/transaction/toconfirm/' . $transaction['id']);

        $response->assertStatus(Response::HTTP_OK); 
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction['id'],
            'amount' => $transaction['amount']
        ]);
        $transaction = $this->getJson('api/transaction/' . $transaction['id']);
        $this->assertTrue(($transaction['confirmed']) == 1);
    }
}