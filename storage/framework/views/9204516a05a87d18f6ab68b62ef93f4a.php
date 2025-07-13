<?php $__env->startSection('content'); ?>
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-[#3a1d09]">Attendance Report for <?php echo e($service->name); ?> (<?php echo e($service->service_date); ?>)</h1>
        <button onclick="window.print()" class="bg-[#f58502] text-white px-4 py-2 rounded shadow hover:bg-orange-600 font-bold">Print/Share</button>
    </div>
    <div class="mb-6">
        <form action="<?php echo e(route('attendance.report.show')); ?>" method="GET" class="inline">
            <input type="hidden" name="service_id" value="<?php echo e($service->id); ?>">
            <select name="filter" onchange="this.form.submit()" class="border border-[#f58502] rounded px-2 py-1">
                <option value="all" <?php if($filter=='all'): ?> selected <?php endif; ?>>All Attendees</option>
                <option value="first_timers" <?php if($filter=='first_timers'): ?> selected <?php endif; ?>>First Timers Only</option>
            </select>
        </form>
    </div>
    <div class="bg-white rounded-lg shadow p-6 mb-8 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-[#f58502] text-[#3a1d09]">
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Date of Birth</th>
                    <th class="px-4 py-2">Contact</th>
                    <th class="px-4 py-2">Residence</th>
                    <th class="px-4 py-2">Purpose</th>
                    <th class="px-4 py-2">Inviter</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if($filter == 'all'): ?>
                    <?php $__currentLoopData = $firstTimerRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b bg-purple-50">
                            <td class="px-4 py-2"><?php echo e($record->firstTimer->name ?? '-'); ?></td>
                            <td class="px-4 py-2"><?php echo e($record->firstTimer->date_of_birth ?? '-'); ?></td>
                            <td class="px-4 py-2"><?php echo e($record->firstTimer->phone ?? '-'); ?></td>
                            <td class="px-4 py-2"><?php echo e($record->firstTimer->residence ?? '-'); ?></td>
                            <td class="px-4 py-2"><?php echo e(ucfirst($record->firstTimer->purpose ?? '-')); ?></td>
                            <td class="px-4 py-2"><?php echo e($record->firstTimer->invited_by ? ($record->firstTimer->inviter->name ?? '-') : '-'); ?></td>
                            <td class="px-4 py-2"><?php echo e($record->present ? 'Present' : 'Absent'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $memberRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?php echo e($record->member->name ?? '-'); ?></td>
                            <td class="px-4 py-2"><?php echo e($record->member->date_of_birth ?? '-'); ?></td>
                            <td class="px-4 py-2"><?php echo e($record->member->phone ?? '-'); ?></td>
                            <td class="px-4 py-2"><?php echo e($record->member->residence ?? '-'); ?></td>
                            <td class="px-4 py-2">-</td>
                            <td class="px-4 py-2">-</td>
                            <td class="px-4 py-2"><?php echo e($record->present ? 'Present' : 'Absent'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <?php $__currentLoopData = $firstTimerRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b bg-purple-50">
                            <td class="px-4 py-2"><?php echo e($record->firstTimer->name ?? '-'); ?></td>
                            <td class="px-4 py-2"><?php echo e($record->firstTimer->date_of_birth ?? '-'); ?></td>
                            <td class="px-4 py-2"><?php echo e($record->firstTimer->phone ?? '-'); ?></td>
                            <td class="px-4 py-2"><?php echo e($record->firstTimer->residence ?? '-'); ?></td>
                            <td class="px-4 py-2"><?php echo e(ucfirst($record->firstTimer->purpose ?? '-')); ?></td>
                            <td class="px-4 py-2"><?php echo e($record->firstTimer->invited_by ? ($record->firstTimer->inviter->name ?? '-') : '-'); ?></td>
                            <td class="px-4 py-2"><?php echo e($record->present ? 'Present' : 'Absent'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-[#3a1d09]">Summary</h2>
        <ul class="list-disc pl-6 text-[#3a1d09]">
            <li><strong>Total Present:</strong> <?php echo e($summary['present']); ?></li>
            <li><strong>Total Absent:</strong> <?php echo e($summary['absent']); ?></li>
            <li><strong>Number of First Timers:</strong> <?php echo e($summary['first_timers']); ?></li>
            <li><strong>First Timers (Stay):</strong> <?php echo e($summary['first_timers_stay']); ?></li>
            <li><strong>First Timers (Visit):</strong> <?php echo e($summary['first_timers_visit']); ?></li>
        </ul>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/attendance/report/show.blade.php ENDPATH**/ ?>