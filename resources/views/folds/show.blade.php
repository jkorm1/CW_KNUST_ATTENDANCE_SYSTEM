@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Fold Details</h1>
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-4">
            <span class="font-semibold">Name:</span>
            <span>{{ $fold->name }}</span>
        </div>
        <div class="mb-4">
            <span class="font-semibold">Description:</span>
            <span>{{ $fold->description ?? 'No description' }}</span>
        </div>
        <div class="mb-4">
            <span class="font-semibold">Cell:</span>
            <span>{{ $fold->cell->name ?? 'N/A' }}</span>
        </div>
        <div class="mb-4">
            <span class="font-semibold">Status:</span>
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $fold->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $fold->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div class="flex justify-end">
            <a href="{{ route('folds.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Back to Folds</a>
        </div>
    </div>
</div>
@endsection 