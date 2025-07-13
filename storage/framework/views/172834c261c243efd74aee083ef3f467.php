<?php $__env->startSection('content'); ?>
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-[#3a1d09]">Attendance Report</h1>
    <?php if(session('error')): ?>
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>
    <form action="<?php echo e(route('attendance.report.show')); ?>" method="GET" class="bg-white p-6 rounded-lg shadow-md max-w-xl mx-auto">
        <div class="mb-4">
            <label for="service_id" class="block text-[#3a1d09] font-semibold mb-2">Select Service</label>
            <select name="service_id" id="service_id" class="w-full border border-[#f58502] rounded px-3 py-2" required>
                <option value="">-- Choose a Service --</option>
                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($service->id); ?>">
                        <?php if(!empty($service->is_auto) && $service->is_auto): ?>
                            <?php echo e($service->service_date); ?>

                        <?php else: ?>
                            <?php echo e($service->name); ?> (<?php echo e($service->service_date); ?>)
                        <?php endif; ?>
                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-[#3a1d09] font-semibold mb-2">Report Type</label>
            <select name="filter" class="w-full border border-[#f58502] rounded px-3 py-2">
                <option value="all">All Attendees</option>
                <option value="first_timers">First Timers Only</option>
            </select>
        </div>
        <button type="submit" class="bg-[#f58502] text-white px-6 py-2 rounded shadow hover:bg-orange-600 font-bold">View Report</button>
    </form>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/attendance/report/index.blade.php ENDPATH**/ ?>