<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Car;
use Illuminate\Validation\Rule;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Rental::with(['car', 'customer'])
            ->orderByDesc('start_date')
            ->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'customer_id' => 'required|exists:customers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => ['sometimes', Rule::in(['reserved', 'active', 'completed', 'cancelled'])],
            'pickup_location' => 'nullable|string|max:255',
            'dropoff_location' => 'nullable|string|max:255',
        ]);

        $status = $validated['status'] ?? 'reserved';

        $this->ensureCarIsAvailable($validated['car_id'], $validated['start_date'], $validated['end_date']);

        $price = $this->calculatePrice($validated['car_id'], $validated['start_date'], $validated['end_date']);

        $rental = Rental::create(array_merge($validated, [
            'status' => $status,
            'total_price' => $price,
        ]));

        $this->syncCarStatus($rental);

        return response()->json($rental->load(['car', 'customer']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rental $rental)
    {
        return $rental->load(['car', 'customer']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rental $rental)
    {
        $validated = $request->validate([
            'car_id' => 'sometimes|exists:cars,id',
            'customer_id' => 'sometimes|exists:customers,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'status' => ['sometimes', Rule::in(['reserved', 'active', 'completed', 'cancelled'])],
            'pickup_location' => 'nullable|string|max:255',
            'dropoff_location' => 'nullable|string|max:255',
        ]);

        $newCarId = $validated['car_id'] ?? $rental->car_id;
        $newStart = $validated['start_date'] ?? $rental->start_date;
        $newEnd = $validated['end_date'] ?? $rental->end_date;

        if ($newCarId != $rental->car_id || $newStart != $rental->start_date || $newEnd != $rental->end_date) {
            $this->ensureCarIsAvailable($newCarId, $newStart, $newEnd, $rental->id);
        }

        $price = $this->calculatePrice($newCarId, $newStart, $newEnd);

        $rental->update(array_merge($validated, ['total_price' => $price]));
        $this->syncCarStatus($rental);

        return $rental->load(['car', 'customer']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rental $rental)
    {
        $rental->delete();
        $this->syncCarStatus($rental, true);

        return response()->noContent();
    }

    private function ensureCarIsAvailable(int $carId, $startDate, $endDate, ?int $ignoreRentalId = null): void
    {
        $conflict = Rental::where('car_id', $carId)
            ->when($ignoreRentalId, fn ($q) => $q->where('id', '!=', $ignoreRentalId))
            ->whereIn('status', ['reserved', 'active'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();

        if ($conflict) {
            abort(response()->json(['message' => 'Car is not available for the selected dates.'], 422));
        }
    }

    private function syncCarStatus(Rental $rental, bool $afterDelete = false): void
    {
        $car = Car::find($rental->car_id);
        if (! $car) {
            return;
        }

        if ($afterDelete || $rental->status === 'completed' || $rental->status === 'cancelled') {
            // If there are other active/reserved rentals keep status rented, else available
            $hasActive = Rental::where('car_id', $car->id)
                ->whereIn('status', ['reserved', 'active'])
                ->exists();
            $car->status = $hasActive ? 'rented' : 'available';
        } else {
            $car->status = 'rented';
        }

        $car->save();
    }

    private function calculatePrice(int $carId, $startDate, $endDate): float
    {
        $car = Car::findOrFail($carId);
        $days = now()->parse($startDate)->diffInDays(now()->parse($endDate)) + 1;

        return round($car->daily_rate * $days, 2);
    }
}
