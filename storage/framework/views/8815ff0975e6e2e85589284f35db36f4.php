<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Car Rental</title>
    <!-- Bootstrap (requested) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <!-- Tailwind for existing styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .pill { @apply inline-flex items-center rounded-full px-3 py-1 bg-slate-900 text-white text-xs font-semibold; }
    </style>
</head>
<body class="bg-white text-slate-900">
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold text-uppercase" href="/">OrangeCrush</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#browse">Browse</a></li>
                    <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="relative">
        <img src="/hero.jpg" class="w-full h-[360px] object-cover" alt="OrangeCrush Car Rental">
        <div class="absolute inset-0 bg-black/35"></div>
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
            <h1 class="text-4xl md:text-5xl font-extrabold">OrangeCrush Car Rental</h1>
            <p class="text-xl mt-2">Find the perfect vehicle for your journey</p>
            <a href="#browse" class="mt-6 bg-white text-slate-900 px-5 py-3 rounded-full font-semibold shadow hover:-translate-y-0.5 transition">Browse Vehicles</a>
        </div>
    </header>

    <main class="relative z-10 max-w-6xl mx-auto px-4 -mt-6 md:-mt-10">
        <section class="bg-white shadow-xl rounded-2xl pt-8 pb-6 px-6 mb-10">
            <h2 class="text-2xl font-semibold mb-4">Search & Filter</h2>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="col-span-3 md:col-span-1 md:col-start-1">
                    <div class="relative">
                        <input type="text" placeholder="Search by car name or brand..." class="w-full rounded-xl border border-slate-200 px-4 py-3 pl-10 focus:ring-2 focus:ring-slate-900 focus:outline-none">
                        <span class="absolute left-3 top-3 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/></svg>
                        </span>
                    </div>
                </div>
                <select class="rounded-xl border border-slate-200 px-4 py-3 focus:ring-2 focus:ring-slate-900 focus:outline-none">
                    <option>All Types</option>
                    <option>Sedan</option>
                    <option>SUV</option>
                    <option>Coupe</option>
                </select>
                <select class="rounded-xl border border-slate-200 px-4 py-3 focus:ring-2 focus:ring-slate-900 focus:outline-none">
                    <option>All Transmissions</option>
                    <option>Automatic</option>
                    <option>Manual</option>
                </select>
            </div>
        </section>

        <section id="browse" class="mb-14">
            <div class="flex items-center gap-3 mb-4">
                <h2 class="text-2xl font-semibold">Available Vehicles</h2>
                <span class="text-slate-500"><?php echo e($cars->count()); ?> vehicles available</span>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <?php $__currentLoopData = $cars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $type = $car->status === 'available' ? 'Available' : ucfirst($car->status);
                        $img = [
                            'sedan' => 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?auto=format&fit=crop&w=1200&q=80',
                            'suv' => 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?auto=format&fit=crop&w=1200&q=80',
                        ][$car->status === 'available' ? 'sedan' : 'suv'] ?? 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?auto=format&fit=crop&w=1200&q=80';
                    ?>
                    <article class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden border border-slate-100">
                        <div class="relative">
                            <img src="<?php echo e($img); ?>" alt="<?php echo e($car->make); ?> <?php echo e($car->model); ?>" class="w-full h-56 object-cover">
                            <span class="pill absolute top-4 right-4 bg-slate-900 text-white"><?php echo e($type); ?></span>
                        </div>
                        <div class="p-5 flex flex-col gap-3">
                            <div>
                                <h3 class="text-xl font-semibold"><?php echo e($car->make); ?> <?php echo e($car->model); ?></h3>
                                <p class="text-slate-500"><?php echo e($car->year); ?></p>
                            </div>
                            <div class="flex items-center gap-4 text-sm text-slate-600">
                                <div class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 19V6a2 2 0 012-2h8a2 2 0 012 2v13M6 19h12M6 19H4m14 0h2"/></svg>
                                    <span>Auto</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m6-6H6"/></svg>
                                    <span><?php echo e($car->license_plate); ?></span>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="pill bg-slate-100 text-slate-800">Daily Rate: $<?php echo e(number_format($car->daily_rate, 0)); ?></span>
                                <span class="pill bg-slate-100 text-slate-800">Status: <?php echo e($car->status); ?></span>
                            </div>
                            <div class="flex items-center justify-between pt-2">
                                <div>
                                    <div class="text-2xl font-bold">$<?php echo e(number_format($car->daily_rate, 0)); ?></div>
                                    <p class="text-sm text-slate-500">per day</p>
                                </div>
                                <a href="<?php echo e(route('booking.show', $car)); ?>" class="bg-slate-900 text-white px-4 py-2 rounded-full font-semibold hover:-translate-y-0.5 transition">Book Now</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    </main>
</body>
</html>
<?php /**PATH C:\Users\Chan Anito\OneDrive\Desktop\OrangeCrush\OrangeCrush\resources\views/landing.blade.php ENDPATH**/ ?>