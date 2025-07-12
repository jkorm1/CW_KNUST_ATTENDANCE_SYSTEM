@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Attendance Record Details</h1>
            <div class="flex space-x-2">
                <a href="{{ route('attendance.edit', [$attendance->service_id, $attendance]) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Edit
                </a>
                <a href="{{ route('attendance.index', $attendance->service_id) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Back to Attendance
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Service Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Service Name</label>
                            <p class="text-gray-800">{{ $attendance->service->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Service Date</label>
                            <p class="text-gray-800">{{ $attendance->service->service_date ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Service Type</label>
                            <p class="text-gray-800">{{ $attendance->service->type ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Member Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Member Name</label>
                            <p class="text-gray-800">{{ $attendance->member->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Phone</label>
                            <p class="text-gray-800">{{ $attendance->member->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Status</label>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ ($attendance->member->status ?? '') === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($attendance->member->status ?? 'N/A') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Attendance Status</h3>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        @if($attendance->present)
                            <span class="text-green-600 text-2xl mr-2">✓</span>
                            <span class="text-green-600 font-bold text-lg">Present</span>
                        @else
                            <span class="text-red-600 text-2xl mr-2">✗</span>
                            <span class="text-red-600 font-bold text-lg">Absent</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Record Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Created At</label>
                        <p class="text-gray-800">{{ $attendance->created_at ? $attendance->created_at->format('F j, Y g:i A') : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Last Updated</label>
                        <p class="text-gray-800">{{ $attendance->updated_at ? $attendance->updated_at->format('F j, Y g:i A') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex justify-between">
                    <form action="{{ route('attendance.destroy', [$attendance->service_id, $attendance]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this attendance record?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Delete Record
                        </button>
                    </form>
                    <a href="{{ route('attendance.index', $attendance->service_id) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 