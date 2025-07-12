@extends('layouts.app')

@section('content')
    <!-- Welcome Section -->
    <div class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Welcome to Charisword Gospel Ministry</h1>
            <p class="hero-subtitle">Raising Able Ministers of Grace • Transforming Lives</p>
            <div class="mt-6 flex gap-4 justify-center">
                <div class="bg-white bg-opacity-20 rounded-full px-6 py-2">
                    <i class="fas fa-calendar-day text-white mr-2"></i>
                    <span class="text-white font-semibold">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</span>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full px-6 py-2">
                    <i class="fas fa-clock text-white mr-2"></i>
                    <span class="text-white font-semibold">{{ \Carbon\Carbon::now()->format('g:i A') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="stats-card">
            <div class="stats-number">{{ $totalMembers }}</div>
            <div class="stats-label">Total Members</div>
        </div>
        <div class="stats-card">
            <div class="stats-number">{{ $activeMembers }}</div>
            <div class="stats-label">Active Members</div>
        </div>
        <div class="stats-card">
            <div class="stats-number">{{ $totalAttendance }}</div>
            <div class="stats-label">Total Attendance</div>
        </div>
        <div class="stats-card">
            <div class="stats-number">{{ $upcomingServiceCount }}</div>
            <div class="stats-label">Tracked services</div>
        </div>
    </div>

    <!-- First Timers & Conversions Analytics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-card p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-user-plus text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-[#3a1d09]">First Timers</h3>
                    <p class="text-sm text-[#f58502]">New visitors to our ministry</p>
                </div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-[#f58502] mb-2">{{ $firstTimersAnalytics['total'] }}</div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <div class="font-semibold text-blue-700">This Month</div>
                        <div class="text-lg font-bold text-blue-600">{{ $firstTimersAnalytics['this_month'] }}</div>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <div class="font-semibold text-blue-700">This Year</div>
                        <div class="text-lg font-bold text-blue-600">{{ $firstTimersAnalytics['this_year'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-star text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-[#3a1d09]">New Members</h3>
                    <p class="text-sm text-[#f58502]">Converted first timers</p>
                </div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-[#f58502] mb-2">{{ $newMembersAnalytics['this_month'] }}</div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="font-semibold text-green-700">This Month</div>
                    <div class="text-sm text-green-600">Total: {{ $newMembersAnalytics['total_new'] }}</div>
                </div>
            </div>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-percentage text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-[#3a1d09]">Conversion Rate</h3>
                    <p class="text-sm text-[#f58502]">First timers to members</p>
                </div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-[#f58502] mb-2">{{ $conversionAnalytics['monthly_rate'] }}%</div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-purple-50 p-3 rounded-lg">
                        <div class="font-semibold text-purple-700">This Month</div>
                        <div class="text-lg font-bold text-purple-600">{{ $conversionAnalytics['monthly_rate'] }}%</div>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-lg">
                        <div class="font-semibold text-purple-700">This Year</div>
                        <div class="text-lg font-bold text-purple-600">{{ $conversionAnalytics['yearly_rate'] }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Time-based Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-card p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-calendar-week text-white"></i>
                </div>
                <h4 class="text-lg font-bold text-[#3a1d09]">This Week</h4>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <span class="text-sm font-semibold text-blue-700">First Timers:</span>
                    <span class="font-bold text-blue-600 text-lg">{{ $weeklyStats['first_timers'] }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <span class="text-sm font-semibold text-green-700">Conversions:</span>
                    <span class="font-bold text-green-600 text-lg">{{ $weeklyStats['conversions'] }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                    <span class="text-sm font-semibold text-purple-700">Attendance:</span>
                    <span class="font-bold text-purple-600 text-lg">{{ $weeklyStats['attendance'] }}</span>
                </div>
            </div>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-calendar-alt text-white"></i>
                </div>
                <h4 class="text-lg font-bold text-[#3a1d09]">This Month</h4>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <span class="text-sm font-semibold text-blue-700">First Timers:</span>
                    <span class="font-bold text-blue-600 text-lg">{{ $monthlyStats['first_timers'] }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <span class="text-sm font-semibold text-green-700">Conversions:</span>
                    <span class="font-bold text-green-600 text-lg">{{ $monthlyStats['conversions'] }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                    <span class="text-sm font-semibold text-purple-700">Attendance:</span>
                    <span class="font-bold text-purple-600 text-lg">{{ $monthlyStats['attendance'] }}</span>
                </div>
            </div>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-calendar text-white"></i>
                </div>
                <h4 class="text-lg font-bold text-[#3a1d09]">This Year</h4>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <span class="text-sm font-semibold text-blue-700">First Timers:</span>
                    <span class="font-bold text-blue-600 text-lg">{{ $yearlyStats['first_timers'] }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <span class="text-sm font-semibold text-green-700">Conversions:</span>
                    <span class="font-bold text-green-600 text-lg">{{ $yearlyStats['conversions'] }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                    <span class="text-sm font-semibold text-purple-700">Attendance:</span>
                    <span class="font-bold text-purple-600 text-lg">{{ $yearlyStats['attendance'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass-card p-8">
        <h3 class="text-2xl font-bold text-[#3a1d09] mb-6 text-center">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('members.create') }}" class="group">
                <div class="bg-blue-500 p-6 rounded-lg text-center text-white hover:bg-blue-600 transition-all duration-200">
                    <i class="fas fa-user-plus text-3xl mb-4"></i>
                    <h4 class="text-lg font-bold mb-2">Add Member</h4>
                    <p class="text-sm opacity-90">Register new church member</p>
                </div>
            </a>

            <a href="{{ route('first_timers.create') }}" class="group">
                <div class="bg-green-500 p-6 rounded-lg text-center text-white hover:bg-green-600 transition-all duration-200">
                    <i class="fas fa-user-clock text-3xl mb-4"></i>
                    <h4 class="text-lg font-bold mb-2">First Timer</h4>
                    <p class="text-sm opacity-90">Record new visitor</p>
                </div>
            </a>

            <a href="{{ route('services.index') }}" class="group">
                <div class="bg-purple-500 p-6 rounded-lg text-center text-white hover:bg-purple-600 transition-all duration-200">
                    <i class="fas fa-clipboard-list text-3xl mb-4"></i>
                    <h4 class="text-lg font-bold mb-2">Take Attendance</h4>
                    <p class="text-sm opacity-90">Record service attendance</p>
                </div>
            </a>

            <a href="{{ route('services.index') }}" class="group">
                <div class="bg-orange-500 p-6 rounded-lg text-center text-white hover:bg-orange-600 transition-all duration-200">
                    <i class="fas fa-calendar-plus text-3xl mb-4"></i>
                    <h4 class="text-lg font-bold mb-2">Manage Services</h4>
                    <p class="text-sm opacity-90">Schedule church services</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Ministry Quote -->
    <div class="mt-8 text-center">
        <div class="bg-gradient-to-r from-[#f58502] to-[#ff9a2e] p-8 rounded-lg text-white">
            <i class="fas fa-quote-left text-4xl mb-4 opacity-50"></i>
            <blockquote class="text-2xl font-bold mb-4">
                "Raising Able Ministers of Grace"
            </blockquote>
            <p class="text-lg opacity-90">Charisword Gospel Ministry</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-[#3a1d09] mb-4 flex items-center"><i class="fas fa-user-friends mr-2"></i>Best Inviters</h3>
            <ol class="list-decimal pl-6 space-y-2">
                @foreach($topInviters->take(5) as $member)
                    <li class="flex justify-between items-center">
                        <span>{{ $member->name }}</span>
                        <span class="font-bold text-blue-700">{{ $member->invitees_count }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-[#3a1d09] mb-4 flex items-center"><i class="fas fa-seedling mr-2"></i>Best Planters</h3>
            <ol class="list-decimal pl-6 space-y-2">
                @foreach($topPlanters->take(5) as $member)
                    <li class="flex justify-between items-center">
                        <span>{{ $member->name }}</span>
                        <span class="font-bold text-green-700">{{ $member->planters_count }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-[#3a1d09] mb-4 flex items-center"><i class="fas fa-user-friends mr-2"></i>Best Inviters by Cell</h3>
            <ol class="list-decimal pl-6 space-y-2">
                @foreach($topInvitersByCell as $row)
                    <li class="flex justify-between items-center">
                        <span><strong>{{ $row['cell'] }}</strong>: {{ $row['member'] }}</span>
                        <span class="font-bold text-blue-700">{{ $row['count'] }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-[#3a1d09] mb-4 flex items-center"><i class="fas fa-seedling mr-2"></i>Best Planters by Cell</h3>
            <ol class="list-decimal pl-6 space-y-2">
                @foreach($topPlantersByCell as $row)
                    <li class="flex justify-between items-center">
                        <span><strong>{{ $row['cell'] }}</strong>: {{ $row['member'] }}</span>
                        <span class="font-bold text-green-700">{{ $row['count'] }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-[#3a1d09] mb-4 flex items-center"><i class="fas fa-user-friends mr-2"></i>Best Inviters by Fold</h3>
            <ol class="list-decimal pl-6 space-y-2">
                @foreach($topInvitersByFold as $row)
                    <li class="flex justify-between items-center">
                        <span><strong>{{ $row['fold'] }}</strong>: {{ $row['member'] }}</span>
                        <span class="font-bold text-blue-700">{{ $row['count'] }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-[#3a1d09] mb-4 flex items-center"><i class="fas fa-seedling mr-2"></i>Best Planters by Fold</h3>
            <ol class="list-decimal pl-6 space-y-2">
                @foreach($topPlantersByFold as $row)
                    <li class="flex justify-between items-center">
                        <span><strong>{{ $row['fold'] }}</strong>: {{ $row['member'] }}</span>
                        <span class="font-bold text-green-700">{{ $row['count'] }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
@endsection
