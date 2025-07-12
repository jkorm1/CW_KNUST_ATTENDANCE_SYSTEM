@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-[#3a1d09]">Attendance Report</h1>
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif
    <form action="{{ route('attendance.report.show') }}" method="GET" class="bg-white p-6 rounded-lg shadow-md max-w-xl mx-auto">
        <div class="mb-4">
            <label for="service_id" class="block text-[#3a1d09] font-semibold mb-2">Select Service</label>
            <select name="service_id" id="service_id" class="w-full border border-[#f58502] rounded px-3 py-2" required>
                <option value="">-- Choose a Service --</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}">
                        @if(!empty($service->is_auto) && $service->is_auto)
                            {{ $service->service_date }}
                        @else
                            {{ $service->name }} ({{ $service->service_date }})
                        @endif
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-[#3a1d09] font-semibold mb-2">Report Type</label>
            <select name="filter" class="w-full border border-[#f58502] rounded px-3 py-2">
                <option value="all">All Attendees</option>
                <option value="first_timers">First Timers Only</option>
            </select>
        </div>
        <button type="submit" class="bg-[#f58502] text-white px-6 py-2 rounded shadow hover:bg-orange-600 font-bold">View Report</button>
    </form>
</div>
@endsection 