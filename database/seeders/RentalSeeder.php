<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rental;
use App\Models\Car;
use App\Models\Customer;

class RentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = Car::all();
        $customers = Customer::all();

        if ($cars->isEmpty() || $customers->isEmpty()) {
            $this->command?->warn('Seed cars and customers before rentals.');
            return;
        }

        Rental::factory()
            ->count(25)
            ->state(function () use ($cars, $customers) {
                return [
                    'car_id' => $cars->random()->id,
                    'customer_id' => $customers->random()->id,
                ];
            })
            ->create();
    }
}
