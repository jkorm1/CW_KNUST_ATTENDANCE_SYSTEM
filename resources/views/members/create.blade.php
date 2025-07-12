@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 max-w-4xl">
    <h1 class="text-2xl font-bold mb-4">Add Member</h1>
    
    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('members.store') }}" method="POST" class="space-y-6 font-montserrat bg-white shadow-md rounded-lg p-6 border border-[#3a1d09]">
        @csrf
        <h3 class="text-lg font-semibold mb-4 text-[#3a1d09]">Member Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-bold text-[#3a1d09] mb-2">Full Name *</label>
                <input type="text" name="name" id="name" class="w-full px-3 py-2 border border-[#3a1d09] rounded-md focus:outline-none focus:ring-2 focus:ring-[#f58502] font-montserrat" value="{{ old('name') }}" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block font-medium">Phone</label>
                <input type="text" name="phone" class="w-full border rounded px-3 py-2" value="{{ old('phone') }}">
            </div>
            <div>
                <label class="block font-medium">Gender</label>
                <select name="gender" class="w-full border rounded px-3 py-2">
                    <option value="">Select Gender</option>
                    <option value="Male" @if(old('gender') == 'Male') selected @endif>Male</option>
                    <option value="Female" @if(old('gender') == 'Female') selected @endif>Female</option>
                </select>
            </div>
            <div>
                <label class="block font-medium">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2" required>
                    <option value="member" @if(old('status') == 'member') selected @endif>Member</option>
                </select>
            </div>
            <div>
                <label class="block font-medium">Cell</label>
                <select name="cell_id" id="cell_id" class="w-full border rounded px-3 py-2">
                    <option value="">Select Cell</option>
                    @foreach($cells as $cell)
                        <option value="{{ $cell->id }}" @if(old('cell_id') == $cell->id) selected @endif>{{ $cell->name }}</option>
                    @endforeach
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
                <input type="date" name="first_visit_date" class="w-full border rounded px-3 py-2" value="{{ old('first_visit_date') }}">
            </div>
        </div>
        
        <div>
            <label class="block font-medium">Notes</label>
            <textarea name="notes" class="w-full border rounded px-3 py-2">{{ old('notes') }}</textarea>
        </div>
        
        @if(auth()->user()->isAdmin())
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
        @endif

        <div class="flex justify-end">
            <x-primary-button>{{ __('Add Member') }}</x-primary-button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
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
@endpush