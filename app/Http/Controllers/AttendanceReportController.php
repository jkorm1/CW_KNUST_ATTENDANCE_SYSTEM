<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\FirstTimer;

class AttendanceReportController extends Controller
{
    // Show the report filter form
    public function index(Request $request)
    {
        // Get all unique service_id values from attendances
        $serviceIds = Attendance::distinct('service_id')->pluck('service_id')->toArray();
        // Separate numeric (manual) and string (auto) service IDs
        $manualIds = array_filter($serviceIds, fn($id) => is_numeric($id));
        $autoIds = array_filter($serviceIds, fn($id) => !is_numeric($id));

        // Fetch manual services from the Service table
        $manualServices = Service::whereIn('id', $manualIds)->orderBy('service_date', 'desc')->get();
        // Prepare auto services as objects with id and label
        $autoServices = collect($autoIds)->map(function($id) {
            return (object)[
                'id' => $id,
                'name' => 'Auto Service',
                'service_date' => $id,
                'is_auto' => true,
            ];
        });
        // Merge and sort all services by date (if possible)
        $allServices = $manualServices->map(function($s) {
            $s->is_auto = false; return $s;
        })->concat($autoServices)->sortByDesc(function($s) {
            // Try to extract date from service_date or id
            if ($s->is_auto && preg_match('/(\d{4}-\d{2}-\d{2})/', $s->service_date, $m)) {
                return $m[1];
            }
            return $s->service_date;
        });
        return view('attendance.report.index', ['services' => $allServices]);
    }

    // Show the report for a selected service
    public function show(Request $request)
    {
        $request->validate([
            'service_id' => 'required',
            'filter' => 'nullable|in:all,first_timers',
        ]);
        $serviceId = $request->service_id;
        $filter = $request->filter ?? 'all';

        // Try to fetch manual service, otherwise treat as auto
        $service = is_numeric($serviceId)
            ? Service::find($serviceId)
            : (object)[
                'id' => $serviceId,
                'name' => 'Auto Service',
                'service_date' => $serviceId,
                'is_auto' => true,
            ];
        if (!$service) {
            return back()->with('error', 'Service not found.');
        }

        // Get all attendance records for this service
        $attendances = Attendance::where('service_id', $serviceId)
            ->with(['member', 'firstTimer'])
            ->get();

        // Separate members and first timers
        $memberRecords = $attendances->whereNotNull('member_id');
        $firstTimerRecords = $attendances->whereNotNull('first_timer_id');

        // Summaries
        $summary = [
            'present' => $attendances->where('present', true)->count(),
            'absent' => $attendances->where('present', false)->count(),
            'first_timers' => $firstTimerRecords->count(),
            'first_timers_stay' => $firstTimerRecords->filter(fn($a) => $a->firstTimer && $a->firstTimer->purpose === 'stay')->count(),
            'first_timers_visit' => $firstTimerRecords->filter(fn($a) => $a->firstTimer && $a->firstTimer->purpose === 'visit')->count(),
        ];

        return view('attendance.report.show', compact('service', 'attendances', 'memberRecords', 'firstTimerRecords', 'summary', 'filter'));
    }
}
