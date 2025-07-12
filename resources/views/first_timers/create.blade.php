@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Register First Timers</h1>
        <a href="{{ route('first_timers.index') }}" class="text-gray-600 hover:text-gray-800">← Back to First Timers</a>
    </div>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('first_timers.store') }}" method="POST" class="space-y-6 font-montserrat bg-white shadow-md rounded-lg p-6 border border-[#3a1d09]" id="bulkFirstTimersForm">
        @csrf
        <h3 class="text-lg font-semibold mb-4 text-[#3a1d09]">First Timers Information</h3>
        <div id="firstTimersRows">
            <div class="first-timer-row grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 border-b pb-4">
                <div>
                    <label class="block text-sm font-bold text-[#3a1d09] mb-2">Full Name *</label>
                    <input type="text" name="first_timers[0][name]" class="w-full px-3 py-2 border border-[#3a1d09] rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#3a1d09] mb-2">Date of Birth</label>
                    <input type="date" name="first_timers[0][date_of_birth]" class="w-full px-3 py-2 border border-[#3a1d09] rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#3a1d09] mb-2">Contact Number *</label>
                    <input type="tel" name="first_timers[0][phone]" class="w-full px-3 py-2 border border-[#3a1d09] rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#3a1d09] mb-2">Residence/Address</label>
                    <input type="text" name="first_timers[0][residence]" class="w-full px-3 py-2 border border-[#3a1d09] rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#3a1d09] mb-2">Purpose of Visit *</label>
                    <select name="first_timers[0][purpose]" class="w-full px-3 py-2 border border-[#3a1d09] rounded-md" required>
                        <option value="">Select Purpose</option>
                        <option value="visit">Visit</option>
                        <option value="stay">Stay (Join Church)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#3a1d09] mb-2">Service Attended *</label>
                    <select name="first_timers[0][service_id]" class="w-full px-3 py-2 border border-[#3a1d09] rounded-md service-select" required>
                        <option value="">Select Service</option>
                        @foreach($allServices as $service)
                            @php
                                $serviceId = $service['id'] ?? $service->id;
                                $serviceDate = $service['service_date'] ?? $service->service_date;
                                if (isset($service['is_auto_generated']) && $service['is_auto_generated']) {
                                    preg_match('/(\\d{4}-\\d{2}-\\d{2})/', $serviceDate, $matches);
                                    $dateValue = $matches[1] ?? $serviceDate;
                                } else {
                                    $dateValue = $serviceDate;
                                }
                            @endphp
                            <option value="{{ $serviceId }}" data-date="{{ $dateValue }}">
                                {{ $service['name'] ?? $service->name }} - {{ \Carbon\Carbon::parse($serviceDate)->format('D, d M Y') }}
                                @if(isset($service['is_auto_generated']) && $service['is_auto_generated'])
                                    (Auto-generated)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="first_timers[0][first_visit_date]" class="first-visit-date">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#3a1d09] mb-2">Invited By (Search Member)</label>
                    <input type="text" class=" px-3 py-2 border border-[#3a1d09] rounded-md inviter-search" placeholder="Start typing member name..." autocomplete="off">
                    <input type="hidden" name="first_timers[0][invited_by]" class="invited-by-id">
                    <div class="search-results absolute z-10 w-full bg-white border border-[#3a1d09] rounded-md shadow-lg max-h-60 overflow-y-auto hidden"></div>
                    <div class="selected-inviter mt-2 p-2 bg-green-50 border border-green-200 rounded-md hidden">
                        <span class="text-sm text-green-700">Selected: <span class="inviter-name"></span></span>
                        <button type="button" class="ml-2 text-red-600 hover:text-red-800 text-sm clear-inviter">Clear</button>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-[#3a1d09] mb-2">Notes</label>
                    <textarea name="first_timers[0][notes]" rows="2" class="w-full px-3 py-2 border border-[#3a1d09] rounded-md"></textarea>
                </div>
            </div>
        </div>
        <button type="button" id="addRowBtn" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-800 font-bold mb-4">+ Add Row</button>
        <div class="flex justify-end">
            <x-primary-button>{{ __('Register First Timers') }}</x-primary-button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowCount = 1;
    document.getElementById('addRowBtn').addEventListener('click', function() {
        const container = document.getElementById('firstTimersRows');
        const newRow = container.firstElementChild.cloneNode(true);
        // Update all name attributes with new index
        newRow.querySelectorAll('input, select, textarea').forEach(function(input) {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, '[' + rowCount + ']');
                if (input.type !== 'hidden') input.value = '';
                if (input.classList.contains('invited-by-id')) input.value = '';
            }
        });
        // Reset inviter search UI
        newRow.querySelectorAll('.inviter-search').forEach(i => i.value = '');
        newRow.querySelectorAll('.search-results').forEach(i => i.classList.add('hidden'));
        newRow.querySelectorAll('.selected-inviter').forEach(i => i.classList.add('hidden'));
        newRow.querySelectorAll('.inviter-name').forEach(i => i.textContent = '');
        container.appendChild(newRow);
        rowCount++;
    });

    // Set first_visit_date for each row based on selected service
    function updateFirstVisitDate(row) {
        const serviceSelect = row.querySelector('.service-select');
        const firstVisitDateInput = row.querySelector('.first-visit-date');
        const selected = serviceSelect.options[serviceSelect.selectedIndex];
        const date = selected.getAttribute('data-date');
        if (date) {
            firstVisitDateInput.value = date;
        }
    }
    document.getElementById('firstTimersRows').addEventListener('change', function(e) {
        if (e.target.classList.contains('service-select')) {
            updateFirstVisitDate(e.target.closest('.first-timer-row'));
        }
    });
    // Set on page load for first row
    document.querySelectorAll('.first-timer-row').forEach(updateFirstVisitDate);

    // Member search/autocomplete for each row
    document.getElementById('firstTimersRows').addEventListener('input', function(e) {
        if (e.target.classList.contains('inviter-search')) {
            const input = e.target;
            const row = input.closest('.first-timer-row');
            const resultsBox = row.querySelector('.search-results');
            const invitedById = row.querySelector('.invited-by-id');
            const selectedInviter = row.querySelector('.selected-inviter');
            const inviterName = row.querySelector('.inviter-name');
            const clearBtn = row.querySelector('.clear-inviter');
            const query = input.value.trim();
            if (query.length < 2) {
                resultsBox.classList.add('hidden');
                return;
            }
            fetch(`/api/members/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsBox.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(member => {
                            const div = document.createElement('div');
                            div.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer';
                            div.textContent = member.name;
                            div.addEventListener('click', () => {
                                invitedById.value = member.id;
                                inviterName.textContent = member.name;
                                input.value = member.name;
                                selectedInviter.classList.remove('hidden');
                                resultsBox.classList.add('hidden');
                            });
                            resultsBox.appendChild(div);
                        });
                        resultsBox.classList.remove('hidden');
                    } else {
                        resultsBox.classList.add('hidden');
                    }
                })
                .catch(error => {
                    resultsBox.classList.add('hidden');
                });
        }
    });
    document.getElementById('firstTimersRows').addEventListener('click', function(e) {
        if (e.target.classList.contains('clear-inviter')) {
            const row = e.target.closest('.first-timer-row');
            row.querySelector('.invited-by-id').value = '';
            row.querySelector('.inviter-search').value = '';
            row.querySelector('.selected-inviter').classList.add('hidden');
        }
    });
});
</script>
@endpush 