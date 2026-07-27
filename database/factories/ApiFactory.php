<?php

namespace Database\Factories;

use App\ApiMethod;
use App\Models\Api;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Api>
 */
class ApiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'name' => fake()->randomElement([
                'OpenAI',
                'GitHub',
                'Stripe',
                'ViaCEP',
                'JSONPlaceholder',
                'Payment API',
                'Authentication API',
                'Orders API',
            ]),

            'url' => fake()->url(),

            'method' => fake()->randomElement(ApiMethod::cases()),

            'expected_status' => 200,

            'timeout' => fake()->numberBetween(3, 10),

            'description' => fake()->optional()->sentence(),

            'is_active' => fake()->boolean(90),
        ];
    }
}
