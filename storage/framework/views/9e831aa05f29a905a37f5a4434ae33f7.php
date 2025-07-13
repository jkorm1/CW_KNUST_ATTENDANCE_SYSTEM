<?php $__env->startSection('content'); ?>
<div class="container mx-auto py-6 max-w-4xl">
    <h1 class="text-2xl font-bold mb-4">Edit Member</h1>

    <?php if(session('error')): ?>
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('members.update', $member)); ?>" method="POST" class="space-y-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-medium">Name</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" value="<?php echo e(old('name', $member->name)); ?>" required>
            </div>
            <div>
                <label class="block font-medium">Phone</label>
                <input type="text" name="phone" class="w-full border rounded px-3 py-2" value="<?php echo e(old('phone', $member->phone)); ?>">
            </div>
            <div>
                <label class="block font-medium">Gender</label>
                <select name="gender" class="w-full border rounded px-3 py-2">
                    <option value="">Select Gender</option>
                    <option value="Male" <?php if(old('gender', $member->gender) == 'Male'): ?> selected <?php endif; ?>>Male</option>
                    <option value="Female" <?php if(old('gender', $member->gender) == 'Female'): ?> selected <?php endif; ?>>Female</option>
                </select>
            </div>
            <div>
                <label class="block font-medium">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2" required>
                    <option value="member" <?php if(old('status', $member->status) == 'member'): ?> selected <?php endif; ?>>Member</option>
                    <option value="first_timer" <?php if(old('status', $member->status) == 'first_timer'): ?> selected <?php endif; ?>>First Timer</option>
                </select>
            </div>
            <div>
                <label class="block font-medium">Cell</label>
                <select name="cell_id" id="cell_id" class="w-full border rounded px-3 py-2">
                    <option value="">Select Cell</option>
                    <?php $__currentLoopData = $cells; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cell->id); ?>" <?php if(old('cell_id', $member->cell_id) == $cell->id): ?> selected <?php endif; ?>><?php echo e($cell->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label for="fold_id" class="block text-sm font-medium text-gray-700">Fold</label>
                <select name="fold_id" id="fold_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    <option value="">Select a cell first</option>
                </select>
            </div>
            <div>
                <label class="block font-medium">First Visit Date</label>
                <input type="date" name="first_visit_date" class="w-full border rounded px-3 py-2" value="<?php echo e(old('first_visit_date', $member->first_visit_date)); ?>">
            </div>
        </div>

        <div>
            <label class="block font-medium">Notes</label>
            <textarea name="notes" class="w-full border rounded px-3 py-2"><?php echo e(old('notes', $member->notes)); ?></textarea>
        </div>

        <?php if(auth()->user()->isAdmin()): ?>
        <div class="border-t pt-6 mt-6">
            <h3 class="text-lg font-bold mb-4">Leadership Assignment</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-medium">Cell Leader Of</label>
                    <select name="cell_leader_of" class="w-full border rounded px-3 py-2">
                        <option value="">None</option>
                        <?php if($member->cell): ?>
                            <option value="<?php echo e($member->cell->id); ?>" <?php if(old('cell_leader_of', optional($member->ledCell)->id) == $member->cell->id): ?> selected <?php endif; ?>><?php echo e($member->cell->name); ?></option>
                        <?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="block font-medium">Assistant Cell Leader Of</label>
                    <select name="assistant_cell_leader_of" class="w-full border rounded px-3 py-2">
                        <option value="">None</option>
                        <?php if($member->cell): ?>
                            <option value="<?php echo e($member->cell->id); ?>" <?php if(old('assistant_cell_leader_of', optional($member->assistantLedCell)->id) == $member->cell->id): ?> selected <?php endif; ?>><?php echo e($member->cell->name); ?></option>
                        <?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="block font-medium">Fold Leader Of</label>
                    <select name="fold_leader_of" class="w-full border rounded px-3 py-2">
                        <option value="">None</option>
                        <?php if($member->fold): ?>
                            <option value="<?php echo e($member->fold->id); ?>" <?php if(old('fold_leader_of', optional($member->ledFold)->id) == $member->fold->id): ?> selected <?php endif; ?>><?php echo e($member->fold->name); ?></option>
                        <?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="block font-medium">Assistant Fold Leader Of</label>
                    <select name="assistant_fold_leader_of" class="w-full border rounded px-3 py-2">
                        <option value="">None</option>
                        <?php if($member->fold): ?>
                            <option value="<?php echo e($member->fold->id); ?>" <?php if(old('assistant_fold_leader_of', optional($member->assistantLedFold)->id) == $member->fold->id): ?> selected <?php endif; ?>><?php echo e($member->fold->name); ?></option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="flex justify-between">
            <a href="<?php echo e(route('members.index')); ?>" class="text-gray-600">Cancel</a>
            <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">Update Member</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cellSelect = document.getElementById('cell_id');
        const foldSelect = document.getElementById('fold_id');
        const initialCellId = '<?php echo e(old('cell_id', $member->cell_id)); ?>';
        const initialFoldId = '<?php echo e(old('fold_id', $member->fold_id)); ?>';

        function fetchFolds(cellId, selectedFoldId = null) {
            foldSelect.innerHTML = '<option value="">Loading...</option>';

            if (!cellId) {
                foldSelect.innerHTML = '<option value="">Select a cell first</option>';
                return;
            }

            fetch(`/api/cells/${cellId}/folds`)
                .then(response => response.json())
                .then(data => {
                    foldSelect.innerHTML = '<option value="">Select a fold</option>';
                    data.forEach(fold => {
                        const option = document.createElement('option');
                        option.value = fold.id;
                        option.textContent = fold.name;
                        if (selectedFoldId && fold.id == selectedFoldId) {
                            option.selected = true;
                        }
                        foldSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading folds:', error);
                    foldSelect.innerHTML = '<option value="">Could not load folds</option>';
                });
        }

        cellSelect.addEventListener('change', function () {
            fetchFolds(this.value);
        });

        // Load initial folds if a cell is already selected
        if (initialCellId) {
            fetchFolds(initialCellId, initialFoldId);
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/members/edit.blade.php ENDPATH**/ ?>