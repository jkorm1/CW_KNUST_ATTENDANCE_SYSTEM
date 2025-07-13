<?php $__env->startSection('content'); ?>
<div class="container mx-auto py-6 max-w-4xl">
    <h1 class="text-2xl font-bold mb-4">Add Member</h1>
    
    <?php if(session('error')): ?>
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('members.store')); ?>" method="POST" class="space-y-6 font-montserrat bg-white shadow-md rounded-lg p-6 border border-[#3a1d09]">
        <?php echo csrf_field(); ?>
        <h3 class="text-lg font-semibold mb-4 text-[#3a1d09]">Member Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-bold text-[#3a1d09] mb-2">Full Name *</label>
                <input type="text" name="name" id="name" class="w-full px-3 py-2 border border-[#3a1d09] rounded-md focus:outline-none focus:ring-2 focus:ring-[#f58502] font-montserrat" value="<?php echo e(old('name')); ?>" required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div>
                <label class="block font-medium">Phone</label>
                <input type="text" name="phone" class="w-full border rounded px-3 py-2" value="<?php echo e(old('phone')); ?>">
            </div>
            <div>
                <label class="block font-medium">Gender</label>
                <select name="gender" class="w-full border rounded px-3 py-2">
                    <option value="">Select Gender</option>
                    <option value="Male" <?php if(old('gender') == 'Male'): ?> selected <?php endif; ?>>Male</option>
                    <option value="Female" <?php if(old('gender') == 'Female'): ?> selected <?php endif; ?>>Female</option>
                </select>
            </div>
            <div>
                <label class="block font-medium">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2" required>
                    <option value="member" <?php if(old('status') == 'member'): ?> selected <?php endif; ?>>Member</option>
                </select>
            </div>
            <div>
                <label class="block font-medium">Cell</label>
                <select name="cell_id" id="cell_id" class="w-full border rounded px-3 py-2">
                    <option value="">Select Cell</option>
                    <?php $__currentLoopData = $cells; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cell->id); ?>" <?php if(old('cell_id') == $cell->id): ?> selected <?php endif; ?>><?php echo e($cell->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="fold_id" class="block text-sm font-medium text-gray-700">Fold</label>
                <select name="fold_id" id="fold_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    <option value="">Select a cell first</option>
                </select>
            </div>
            <div>
                <label class="block font-medium">First Visit Date</label>
                <input type="date" name="first_visit_date" class="w-full border rounded px-3 py-2" value="<?php echo e(old('first_visit_date')); ?>">
            </div>
        </div>
        
        <div>
            <label class="block font-medium">Notes</label>
            <textarea name="notes" class="w-full border rounded px-3 py-2"><?php echo e(old('notes')); ?></textarea>
        </div>
        
        <?php if(auth()->user()->isAdmin()): ?>
        <div class="border-t pt-6 mt-6">
            <h3 class="text-lg font-bold mb-4">Leadership Assignment</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-medium">Cell Leader Of</label>
                    <select name="cell_leader_of" id="cell_leader_of" class="w-full border rounded px-3 py-2">
                        <option value="">None</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium">Assistant Cell Leader Of</label>
                    <select name="assistant_cell_leader_of" id="assistant_cell_leader_of" class="w-full border rounded px-3 py-2">
                        <option value="">None</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium">Fold Leader Of</label>
                    <select name="fold_leader_of" id="fold_leader_of" class="w-full border rounded px-3 py-2">
                        <option value="">None</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium">Assistant Fold Leader Of</label>
                    <select name="assistant_fold_leader_of" id="assistant_fold_leader_of" class="w-full border rounded px-3 py-2">
                        <option value="">None</option>
                    </select>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="flex justify-end">
            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('Add Member')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
        </div>
    </form>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cellSelect = document.getElementById('cell_id');
        const foldSelect = document.getElementById('fold_id');
        // Leadership dropdowns
        const cellLeaderSelect = document.getElementById('cell_leader_of');
        const assistantCellLeaderSelect = document.getElementById('assistant_cell_leader_of');
        const foldDiv = foldSelect.closest('.mb-4');

        function checkLeaderAssignment() {
            if ((cellLeaderSelect && cellLeaderSelect.value) || (assistantCellLeaderSelect && assistantCellLeaderSelect.value)) {
                foldSelect.value = '';
                foldSelect.disabled = true;
                if (foldDiv) foldDiv.style.display = 'none';
            } else {
                foldSelect.disabled = false;
                if (foldDiv) foldDiv.style.display = '';
            }
        }

        if (cellLeaderSelect) cellLeaderSelect.addEventListener('change', checkLeaderAssignment);
        if (assistantCellLeaderSelect) assistantCellLeaderSelect.addEventListener('change', checkLeaderAssignment);
        checkLeaderAssignment();

        cellSelect.addEventListener('change', function () {
            const cellId = this.value;
            const cellName = this.options[this.selectedIndex].text;

            // Reset and populate cell leadership dropdowns
            cellLeaderSelect.innerHTML = '<option value="">None</option>';
            assistantCellLeaderSelect.innerHTML = '<option value="">None</option>';
            if (cellId) {
                const option = new Option(cellName, cellId);
                cellLeaderSelect.add(option.cloneNode(true));
                assistantCellLeaderSelect.add(option.cloneNode(true));
            }

            // Fetch and populate folds
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
                        foldSelect.appendChild(option);
                    });
                    // Trigger change to update fold leadership dropdowns
                    foldSelect.dispatchEvent(new Event('change'));
                })
                .catch(error => {
                    console.error('Error loading folds:', error);
                    foldSelect.innerHTML = '<option value="">Could not load folds</option>';
                });
        });

        foldSelect.addEventListener('change', function() {
            const foldId = this.value;
            const foldName = this.options[this.selectedIndex].text;

            // Reset and populate fold leadership dropdowns
            foldLeaderSelect.innerHTML = '<option value="">None</option>';
            assistantFoldLeaderSelect.innerHTML = '<option value="">None</option>';

            if (foldId) {
                const option = new Option(foldName, foldId);
                foldLeaderSelect.add(option.cloneNode(true));
                assistantFoldLeaderSelect.add(option.cloneNode(true));
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/members/create.blade.php ENDPATH**/ ?>