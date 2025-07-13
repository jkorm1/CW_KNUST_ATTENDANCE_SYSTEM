<?php $__env->startSection('content'); ?>
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Attendance for <?php echo e($service->name); ?></h1>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('attendance.export', $service->id)); ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Export CSV
            </a>
            <a href="<?php echo e(route('services.index')); ?>" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Back to Services
            </a>
        </div>
    </div>

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

    <!-- Statistics Card -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6 font-montserrat">
        <div class="bg-white p-4 rounded-lg shadow border border-[#3a1d09]">
            <div class="text-sm text-[#3a1d09]">Total Members</div>
            <div class="text-2xl font-bold text-gray-800"><?php echo e($members->count()); ?></div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border border-[#3a1d09]">
            <div class="text-sm text-[#3a1d09]">First Timers</div>
            <div class="text-2xl font-bold text-purple-600"><?php echo e($firstTimers->count()); ?></div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border border-[#3a1d09]">
            <div class="text-sm text-[#3a1d09]">Present</div>
            <div class="text-2xl font-bold text-green-600"><?php echo e($memberAttendance->filter(fn($present) => $present)->count() + $firstTimerAttendance->filter(fn($present) => $present)->count()); ?></div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border border-[#3a1d09]">
            <div class="text-sm text-[#3a1d09]">Absent</div>
            <div class="text-2xl font-bold text-red-600"><?php echo e($memberAttendance->filter(fn($present) => !$present)->count() + $firstTimerAttendance->filter(fn($present) => !$present)->count()); ?></div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border border-[#3a1d09]">
            <div class="text-sm text-[#3a1d09]">Unmarked</div>
            <div class="text-2xl font-bold text-gray-500"><?php echo e($members->count() - $memberAttendance->count()); ?></div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-2 mb-6">
        <form action="<?php echo e(route('attendance.finalize', $service->id)); ?>" method="POST" class="inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="bg-orange-500 text-gray-900 px-4 py-2 rounded border border-orange-700 shadow-sm hover:bg-orange-600">Finalize Attendance</button>
        </form>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden font-montserrat">
        <div class="overflow-x-auto">
            <table class="min-w-full rounded-lg overflow-hidden">
                <thead class="bg-[#f58502]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#3a1d09] uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#3a1d09] uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#3a1d09] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#3a1d09] uppercase tracking-wider">Attendance</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#3a1d09] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-orange-100">
                    <!-- Members Section -->
                    <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-orange-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($member->name); ?></div>
                            <?php if($member->phone): ?>
                                <div class="text-sm text-gray-500"><?php echo e($member->phone); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Member
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo e($member->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e(ucfirst($member->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if(isset($memberAttendance[$member->id])): ?>
                                <?php if($memberAttendance[$member->id]): ?>
                                    <span class="text-green-600 font-bold">✓ Present</span>
                                <?php else: ?>
                                    <span class="text-red-600 font-bold">✗ Absent</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-gray-500">- Unmarked</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <?php if(!isset($memberAttendance[$member->id])): ?>
                                <?php if(true): ?> <!-- Always show both buttons for re-marking -->
                                    <div class="flex space-x-2">
                                        <form action="<?php echo e(route('attendance.mark', ['service' => $service->id, 'member' => $member->id])); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="present" value="1">
                                            <button type="submit"
                                                class="px-2 py-1 text-xs font-semibold text-white bg-green-500 border border-green-700 rounded shadow-sm hover:bg-green-600"
                                                onclick="this.disabled=true;this.innerHTML='<span class=\'animate-spin mr-1\'>⏳</span>Saving...';">
                                                Present
                                            </button>
                                        </form>
                                        <form action="<?php echo e(route('attendance.mark', ['service' => $service->id, 'member' => $member->id])); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="present" value="0">
                                            <button type="submit"
                                                class="px-2 py-1 text-xs font-semibold text-white bg-red-500 border border-red-700 rounded shadow-sm hover:bg-red-600"
                                                onclick="this.disabled=true;this.innerHTML='<span class=\'animate-spin mr-1\'>⏳</span>Saving...';">
                                                Absent
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="flex space-x-2">
                                    <form action="<?php echo e(route('attendance.mark', ['service' => $service->id, 'member' => $member->id])); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="present" value="1">
                                        <button type="submit" class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-200 border border-gray-400 rounded shadow-sm" disabled>Present</button>
                                    </form>
                                    <form action="<?php echo e(route('attendance.mark', ['service' => $service->id, 'member' => $member->id])); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="present" value="0">
                                        <button type="submit" class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-200 border border-gray-400 rounded shadow-sm" disabled>Absent</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- First Timers Section -->
                    <?php $__currentLoopData = $firstTimers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $firstTimer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-orange-50 bg-purple-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($firstTimer->name); ?></div>
                            <?php if($firstTimer->phone): ?>
                                <div class="text-sm text-gray-500"><?php echo e($firstTimer->phone); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($firstTimer->purpose === 'stay'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Member
                                </span>
                            <?php else: ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    First Timer
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo e($firstTimer->purpose === 'stay' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                <?php echo e(ucfirst($firstTimer->purpose ?? 'visit')); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if(isset($firstTimerAttendance[$firstTimer->id])): ?>
                                <?php if($firstTimerAttendance[$firstTimer->id]): ?>
                                    <span class="text-green-600 font-bold">✓ Present (Auto)</span>
                                <?php else: ?>
                                    <span class="text-red-600 font-bold">✗ Absent</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-green-600 font-bold">✓ Present (Auto)</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <form action="<?php echo e(route('attendance.markFirstTimer', ['service' => $service->id, 'firstTimer' => $firstTimer->id])); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="present" value="1">
                                    <button type="submit" class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-200 border border-gray-400 rounded shadow-sm" disabled>Present</button>
                                </form>
                                <form action="<?php echo e(route('attendance.markFirstTimer', ['service' => $service->id, 'firstTimer' => $firstTimer->id])); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="present" value="0">
                                    <button type="submit" class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-200 border border-gray-400 rounded shadow-sm" disabled>Absent</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Find all attendance forms
    document.querySelectorAll('form[action*="attendance.mark"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            // Disable all buttons in this row
            const row = form.closest('tr');
            row.querySelectorAll('button[type="submit"]').forEach(btn => {
                btn.disabled = true;
                btn.innerHTML = '<span class="animate-spin mr-1">⏳</span>Saving...';
            });
        });
    });
});
</script> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/attendance/index.blade.php ENDPATH**/ ?>