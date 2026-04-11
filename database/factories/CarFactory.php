<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $make = $this->faker->randomElement(['Toyota', 'Honda', 'Ford', 'Nissan', 'Hyundai', 'Chevrolet', 'Kia']);
        $model = $this->faker->randomElement(['Corolla', 'Civic', 'Camry', 'Focus', 'Altima', 'Elantra', 'Soul']);

        return [
            'make' => $make,
            'model' => $model,
            'year' => $this->faker->numberBetween(2015, 2024),
            'license_plate' => strtoupper($this->faker->bothify('???-####')),
            'daily_rate' => $this->faker->randomFloat(2, 30, 120),
            'status' => 'available',
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
