@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gradient mb-2">
                <i class="fas fa-user-plus mr-3"></i>First Timers & Follow-Up Department
            </h1>
            <p class="text-[#3a1d09] font-medium">Welcome and track new visitors to Charisword Gospel Ministry</p>
        </div>
        <a href="{{ route('first_timers.create') }}" class="btn-primary">
            <i class="fas fa-user-plus mr-2"></i>Register First Timer
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="stats-card">
            <div class="flex items-center justify-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
            </div>
            <div class="stats-number">{{ $total }}</div>
            <div class="stats-label">Grand Total</div>
        </div>

        <div class="stats-card">
            <div class="flex items-center justify-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-home text-white text-xl"></i>
                </div>
            </div>
            <div class="stats-number">{{ $stay }}</div>
            <div class="stats-label">Total Stay</div>
        </div>

        <div class="stats-card">
            <div class="flex items-center justify-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-check text-white text-xl"></i>
                </div>
            </div>
            <div class="stats-number">{{ $visit }}</div>
            <div class="stats-label">Total Visit</div>
        </div>

        <div class="stats-card">
            <div class="flex items-center justify-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
            </div>
            <div class="stats-number">{{ $firstTimers->count() }}</div>
            <div class="stats-label">Current View</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="glass-card p-6">
        <h3 class="text-lg font-bold text-[#3a1d09] mb-4">
            <i class="fas fa-filter mr-2"></i>Filter Records
        </h3>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="form-group">
                <label for="service_id" class="form-label">Filter by Service</label>
                <select name="service_id" id="service_id" class="form-input">
                    <option value="">-- All Services --</option>
                    @foreach($allServices as $service)
                        <option value="{{ $service->id }}" @if(request('service_id') == $service->id) selected @endif>
                            @if(!empty($service->is_auto) && $service->is_auto)
                                @php
                                    preg_match('/(\\d{4}-\\d{2}-\\d{2})/', $service->service_date, $matches);
                                    $date = $matches[1] ?? $service->service_date;
                                @endphp
                                Auto Service ({{ \Carbon\Carbon::parse($date)->format('D, d M Y') }})
                            @else
                                {{ $service->name }} ({{ \Carbon\Carbon::parse($service->service_date)->format('D, d M Y') }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="date" class="form-label">Or by Date</label>
                <input type="date" name="date" id="date" value="{{ request('date') }}" class="form-input">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('first_timers.index') }}" class="btn-secondary">
                    <i class="fas fa-refresh mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Current Filter Info -->
    @if($serviceInfo || $date)
        <div class="glass-card p-6 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-bold text-[#3a1d09] mb-2">Current Filter</h4>
                    @if($serviceInfo)
                        <p class="text-[#f58502] font-semibold">
                            <i class="fas fa-church mr-2"></i>
                            {{ $serviceInfo['name'] }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($serviceInfo['service_date'])->format('D, d M Y') }}
                        </p>
                    @elseif($date)
                        <p class="text-[#f58502] font-semibold">
                            <i class="fas fa-calendar mr-2"></i>
                            {{ \Carbon\Carbon::parse($date)->format('D, d M Y') }}
                        </p>
                    @endif
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-[#f58502]">{{ $firstTimers->count() }}</div>
                    <div class="text-sm text-gray-600">Records Found</div>
                </div>
            </div>
        </div>
    @endif

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- First Timers Table -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-modern w-full">
                <thead>
                    <tr>
                        <th class="text-left">
                            <i class="fas fa-user mr-2"></i>Name
                        </th>
                        <th class="text-left">
                            <i class="fas fa-birthday-cake mr-2"></i>Date of Birth
                        </th>
                        <th class="text-left">
                            <i class="fas fa-phone mr-2"></i>Contact
                        </th>
                        <th class="text-left">
                            <i class="fas fa-map-marker-alt mr-2"></i>Residence
                        </th>
                        <th class="text-left">
                            <i class="fas fa-flag mr-2"></i>Purpose
                        </th>
                        <th class="text-left">
                            <i class="fas fa-church mr-2"></i>Service
                        </th>
                        <th class="text-left">
                            <i class="fas fa-user-friends mr-2"></i>Invited By
                        </th>
                        <th class="text-left">
                            <i class="fas fa-home mr-2"></i>Cell
                        </th>
                        <th class="text-left">
                            <i class="fas fa-layer-group mr-2"></i>Fold
                        </th>
                        <th class="text-left">
                            <i class="fas fa-calendar mr-2"></i>First Visit
                        </th>
                        <th class="text-left">
                            <i class="fas fa-cogs mr-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($firstTimers as $ft)
                    <tr class="hover:bg-orange-50 transition-colors duration-200">
                        <td class="font-semibold text-[#3a1d09]">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-[#f58502] to-[#ff9a2e] rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                    {{ strtoupper(substr($ft->name, 0, 1)) }}
                                </div>
                                {{ $ft->name }}
                            </div>
                        </td>
                        <td>
                            <span class="text-sm text-gray-600">
                                {{ $ft->date_of_birth ? \Carbon\Carbon::parse($ft->date_of_birth)->format('M j, Y') : 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <a href="tel:{{ $ft->phone }}" class="text-[#f58502] hover:text-[#3a1d09] transition-colors">
                                <i class="fas fa-phone mr-1"></i>{{ $ft->phone }}
                            </a>
                        </td>
                        <td>
                            <span class="text-sm text-gray-600">{{ $ft->residence ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $ft->purpose == 'stay' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                <i class="fas {{ $ft->purpose == 'stay' ? 'fa-home' : 'fa-calendar-check' }} mr-1"></i>
                                {{ ucfirst($ft->purpose ?? 'visit') }}
                            </span>
                        </td>
                        <td>
                            <span class="text-sm text-gray-600">{{ $ft->service_name }}</span>
                        </td>
                        <td>
                            @if($ft->inviter)
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-2">
                                        {{ strtoupper(substr($ft->inviter->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm">{{ $ft->inviter->name }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td>
                            @if($ft->cell)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                    {{ $ft->cell->name }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td>
                            @if($ft->fold)
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                    {{ $ft->fold->name }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-sm text-gray-600">
                                {{ $ft->first_visit_date ? \Carbon\Carbon::parse($ft->first_visit_date)->format('M j, Y') : 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <form action="{{ route('first_timers.promote', $ft->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800 transition-colors" title="Promote to Member">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                </form>
                                <a href="#" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('first_timers.destroy', $ft->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Delete" onclick="return confirm('Are you sure you want to delete this first timer?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($firstTimers->hasPages())
        <div class="flex justify-center">
            <div class="glass-card p-4">
                {{ $firstTimers->links() }}
            </div>
        </div>
    @endif
</div>
@endsection 