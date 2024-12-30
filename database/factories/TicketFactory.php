<?php

namespace Database\Factories;

use App\Models\TicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'price' => fake()->randomFloat(2, 10, 100), // Price between 10 and 100
            'quantity' => fake()->numberBetween(50, 200),
            'sale_starts_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'sale_ends_at' => fake()->dateTimeBetween('now', '+1 month'),
            'deleted_at' => null, // Assuming soft deletes are enabled
    ];
    }
}
