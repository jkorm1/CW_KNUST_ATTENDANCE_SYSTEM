@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 max-w-4xl">
    <h1 class="text-2xl font-bold mb-4">Cell: {{ $cell->name }}</h1>
    <div class="mb-4">
        <strong>Folds:</strong>
        <ul class="list-disc ml-6">
            @foreach($cell->folds as $fold)
                <li>{{ $fold->name }}</li>
            @endforeach
        </ul>
    </div>
    <div class="bg-white rounded shadow p-4">
        <h2 class="text-lg font-semibold mb-2">Analytics (Coming Soon)</h2>
        <p>Attendance, first timers, conversions, productivity, and more will be shown here.</p>
    </div>
</div>
@endsection 