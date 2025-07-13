<?php $__env->startSection('content'); ?>
    <div class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Services')); ?>

            </h2>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl font-semibold mb-6">Available Services</h3>
                    <?php if(session('success')): ?>
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="space-y-4">
                        <?php $__empty_1 = true; $__currentLoopData = $allServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                                <div>
                                    <p class="font-bold text-lg text-gray-800"><?php echo e($service['name']); ?></p>
                                    <p class="text-sm text-gray-600"><?php echo e(\Carbon\Carbon::parse($service['service_date'])->format('l, F j, Y')); ?></p>
                                </div>
                                <div>
                                    <?php if(isset($service['id'])): ?>
                                        <a href="<?php echo e(route('attendance.index', ['service' => $service['id']])); ?>" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                                            Take Attendance
                                        </a>
                                    <?php else: ?>
                                        <button class="bg-gray-300 text-gray-500 px-4 py-2 rounded-md cursor-not-allowed" disabled>
                                            Take Attendance
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-gray-500">No services available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/services/index.blade.php ENDPATH**/ ?>