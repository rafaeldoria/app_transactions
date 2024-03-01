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
    public function test_get_all_wallets_endpoint(): void
    {
        User::factory(3)->create();
        
        Wallet::factory(1)->create();
        $response = $this->getJson('/api/wallet');
        
        $response->assertStatus(Response::HTTP_OK)
                ->assertJsonCount(1,'data')
                ->assertSee(['user_id','amount']);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'amount',
                    'user'
                ],
            ],
        ]);
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
                ->assertJsonStructure(['data' => ['id', 'user_id', 'amount']]);
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
        $wallet = $this->getJson('api/wallet/getByUser/' . $userResponse['data']['id']);

        $updatedWallet = [
            'user_id' => $userResponse['data']['id'],
            'amount' => fake()->randomNumber(5, true)
        ];
        $response = $this->json('PUT', 'api/wallet/' . $wallet['data']['id'], $updatedWallet);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('wallets', [
            'id' => $wallet['data']['id'],
            'user_id' => $wallet['data']['user_id'],
            'amount' => $updatedWallet['amount']
        ]);
        $wallet = $this->getJson('api/wallet/' . $wallet['data']['id']);
        $this->assertEquals($updatedWallet['amount'], $wallet['data']['amount']);
    }

     /**
     * Test getting an API wallet
     * by user.
     */
    public function test_get_wallet_by_user_endpoint(): void
    {
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('987654'),
        ];
        $user = $this->postJson('api/user', $userData);
        $response = $this->getJson('api/wallet/getByUser/' . $user['data']['id']);

        $response->assertStatus(Response::HTTP_OK)
                ->assertJsonStructure(['data' => ['id','amount','user_id']]);
    }

    
}
