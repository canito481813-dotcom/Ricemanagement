<?php

namespace Database\Factories;

use App\Models\Rental;
use App\Models\Car;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rental>
 */
class RentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $end = (clone $start)->modify('+' . $this->faker->numberBetween(1, 14) . ' days');

        return [
            'car_id' => Car::factory(),
            'customer_id' => Customer::factory(),
            'start_date' => $start,
            'end_date' => $end,
            'total_price' => $this->faker->randomFloat(2, 100, 1200),
            'status' => $this->faker->randomElement(['reserved', 'active', 'completed']),
            'pickup_location' => $this->faker->city(),
            'dropoff_location' => $this->faker->city(),
        ];
    }
}
