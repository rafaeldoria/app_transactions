<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test getting an API all users.
     */
    public function test_get_all_users_endpoint(): void
    {
        User::factory(3)->create();
        $response = $this->getJson('/api/user');
        
        $response->assertStatus(Response::HTTP_OK)
                ->assertJsonCount(3)
                ->assertSee(['id','name','email']);

        $response->assertJson(function (AssertableJson $json){
            $json->whereType('0.id', 'integer');
            $json->whereType('0.email', 'string');
        });
    }

    /**
     * Test getting an API user.
     */
    public function test_get_user_endpoint(): void
    {
        $user = User::factory()->create();
        
        $response = $this->getJson('api/user/' . $user->id);

        $response->assertStatus(Response::HTTP_OK)
                ->assertJsonStructure(['id','name', 'email']);
    }

    /**
     * Test storing an API user 
     * and create wallet by service
     */
    public function test_post_user_endpoint(): void
    {
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('987654'),
            'remember_token' => Str::random(10)
        ];
        $response = $this->postJson('api/user', $userData);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email']
        ]);

        $user = User::where('email', $userData['email'])->first();
        $this->assertTrue(Hash::check('987654', $user->password));
    }

    /**
     *  Test updating an API user
     */
    public function test_update_user_endpoint(): void
    {
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('987654'),
        ];
        $user = User::Factory()->create($userData);
        $updatedUserData = [
            'name' => fake()->name,
            'password' => Hash::make('123456')
        ];

        $response = $this->json('PUT', 'api/user/' . $user->id, $updatedUserData);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $updatedUserData['name'],
            'email' => $userData['email']
        ]);
        $user = $user->fresh();
        $this->assertTrue(Hash::check('123456', $user->password));
    }

    /**
     * Test destroy an user
     */
    public function test_delete_user_endpoint(): void
    {
        $user = User::factory()->create();

        $response = $this->json('DELETE', 'api/user/' . $user->id);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /**
     * Test create and find an User with type
     */
    public function test_post_user_endpoint_with_type_and_find_by_type(): void
    {
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'type' => User::__COMMOM__,
            'email_verified_at' => now(),
            'password' => Hash::make('11223344'),
            'remember_token' => Str::random(10),
        ];
        $response = $this->postJson('api/user', $userData);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email']
        ]);

        $commonUser = User::where('type', User::__COMMOM__)->first();

        $this->assertNotNull($commonUser);
        $this->assertEquals(User::__COMMOM__, $commonUser->type);
    }
}
