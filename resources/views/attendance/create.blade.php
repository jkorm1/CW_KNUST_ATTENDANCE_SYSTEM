@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Add Attendance Record</h1>
            <a href="{{ route('services.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Back to Services
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
            <form action="{{ route('attendance.store', $service->id) }}" method="POST" class="space-y-6 font-montserrat bg-white shadow-md rounded-lg p-6 border border-[#3a1d09]">
                @csrf
                <h3 class="text-lg font-semibold mb-4 text-[#3a1d09]">Attendance Record</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="service_id" class="block text-sm font-bold text-[#3a1d09] mb-2">Service *</label>
                        <select name="service_id" id="service_id" class="w-full px-3 py-2 border border-[#3a1d09] rounded-md focus:outline-none focus:ring-2 focus:ring-[#f58502] font-montserrat" required>
                            <option value="">Select a service</option>
                            @foreach($services as $serviceOption)
                                <option value="{{ $serviceOption->id }}" {{ $service->id == $serviceOption->id ? 'selected' : '' }}>
                                    {{ $serviceOption->name }} - {{ $serviceOption->service_date }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="member_id" class="block text-sm font-bold text-[#3a1d09] mb-2">Member *</label>
                        <select name="member_id" id="member_id" class="w-full px-3 py-2 border border-[#3a1d09] rounded-md focus:outline-none focus:ring-2 focus:ring-[#f58502] font-montserrat" required>
                            <option value="">Select a member</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">
                                    {{ $member->name }} ({{ ucfirst($member->status) }})
                                </option>
                            @endforeach
                        </select>
                        @error('member_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Attendance Status</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="present" value="1" class="mr-2" required>
                            <span class="text-green-600 font-medium">Present</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="present" value="0" class="mr-2" required>
                            <span class="text-red-600 font-medium">Absent</span>
                        </label>
                    </div>
                    @error('present')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <x-primary-button>{{ __('Add Attendance Record') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 