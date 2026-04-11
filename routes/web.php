<?php

use Illuminate\Support\Facades\Route;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Rental;

Route::get('/', function () {
    $cars = Car::orderBy('status')->take(6)->get();
    return view('landing', ['cars' => $cars]);
});

Route::get('/booking/{car}', function (Car $car) {
    $image = 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?auto=format&fit=crop&w=1200&q=80';
    $features = ['Autopilot', 'Premium Sound', 'GPS Navigation', 'Bluetooth'];

    return view('booking', [
        'car' => $car,
        'image' => $image,
        'features' => $features,
    ]);
})->name('booking.show');

Route::get('/car-management', function () {
    $cars = Car::orderByDesc('created_at')->paginate(10);
    return view('car-management', ['cars' => $cars]);
})->name('cars.manage');

Route::post('/car-management', function (Illuminate\Http\Request $request) {
    $data = $request->validate([
        'make' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'year' => 'required|integer|min:1990|max:' . (now()->year + 1),
        'license_plate' => 'required|string|max:20|unique:cars,license_plate',
        'daily_rate' => 'required|numeric|min:0',
        'status' => 'required|in:available,rented,maintenance',
    ]);

    Car::create($data);

    return back()->with('status', 'Car added successfully.');
});

Route::patch('/car-management/{car}', function (Illuminate\Http\Request $request, Car $car) {
    $data = $request->validate([
        'make' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'year' => 'required|integer|min:1990|max:' . (now()->year + 1),
        'license_plate' => 'required|string|max:20|unique:cars,license_plate,' . $car->id,
        'daily_rate' => 'required|numeric|min:0',
        'status' => 'required|in:available,rented,maintenance',
    ]);

    $car->update($data);

    return back()->with('status', 'Car updated.');
})->name('cars.update');

Route::delete('/car-management/{car}', function (Car $car) {
    $car->delete();
    return back()->with('status', 'Car deleted.');
})->name('cars.destroy');

Route::get('/dashboard', function () {
    $totalFleet = Car::count();
    $availableFleet = Car::where('status', 'available')->count();
    $onRent = Rental::whereIn('status', ['reserved', 'active'])->count();
    $revenueToday = Rental::whereDate('start_date', now()->toDateString())->sum('total_price');

    $rentals = Rental::with(['car', 'customer'])->orderByDesc('start_date')->paginate(5);
    $categoryStats = [
        [
            'label' => 'Luxury',
            'available' => 8,
            'rented' => 14,
            'maintenance' => 2,
        ],
        [
            'label' => 'SUV',
            'available' => 5,
            'rented' => 12,
            'maintenance' => 1,
        ],
        [
            'label' => 'Sedan',
            'available' => 12,
            'rented' => 18,
            'maintenance' => 2,
        ],
        [
            'label' => 'Electric',
            'available' => 6,
            'rented' => 8,
            'maintenance' => 1,
        ],
    ];

    return view('dashboard', [
        'totalFleet' => $totalFleet,
        'availableFleet' => $availableFleet,
        'onRent' => $onRent,
        'revenueToday' => $revenueToday,
        'rentals' => $rentals,
        'categoryStats' => $categoryStats,
    ]);
});

Route::post('/dashboard/cars', function () {
    $data = request()->validate([
        'make' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'year' => 'required|integer|min:1990|max:' . (now()->year + 1),
        'license_plate' => 'required|string|max:20|unique:cars,license_plate',
        'daily_rate' => 'required|numeric|min:0',
    ]);

    Car::create($data);

    return back()->with('status', 'Car added.');
});

Route::post('/dashboard/customers', function () {
    $data = request()->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:customers,email',
        'phone' => 'nullable|string|max:30',
    ]);

    Customer::create($data);

    return back()->with('status', 'Customer added.');
});

Route::post('/dashboard/rentals', function () {
    $data = request()->validate([
        'car_id' => 'required|exists:cars,id',
        'customer_id' => 'required|exists:customers,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'pickup_location' => 'nullable|string|max:255',
        'dropoff_location' => 'nullable|string|max:255',
    ]);

    $car = Car::findOrFail($data['car_id']);
    $days = now()->parse($data['start_date'])->diffInDays(now()->parse($data['end_date'])) + 1;
    $data['total_price'] = round($car->daily_rate * $days, 2);
    $data['status'] = 'reserved';

    Rental::create($data);

    return back()->with('status', 'Rental created.');
});
