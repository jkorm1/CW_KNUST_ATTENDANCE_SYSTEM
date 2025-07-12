@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-[#3a1d09]">Attendance Report for {{ $service->name }} ({{ $service->service_date }})</h1>
        <button onclick="window.print()" class="bg-[#f58502] text-white px-4 py-2 rounded shadow hover:bg-orange-600 font-bold">Print/Share</button>
    </div>
    <div class="mb-6">
        <form action="{{ route('attendance.report.show') }}" method="GET" class="inline">
            <input type="hidden" name="service_id" value="{{ $service->id }}">
            <select name="filter" onchange="this.form.submit()" class="border border-[#f58502] rounded px-2 py-1">
                <option value="all" @if($filter=='all') selected @endif>All Attendees</option>
                <option value="first_timers" @if($filter=='first_timers') selected @endif>First Timers Only</option>
            </select>
        </form>
    </div>
    <div class="bg-white rounded-lg shadow p-6 mb-8 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-[#f58502] text-[#3a1d09]">
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Date of Birth</th>
                    <th class="px-4 py-2">Contact</th>
                    <th class="px-4 py-2">Residence</th>
                    <th class="px-4 py-2">Purpose</th>
                    <th class="px-4 py-2">Inviter</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @if($filter == 'all')
                    @foreach($firstTimerRecords as $record)
                        <tr class="border-b bg-purple-50">
                            <td class="px-4 py-2">{{ $record->firstTimer->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $record->firstTimer->date_of_birth ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $record->firstTimer->phone ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $record->firstTimer->residence ?? '-' }}</td>
                            <td class="px-4 py-2">{{ ucfirst($record->firstTimer->purpose ?? '-') }}</td>
                            <td class="px-4 py-2">{{ $record->firstTimer->invited_by ? ($record->firstTimer->inviter->name ?? '-') : '-' }}</td>
                            <td class="px-4 py-2">{{ $record->present ? 'Present' : 'Absent' }}</td>
                        </tr>
                    @endforeach
                    @foreach($memberRecords as $record)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $record->member->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $record->member->date_of_birth ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $record->member->phone ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $record->member->residence ?? '-' }}</td>
                            <td class="px-4 py-2">-</td>
                            <td class="px-4 py-2">-</td>
                            <td class="px-4 py-2">{{ $record->present ? 'Present' : 'Absent' }}</td>
                        </tr>
                    @endforeach
                @else
                    @foreach($firstTimerRecords as $record)
                        <tr class="border-b bg-purple-50">
                            <td class="px-4 py-2">{{ $record->firstTimer->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $record->firstTimer->date_of_birth ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $record->firstTimer->phone ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $record->firstTimer->residence ?? '-' }}</td>
                            <td class="px-4 py-2">{{ ucfirst($record->firstTimer->purpose ?? '-') }}</td>
                            <td class="px-4 py-2">{{ $record->firstTimer->invited_by ? ($record->firstTimer->inviter->name ?? '-') : '-' }}</td>
                            <td class="px-4 py-2">{{ $record->present ? 'Present' : 'Absent' }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-[#3a1d09]">Summary</h2>
        <ul class="list-disc pl-6 text-[#3a1d09]">
            <li><strong>Total Present:</strong> {{ $summary['present'] }}</li>
            <li><strong>Total Absent:</strong> {{ $summary['absent'] }}</li>
            <li><strong>Number of First Timers:</strong> {{ $summary['first_timers'] }}</li>
            <li><strong>First Timers (Stay):</strong> {{ $summary['first_timers_stay'] }}</li>
            <li><strong>First Timers (Visit):</strong> {{ $summary['first_timers_visit'] }}</li>
        </ul>
    </div>
</div>
@endsection 