<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WalletControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test getting an API all wallets.
     */
    public function test_get_all_users_endpoint(): void
    {
        User::factory(3)->create();
        
        Wallet::factory(1)->create();
        $response = $this->getJson('/api/wallet');
        
        $response->assertStatus(Response::HTTP_OK)
                ->assertJsonCount(1)
                ->assertSee(['user_id','amount']);

        $response->assertJson(function (AssertableJson $json){
            $json->whereType('0.id', 'integer');
            $json->whereType('0.amount', 'integer');
        });
    }

    /**
     * Test getting an API wallet.
     */
    public function test_get_wallet_endpoint(): void
    {
        User::factory(3)->create();

        $wallet = Wallet::factory()->create();
        
        $response = $this->getJson('api/wallet/' . $wallet->id);
        
        $response->assertStatus(Response::HTTP_OK)
                ->assertJsonStructure(['id', 'user_id', 'amount']);
    }

    /**
     *  Test updating an API Wallet
     * and creating user to create a wallet 
     * by service
     */
    public function test_update_wallet_endpoint(): void
    {
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('987654'),
        ];
        $userResponse = $this->postJson('api/user', $userData);

        $userResponse->assertStatus(Response::HTTP_CREATED);
        $wallet = $this->getJson('api/wallet/getByUser/' . $userResponse['id']);

        $updatedWallet = [
            'user_id' => $userResponse['id'],
            'amount' => fake()->randomNumber(5, true)
        ];
        $response = $this->json('PUT', 'api/wallet/' . $wallet['id'], $updatedWallet);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('wallets', [
            'id' => $wallet['id'],
            'user_id' => $wallet['user_id'],
            'amount' => $updatedWallet['amount']
        ]);
        $wallet = $this->getJson('api/wallet/' . $wallet['id']);
        $this->assertEquals($updatedWallet['amount'], $wallet['amount']);
    }

    
}
