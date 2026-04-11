<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | OrangeCrush</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <style>
        body { background: #f5f6fa; }
        .sidebar { width: 240px; min-height: 100vh; background: #111c3d; color: #fff; }
        .sidebar .nav-link { color: #d7dbec; border-radius: 10px; padding: 10px 14px; }
        .sidebar .nav-link.active { background: #6a5cff; color: #fff; }
        .sidebar .nav-link:hover { background: #1c2a52; color: #fff; }
        .stat-card { border: 1px solid #e9eef5; border-radius: 14px; }
        .chart-placeholder { height: 280px; border-radius: 14px; border: 1px solid #e9eef5; background: #fff; }
        .mini-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3">
        <div class="d-flex align-items-center mb-4">
            <span class="fs-5 fw-bold">Car Rental System</span>
        </div>
        <nav class="nav flex-column gap-1">
            <a class="nav-link active" href="/dashboard">🏠 Dashboard</a>
            <a class="nav-link" href="#fleet">🚗 Car Management</a>
            <a class="nav-link" href="#brands">🏷️ Brands</a>
            <a class="nav-link" href="#bookings">📅 Bookings</a>
            <a class="nav-link" href="#transactions">💳 Transactions</a>
            <a class="nav-link" href="#maintenance">🛠️ Maintenance</a>
            <a class="nav-link" href="#employees">👥 Employees</a>
            <a class="nav-link" href="/">↩️ Back to Landing</a>
        </nav>
    </div>

    <!-- Main -->
    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Dashboard</h4>
                <div class="text-muted">At a glance overview</div>
            </div>
            <button class="btn btn-primary">Add Booking</button>
        </div>

        <!-- Stat cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="p-3 bg-white stat-card">
                    <div class="text-muted">Total Fleet</div>
                    <div class="h3 fw-bold">{{ $totalFleet }}</div>
                    <div class="text-muted small">All vehicles</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-white stat-card">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Available</span>
                        <span class="text-success">↑</span>
                    </div>
                    <div class="h3 fw-bold">{{ $availableFleet }}</div>
                    <div class="text-success small">Ready to rent</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-white stat-card">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">On Rent</span>
                        <span class="text-primary">📅</span>
                    </div>
                    <div class="h3 fw-bold">{{ $onRent }}</div>
                    <div class="text-muted small">Active rentals</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-white stat-card">
                    <div class="text-muted">Revenue Today</div>
                    <div class="h3 fw-bold">${{ number_format($revenueToday, 0) }}</div>
                    <div class="text-success small">+12% vs yesterday</div>
                </div>
            </div>
        </div>

        <!-- Charts row -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="chart-placeholder p-3">
                    <h6>Weekly Rental Activity</h6>
                    <div class="text-muted small">Demo chart placeholder</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-placeholder p-3">
                    <h6>Monthly Revenue</h6>
                    <div class="text-muted small">Demo chart placeholder</div>
                </div>
            </div>
        </div>

        <!-- Lists -->
        <div class="row g-3">
            <div class="col-md-6">
                <div class="bg-white p-3 stat-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">Active Rentals</h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <input class="form-control form-control-sm" style="max-width: 180px;" placeholder="Search...">
                            <button class="btn btn-outline-secondary btn-sm"><span class="bi bi-funnel"></span> Filter</button>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse ($rentals as $rental)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="fw-semibold">{{ $rental->customer->name ?? 'Customer' }}</div>
                                        <div class="text-muted small">{{ $rental->car->make ?? '' }} {{ $rental->car->model ?? '' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">${{ number_format($rental->total_price, 0) }}</div>
                                        <div class="text-muted small">R-{{ $rental->id }}</div>
                                    </div>
                                </div>
                                <div class="text-muted small mt-1">
                                    Pickup: {{ optional($rental->start_date)->format('Y-m-d') }}  →  Return: {{ optional($rental->end_date)->format('Y-m-d') }}
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-muted">No active rentals.</div>
                        @endforelse
                    </div>
                    <div class="mt-2">
                        {{ $rentals->withQueryString()->links() }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-white p-3 stat-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Fleet Status by Category</h6>
                        <span class="text-muted small">Live overview</span>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        @foreach ($categoryStats as $cat)
                            @php
                                $total = $cat['available'] + $cat['rented'] + $cat['maintenance'];
                                $availPct = $total ? ($cat['available'] / $total) * 100 : 0;
                                $rentPct = $total ? ($cat['rented'] / $total) * 100 : 0;
                                $maintPct = $total ? ($cat['maintenance'] / $total) * 100 : 0;
                            @endphp
                            <div>
                                <div class="d-flex justify-content-between">
                                    <div class="fw-semibold">{{ $cat['label'] }}</div>
                                    <div class="text-muted small">{{ $total }} total</div>
                                </div>
                                <div class="small text-muted mb-1">
                                    <span class="mini-dot bg-success"></span>{{ $cat['available'] }} available
                                    <span class="mini-dot bg-primary ms-2"></span>{{ $cat['rented'] }} rented
                                    <span class="mini-dot bg-warning ms-2"></span>{{ $cat['maintenance'] }} maintenance
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $availPct }}%"></div>
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $rentPct }}%"></div>
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $maintPct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>
