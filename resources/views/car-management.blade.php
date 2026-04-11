<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Management | OrangeCrush</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold text-uppercase" href="/">OrangeCrush</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navCar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navCar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/">Landing</a></li>
                <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="/car-management">Car Management</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Add Car</h5>
                    @if (session('status'))
                        <div class="alert alert-success py-2">{{ session('status') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="/car-management" class="vstack gap-3">
                        @csrf
                        <div>
                            <label class="form-label">Make</label>
                            <input name="make" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label">Model</label>
                            <input name="model" class="form-control" required>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label">Year</label>
                                <input type="number" name="year" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Plate</label>
                                <input name="license_plate" class="form-control" required>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Daily Rate ($)</label>
                            <input type="number" step="0.01" name="daily_rate" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="available">Available</option>
                                <option value="rented">Rented</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        <button class="btn btn-primary w-100">Save</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Fleet</h5>
                        <span class="badge bg-secondary">{{ $cars->total() }} vehicles</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Make / Model</th>
                                    <th>Year</th>
                                    <th>Plate</th>
                                    <th>Rate</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cars as $car)
                                    <tr>
                                        <td>{{ $car->make }} {{ $car->model }}</td>
                                        <td>{{ $car->year }}</td>
                                        <td>{{ $car->license_plate }}</td>
                                        <td>${{ number_format($car->daily_rate, 2) }}</td>
                                        <td>
                                            <span class="badge
                                                @if($car->status === 'available') bg-success
                                                @elseif($car->status === 'rented') bg-primary
                                                @else bg-warning text-dark
                                                @endif">
                                                {{ ucfirst($car->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#edit-{{ $car->id }}">Edit</button>
                                                <form method="POST" action="{{ route('cars.destroy', $car) }}" onsubmit="return confirm('Delete this car?')" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger" type="submit">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="edit-{{ $car->id }}">
                                        <td colspan="6">
                                            <form method="POST" action="{{ route('cars.update', $car) }}" class="row g-2 align-items-end">
                                                @csrf
                                                @method('PATCH')
                                                <div class="col-md-2">
                                                    <label class="form-label">Make</label>
                                                    <input name="make" class="form-control form-control-sm" value="{{ $car->make }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Model</label>
                                                    <input name="model" class="form-control form-control-sm" value="{{ $car->model }}" required>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">Year</label>
                                                    <input type="number" name="year" class="form-control form-control-sm" value="{{ $car->year }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Plate</label>
                                                    <input name="license_plate" class="form-control form-control-sm" value="{{ $car->license_plate }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Rate ($)</label>
                                                    <input type="number" step="0.01" name="daily_rate" class="form-control form-control-sm" value="{{ $car->daily_rate }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select form-select-sm" required>
                                                        <option value="available" @selected($car->status === 'available')>Available</option>
                                                        <option value="rented" @selected($car->status === 'rented')>Rented</option>
                                                        <option value="maintenance" @selected($car->status === 'maintenance')>Maintenance</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <button class="btn btn-primary btn-sm w-100">Save</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div>
                        {{ $cars->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
