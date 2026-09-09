<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
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
            'total' => fake()->randomFloat(2, 10, 150),
            'status' => Order::STATUS_IN_ATTESA,
            'shipping_address' => fake()->streetAddress().', '.fake()->postcode().' '.fake()->city(),
            'payment_method' => fake()->randomElement([Order::PAYMENT_CONTRASSEGNO, Order::PAYMENT_BONIFICO]),
        ];
    }
}
