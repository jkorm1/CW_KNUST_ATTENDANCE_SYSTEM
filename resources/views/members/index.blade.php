@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gradient mb-2">
                <i class="fas fa-users mr-3"></i>Church Members
            </h1>
            <p class="text-[#3a1d09] font-medium">Manage and view all registered members of Charisword Gospel Ministry</p>
        </div>
        <a href="{{ route('members.create') }}" class="btn-primary">
            <i class="fas fa-user-plus mr-2"></i>Add New Member
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="stats-card">
            <div class="flex items-center justify-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
            </div>
            <div class="stats-number">{{ $members->total() }}</div>
            <div class="stats-label">Total Members</div>
        </div>

        <div class="stats-card">
            <div class="flex items-center justify-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-check text-white text-xl"></i>
                </div>
            </div>
            <div class="stats-number">{{ $members->where('status', 'active')->count() }}</div>
            <div class="stats-label">Active Members</div>
        </div>

        <div class="stats-card">
            <div class="flex items-center justify-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-crown text-white text-xl"></i>
                </div>
            </div>
            <div class="stats-number">{{ $members->where('ledCell', true)->count() + $members->where('ledFold', true)->count() }}</div>
            <div class="stats-label">Leaders</div>
        </div>

        <div class="stats-card">
            <div class="flex items-center justify-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-plus text-white text-xl"></i>
                </div>
            </div>
            <div class="stats-number">{{ $members->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
            <div class="stats-label">This Month</div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="glass-card p-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" placeholder="Search members..." class="form-input" id="searchInput">
            </div>
            <div class="flex gap-2">
                <select class="form-input" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <select class="form-input" id="genderFilter">
                    <option value="">All Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Members Table -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-modern w-full">
                <thead>
                    <tr>
                        <th class="text-left">
                            <i class="fas fa-user mr-2"></i>Name
                        </th>
                        <th class="text-left">
                            <i class="fas fa-venus-mars mr-2"></i>Gender
                        </th>
                        <th class="text-left">
                            <i class="fas fa-phone mr-2"></i>Phone
                        </th>
                        <th class="text-left">
                            <i class="fas fa-circle mr-2"></i>Status
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
                            <i class="fas fa-star mr-2"></i>Leader Role
                        </th>
                        <th class="text-left">
                            <i class="fas fa-user-friends mr-2"></i>Invitees
                        </th>
                        <th class="text-left">
                            <i class="fas fa-seedling mr-2"></i>Planters
                        </th>
                        <th class="text-left">
                            <i class="fas fa-cogs mr-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                    <tr class="hover:bg-orange-50 transition-colors duration-200">
                        <td class="font-semibold text-[#3a1d09]">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-[#f58502] to-[#ff9a2e] rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                {{ $member->name }}
                            </div>
                        </td>
                        <td>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $member->gender === 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ ucfirst($member->gender) }}
                            </span>
                        </td>
                        <td>
                            <a href="tel:{{ $member->phone }}" class="text-[#f58502] hover:text-[#3a1d09] transition-colors">
                                <i class="fas fa-phone mr-1"></i>{{ $member->phone }}
                            </a>
                        </td>
                        <td>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($member->status) }}
                            </span>
                        </td>
                        <td>
                            @if($member->cell)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                    {{ $member->cell->name }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td>
                            @if($member->fold)
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                    {{ $member->fold->name }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-sm text-gray-600">
                                {{ $member->first_visit_date ? \Carbon\Carbon::parse($member->first_visit_date)->format('M j, Y') : 'N/A' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $roles = [];
                                if ($member->ledCell) $roles[] = 'Cell Leader';
                                if ($member->assistantLedCell) $roles[] = 'Assistant Cell Leader';
                                if ($member->ledFold) $roles[] = 'Fold Leader';
                                if ($member->assistantLedFold) $roles[] = 'Assistant Fold Leader';
                            @endphp
                            @if(count($roles))
                                <div class="flex flex-wrap gap-1">
                                    @foreach($roles as $role)
                                        <span class="px-2 py-1 bg-gradient-to-r from-[#f58502] to-[#ff9a2e] text-white rounded-full text-xs font-semibold">
                                            {{ $role }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td>
                            {{ $member->invitees_count }}
                        </td>
                        <td>
                            {{ $member->planters_count }}
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('members.edit', $member) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit Member">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('members.show', $member) }}" class="text-green-600 hover:text-green-800 transition-colors" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Delete Member" onclick="return confirm('Are you sure you want to delete this member?')">
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
    @if($members->hasPages())
        <div class="flex justify-center">
            <div class="glass-card p-4">
                {{ $members->links() }}
            </div>
        </div>
    @endif
</div>

<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Filter functionality
    document.getElementById('statusFilter').addEventListener('change', filterTable);
    document.getElementById('genderFilter').addEventListener('change', filterTable);

    function filterTable() {
        const statusFilter = document.getElementById('statusFilter').value;
        const genderFilter = document.getElementById('genderFilter').value;
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const status = row.children[3].textContent.toLowerCase();
            const gender = row.children[1].textContent.toLowerCase();
            
            const statusMatch = !statusFilter || status.includes(statusFilter);
            const genderMatch = !genderFilter || gender.includes(genderFilter);
            
            row.style.display = statusMatch && genderMatch ? '' : 'none';
        });
    }
</script>
@endsection 