<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->randomNumber(5, true),
            'confirmed' => 0,
            'payer_id' => function () {return User::factory()->create()->id;},
            'payee_id' => function () {return User::factory()->create()->id;}
        ];
    }
}
