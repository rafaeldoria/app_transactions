<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        $users = User::all();
        foreach ($users as $key => $user) {
            $wallet = [
                'user_id' => $user->id,
                'amount' => fake()->randomNumber(5, true)
            ];
            Wallet::create($wallet);
        }
    }
}
