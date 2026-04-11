<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RentalApiTest extends TestCase
{
    use RefreshDatabase;

    private function actingApiUser(): void
    {
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_prevents_overlapping_rentals(): void
    {
        $this->actingApiUser();
        $car = Car::factory()->create();
        $customer = Customer::factory()->create();

        Rental::factory()->create([
            'car_id' => $car->id,
            'customer_id' => $customer->id,
            'start_date' => '2026-04-10',
            'end_date' => '2026-04-12',
            'status' => 'reserved',
        ]);

        $response = $this->postJson('/api/rentals', [
            'car_id' => $car->id,
            'customer_id' => $customer->id,
            'start_date' => '2026-04-11',
            'end_date' => '2026-04-14',
        ]);

        $response->assertStatus(422);
    }

    public function test_price_is_calculated_from_daily_rate(): void
    {
        $this->actingApiUser();
        $car = Car::factory()->create(['daily_rate' => 50]);
        $customer = Customer::factory()->create();

        $response = $this->postJson('/api/rentals', [
            'car_id' => $car->id,
            'customer_id' => $customer->id,
            'start_date' => '2026-04-10',
            'end_date' => '2026-04-12',
        ])->assertCreated();

        $this->assertEquals(150.00, $response->json('total_price'));
    }

    public function test_calendar_endpoint_returns_bookings(): void
    {
        $this->actingApiUser();
        $car = Car::factory()->create();
        Rental::factory()->count(2)->create(['car_id' => $car->id]);

        $this->getJson("/api/cars/{$car->id}/calendar")
            ->assertOk()
            ->assertJsonStructure(['car', 'bookings']);
    }
}
