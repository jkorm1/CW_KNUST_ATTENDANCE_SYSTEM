@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Add Fold</h1>
    <form action="{{ route('folds.store') }}" method="POST" class="space-y-6 font-montserrat bg-white shadow-md rounded-lg p-6 border border-[#3a1d09]">
        @csrf
        <h3 class="text-lg font-semibold mb-4 text-[#3a1d09]">Fold Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-bold text-[#3a1d09] mb-2">Fold Name *</label>
                <input type="text" name="name" id="name" class="w-full px-3 py-2 border border-[#3a1d09] rounded-md focus:outline-none focus:ring-2 focus:ring-[#f58502] font-montserrat" value="{{ old('name') }}" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block font-medium">Description</label>
                <textarea name="description" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block font-medium">Cell</label>
                <select name="cell_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Cell</option>
                    @foreach($cells as $cell)
                        <option value="{{ $cell->id }}" @if(old('cell_id') == $cell->id) selected @endif>{{ $cell->name }}</option>
                    @endforeach
                </select>
                @error('cell_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" @if(old('is_active', true)) checked @endif class="mr-2">
                    <span class="font-medium">Active</span>
                </label>
            </div>
        </div>
        <div class="flex justify-end">
            <x-primary-button>{{ __('Add Fold') }}</x-primary-button>
        </div>
    </form>
</div>
@endsection 