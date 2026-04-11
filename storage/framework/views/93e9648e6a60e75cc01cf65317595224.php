<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book <?php echo e($car->make); ?> <?php echo e($car->model); ?></title>
    <!-- Bootstrap (requested) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <!-- Tailwind for existing styles -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-900">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="mb-4">
            <a href="<?php echo e(url('/')); ?>" class="inline-flex items-center gap-2 text-slate-700 hover:underline">
                <span>←</span> Back to Browse
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white rounded-2xl shadow border border-slate-100 p-6">
                <h2 class="text-xl font-semibold mb-6">Booking Details</h2>

                <form class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Personal Information</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-semibold">First Name *</label>
                                <input class="input" placeholder="First Name">
                            </div>
                            <div>
                                <label class="text-sm font-semibold">Last Name *</label>
                                <input class="input" placeholder="Last Name">
                            </div>
                            <div>
                                <label class="text-sm font-semibold">Email *</label>
                                <input class="input" placeholder="Email">
                            </div>
                            <div>
                                <label class="text-sm font-semibold">Phone Number *</label>
                                <input class="input" placeholder="Phone Number">
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-semibold">Driver's License Number *</label>
                                <input class="input" placeholder="License Number">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">Rental Period</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-semibold">Pick-up Date *</label>
                                <input type="date" class="input">
                            </div>
                            <div>
                                <label class="text-sm font-semibold">Return Date *</label>
                                <input type="date" class="input">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">Payment Information</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-semibold">Card Number *</label>
                                <input class="input" placeholder="•••• •••• •••• ••••">
                            </div>
                            <div>
                                <label class="text-sm font-semibold">Cardholder Name *</label>
                                <input class="input" placeholder="Name on card">
                            </div>
                            <div>
                                <label class="text-sm font-semibold">Expiry Date (MM/YY) *</label>
                                <input class="input" placeholder="MM/YY">
                            </div>
                            <div>
                                <label class="text-sm font-semibold">CVV *</label>
                                <input class="input" placeholder="CVV">
                            </div>
                        </div>
                    </div>

                    <button type="button" class="w-full bg-slate-500 text-white py-3 rounded-lg font-semibold hover:bg-slate-600 transition">Confirm Booking</button>
                </form>
            </div>

            <aside class="bg-white rounded-2xl shadow border border-slate-100 p-6">
                <h3 class="text-lg font-semibold mb-4">Booking Summary</h3>
                <img src="<?php echo e($image); ?>" alt="<?php echo e($car->make); ?> <?php echo e($car->model); ?>" class="w-full h-40 object-cover rounded-lg mb-4">
                <div class="space-y-2">
                    <div>
                        <h4 class="text-xl font-semibold"><?php echo e($car->make); ?> <?php echo e($car->model); ?></h4>
                        <p class="text-slate-500"><?php echo e($car->year); ?></p>
                    </div>
                    <div class="space-y-1 text-sm text-slate-700">
                        <div class="flex items-center gap-2">
                            <span>🚘</span><span>5 seats</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>⚡</span><span>Automatic</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>⛽</span><span>Fuel</span>
                        </div>
                    </div>
                    <div class="border-t border-slate-200 pt-3 mt-2 text-sm">
                        <div class="flex justify-between"><span>Rate per day</span><span>$<?php echo e(number_format($car->daily_rate, 0)); ?></span></div>
                        <div class="flex justify-between"><span>Number of days</span><span>1</span></div>
                    </div>
                    <div class="flex justify-between items-center text-lg font-bold mt-1">
                        <span>Total</span><span>$<?php echo e(number_format($car->daily_rate, 0)); ?></span>
                    </div>
                    <div class="pt-3">
                        <p class="text-sm font-semibold mb-2">Features</p>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="px-3 py-1 bg-slate-100 rounded-full text-xs font-semibold text-slate-800"><?php echo e($feature); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <style>
        .input { width: 100%; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 12px; background: #f8fafc; }
    </style>
</body>
</html>
<?php /**PATH C:\Users\Chan Anito\OneDrive\Desktop\OrangeCrush\OrangeCrush\resources\views/booking.blade.php ENDPATH**/ ?>