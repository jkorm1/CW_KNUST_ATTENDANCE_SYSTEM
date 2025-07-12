@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 max-w-5xl">
    <h1 class="text-2xl font-bold mb-4">Folds Overview</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($folds as $fold)
        <div class="bg-white rounded shadow p-4">
            <h2 class="text-lg font-semibold mb-2">{{ $fold->name }}</h2>
            <a href="{{ route('dashboard.folds.detail', $fold->id) }}" class="text-blue-600 hover:underline text-sm mb-2 inline-block">View Analytics</a>
            <div class="text-sm text-gray-600 mb-2">Cell: {{ $fold->cell->name ?? 'N/A' }}</div>
        </div>
        @endforeach
    </div>
</div>
@endsection 