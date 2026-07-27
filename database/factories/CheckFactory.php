<?php

namespace Database\Factories;

use App\CheckStatus;
use App\Models\Api;
use App\Models\Check;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Check>
 */
class CheckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(CheckStatus::cases());
        return [
            'api_id' => Api::factory(),

            'status' => $status,

            'status_code' => match ($status) {
                CheckStatus::ONLINE => 200,
                CheckStatus::OFFLINE => fake()->randomElement([500, 502, 503]),
                CheckStatus::TIMEOUT => null,
            },

            'response_time' => match ($status) {
                CheckStatus::ONLINE => fake()->numberBetween(40, 350),
                CheckStatus::OFFLINE => fake()->numberBetween(50, 500),
                CheckStatus::TIMEOUT => null,
            },

            'response_message' => match ($status) {
                CheckStatus::ONLINE => null,
                CheckStatus::OFFLINE => fake()->randomElement([
                    'Internal Server Error',
                    'Bad Gateway',
                    'Service Unavailable',
                ]),
                CheckStatus::TIMEOUT => 'Connection timed out',
            },

            'checked_at' => fake()->dateTimeBetween('-30 days'),
        ];
    }
}
