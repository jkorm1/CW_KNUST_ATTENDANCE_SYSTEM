@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Edit Fold</h1>
    <form action="{{ route('folds.update', $fold) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block font-medium">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name', $fold->name) }}" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block font-medium">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2">{{ old('description', $fold->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block font-medium">Cell</label>
            <select name="cell_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Cell</option>
                @foreach($cells as $cell)
                    <option value="{{ $cell->id }}" @if(old('cell_id', $fold->cell_id) == $cell->id) selected @endif>{{ $cell->name }}</option>
                @endforeach
            </select>
            @error('cell_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" @if(old('is_active', $fold->is_active)) checked @endif class="mr-2">
                <span class="font-medium">Active</span>
            </label>
        </div>
        <div class="flex justify-between">
            <a href="{{ route('folds.index') }}" class="text-gray-600">Cancel</a>
            <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">Update Fold</button>
        </div>
    </form>
</div>
@endsection 