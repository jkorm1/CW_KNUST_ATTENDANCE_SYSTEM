<?php $__env->startSection('content'); ?>
<div class="container mx-auto py-6 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Fold Details</h1>
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-4">
            <span class="font-semibold">Name:</span>
            <span><?php echo e($fold->name); ?></span>
        </div>
        <div class="mb-4">
            <span class="font-semibold">Description:</span>
            <span><?php echo e($fold->description ?? 'No description'); ?></span>
        </div>
        <div class="mb-4">
            <span class="font-semibold">Cell:</span>
            <span><?php echo e($fold->cell->name ?? 'N/A'); ?></span>
        </div>
        <div class="mb-4">
            <span class="font-semibold">Status:</span>
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($fold->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                <?php echo e($fold->is_active ? 'Active' : 'Inactive'); ?>

            </span>
        </div>
        <div class="flex justify-end">
            <a href="<?php echo e(route('folds.index')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Back to Folds</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/folds/show.blade.php ENDPATH**/ ?>