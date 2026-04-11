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
                    <?php if(session('status')): ?>
                        <div class="alert alert-success py-2"><?php echo e(session('status')); ?></div>
                    <?php endif; ?>
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="/car-management" class="vstack gap-3">
                        <?php echo csrf_field(); ?>
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
                        <span class="badge bg-secondary"><?php echo e($cars->total()); ?> vehicles</span>
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $cars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($car->make); ?> <?php echo e($car->model); ?></td>
                                        <td><?php echo e($car->year); ?></td>
                                        <td><?php echo e($car->license_plate); ?></td>
                                        <td>$<?php echo e(number_format($car->daily_rate, 2)); ?></td>
                                        <td>
                                            <span class="badge
                                                <?php if($car->status === 'available'): ?> bg-success
                                                <?php elseif($car->status === 'rented'): ?> bg-primary
                                                <?php else: ?> bg-warning text-dark
                                                <?php endif; ?>">
                                                <?php echo e(ucfirst($car->status)); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <?php echo e($cars->withQueryString()->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\Users\Chan Anito\OneDrive\Desktop\OrangeCrush\OrangeCrush\resources\views/car-management.blade.php ENDPATH**/ ?>