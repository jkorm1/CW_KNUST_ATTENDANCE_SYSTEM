@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Edit Attendance Record</h1>
            <a href="{{ route('attendance.index', $attendance->service_id) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Back to Attendance
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('attendance.update', [$attendance->service_id, $attendance]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">Service</label>
                    <select name="service_id" id="service_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select a service</option>
                        @foreach($services as $serviceOption)
                            <option value="{{ $serviceOption->id }}" {{ $attendance->service_id == $serviceOption->id ? 'selected' : '' }}>
                                {{ $serviceOption->name }} - {{ $serviceOption->service_date }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="member_id" class="block text-sm font-medium text-gray-700 mb-2">Member</label>
                    <select name="member_id" id="member_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select a member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ $attendance->member_id == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ ucfirst($member->status) }})
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Attendance Status</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="present" value="1" class="mr-2" {{ $attendance->present ? 'checked' : '' }} required>
                            <span class="text-green-600 font-medium">Present</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="present" value="0" class="mr-2" {{ !$attendance->present ? 'checked' : '' }} required>
                            <span class="text-red-600 font-medium">Absent</span>
                        </label>
                    </div>
                    @error('present')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('attendance.index', $attendance->service_id) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded border border-blue-700 shadow-sm hover:bg-blue-600">Update Attendance Record</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 