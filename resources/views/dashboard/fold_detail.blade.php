@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 max-w-4xl">
    <h1 class="text-2xl font-bold mb-4">Fold: {{ $fold->name }}</h1>
    <div class="mb-4">
        <strong>Cell:</strong> {{ $fold->cell->name ?? 'N/A' }}
    </div>
    <div class="bg-white rounded shadow p-4">
        <h2 class="text-lg font-semibold mb-2">Analytics (Coming Soon)</h2>
        <p>Attendance, first timers, conversions, productivity, and more will be shown here.</p>
    </div>
</div>
@endsection 