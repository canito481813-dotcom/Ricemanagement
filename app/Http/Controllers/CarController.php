<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Rental;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Car::orderByDesc('created_at')->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1990|max:' . (now()->year + 1),
            'license_plate' => 'required|string|max:20|unique:cars,license_plate',
            'daily_rate' => 'required|numeric|min:0',
            'status' => 'sometimes|in:available,rented,maintenance',
            'notes' => 'nullable|string',
        ]);

        $car = Car::create($validated);

        return response()->json($car, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        return $car;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'make' => 'sometimes|string|max:255',
            'model' => 'sometimes|string|max:255',
            'year' => 'sometimes|integer|min:1990|max:' . (now()->year + 1),
            'license_plate' => 'sometimes|string|max:20|unique:cars,license_plate,' . $car->id,
            'daily_rate' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:available,rented,maintenance',
            'notes' => 'nullable|string',
        ]);

        $car->update($validated);

        return response()->json($car->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        $car->delete();

        return response()->noContent();
    }

    /**
     * Return booked date ranges for a car (availability calendar).
     */
    public function calendar(Car $car)
    {
        $bookings = Rental::where('car_id', $car->id)
            ->select('id', 'start_date', 'end_date', 'status')
            ->orderBy('start_date')
            ->get();

        return [
            'car' => $car,
            'bookings' => $bookings,
        ];
    }
}
