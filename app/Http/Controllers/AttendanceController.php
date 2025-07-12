<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Member;
use App\Models\Service;
use App\Models\FirstTimer;
use App\Services\ServiceGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    protected $serviceGenerator;

    public function __construct(ServiceGeneratorService $serviceGenerator)
    {
        $this->serviceGenerator = $serviceGenerator;
    }

    /**
     * Display a listing of the resource.
     */
    public function index($service_id)
    {
        $user = auth()->user();

        try {
            // Check if it's an auto-generated service
            if (str_starts_with($service_id, 'auto_')) {
                // Handle auto-generated service
                $service = $this->serviceGenerator->parseServiceId($service_id);
                if (!$service) {
                    abort(404, 'Service not found');
                }
                $service = (object) $service;
            } else {
                // Handle database service
                $service = Service::findOrFail($service_id);
            }

            // Get manageable members (exclude those who were converted from first timers on this service date)
            $serviceDate = $service->service_date ?? date('Y-m-d');
            $members = $user->getManageableMembers()
                           ->whereNotIn('id', function($query) use ($serviceDate) {
                               $query->select('id')
                                     ->from('members')
                                     ->whereDate('created_at', $serviceDate)
                                     ->whereNotNull('invited_by');
                           })
                           ->orderBy('name')
                           ->get();
            
            // Get all first timers for this service date (both visit and stay)
            $firstTimers = FirstTimer::whereDate('first_visit_date', $serviceDate)
                                   ->orderBy('name')
                                   ->get();

            // Get attendance for members
            $memberAttendance = Attendance::where('service_id', $service_id)
                ->whereIn('member_id', $members->pluck('id'))
                ->pluck('present', 'member_id');

            // Get attendance for first timers
            $firstTimerAttendance = Attendance::where('service_id', $service_id)
                ->whereNotNull('first_timer_id')
                ->pluck('present', 'first_timer_id');

            // Automatically create attendance records for first timers who don't have one yet
            foreach ($firstTimers as $firstTimer) {
                if (!isset($firstTimerAttendance[$firstTimer->id])) {
                    // Auto-mark first timers as present
                    Attendance::create([
                        'service_id' => $service_id,
                        'first_timer_id' => $firstTimer->id,
                        'present' => true, // Auto-mark as present
                    ]);
                    $firstTimerAttendance[$firstTimer->id] = true;
                }
            }

            return view('attendance.index', compact('service', 'members', 'memberAttendance', 'firstTimers', 'firstTimerAttendance'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading attendance: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::orderBy('service_date', 'desc')->get();
        // Use the centralized method to get manageable members
        $members = auth()->user()->getManageableMembers()->orderBy('name')->get();

        return view('attendance.create', compact('services', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|string',
            'member_id' => 'required|exists:members,id',
            'present' => 'required|boolean',
        ]);

        // Authorization check
        $this->authorize('manage', Member::findOrFail($request->member_id));

        try {
            Attendance::updateOrCreate(
                [
                    'service_id' => $request->service_id,
                    'member_id' => $request->member_id,
                ],
                [
                    'present' => $request->present,
                ]
            );

            return back()->with('success', 'Attendance recorded successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error recording attendance: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        $this->authorize('view', $attendance);
        return view('attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        $this->authorize('update', $attendance);
        $services = Service::orderBy('service_date', 'desc')->get();
        // Use the centralized method to get manageable members
        $members = auth()->user()->getManageableMembers()->orderBy('name')->get();

        return view('attendance.edit', compact('attendance', 'services', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $this->authorize('update', $attendance);
        $request->validate([
            'service_id' => 'required|string',
            'member_id' => 'required|exists:members,id',
            'present' => 'required|boolean',
        ]);

        try {
            $attendance->update([
                'service_id' => $request->service_id,
                'member_id' => $request->member_id,
                'present' => $request->present,
            ]);

            return redirect()->route('attendance.index', $request->service_id)
                           ->with('success', 'Attendance updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating attendance: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $this->authorize('delete', $attendance);
        try {
            $service_id = $attendance->service_id;
            $attendance->delete();
            
            return redirect()->route('attendance.index', $service_id)
                           ->with('success', 'Attendance record deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting attendance: ' . $e->getMessage());
        }
    }

    /**
     * Mark a member's attendance status for a service.
     */
    public function mark(Request $request, $service_id, $member_id)
    {
        $request->validate(['present' => 'required|boolean']);

        $member = Member::findOrFail($member_id);
        $this->authorize('manage', $member);

        try {
            Attendance::updateOrCreate(
                ['service_id' => $service_id, 'member_id' => $member_id],
                ['present' => $request->present]
            );
            $status = $request->present ? 'present' : 'absent';
            return back()->with('success', "Marked {$status}!");
        } catch (\Exception $e) {
            return back()->with('error', "Error marking {$status}: " . $e->getMessage());
        }
    }

    /**
     * Bulk update attendance for a service.
     */
    public function bulkUpdate(Request $request, $service_id)
    {
        $request->validate([
            'attendance' => 'required|array',
            'attendance.*.member_id' => 'required|exists:members,id',
            'attendance.*.present' => 'required|boolean',
        ]);

        $user = auth()->user();
        $manageableMemberIds = $user->getManageableMembers()->pluck('id');

        DB::beginTransaction();
        try {
            foreach ($request->attendance as $attData) {
                // Ensure user can manage this member
                if (!$manageableMemberIds->contains($attData['member_id'])) {
                    throw new \Exception("You do not have permission to update attendance for member ID {$attData['member_id']}.");
                }

                Attendance::updateOrCreate(
                    [
                        'service_id' => $service_id,
                        'member_id' => $attData['member_id'],
                    ],
                    ['present' => $attData['present']]
                );
            }
            DB::commit();
            return back()->with('success', 'Attendance updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating attendance: ' . $e->getMessage());
        }
    }

    /**
     * Finalize attendance for a service (and create the service if it was auto-generated).
     */
    public function finalize($service_id)
    {
        // Check authorization
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isCellLeader() && !$user->isFoldLeader()) {
            abort(403, 'You do not have permission to finalize attendance.');
        }

        try {
            DB::beginTransaction();
            
            // Get service date
            $serviceDate = null;
            if (str_starts_with($service_id, 'auto_')) {
                $service = $this->serviceGenerator->parseServiceId($service_id);
                $serviceDate = $service['service_date'] ?? date('Y-m-d');
            } else {
                $service = Service::findOrFail($service_id);
                $serviceDate = $service->service_date ?? date('Y-m-d');
            }
            
            // Get members based on user's role
            if ($user->isAdmin()) {
                $members = Member::pluck('id');
            } elseif ($user->isCellLeader()) {
                $cell = $user->getLedCell();
                $members = $cell ? $cell->members()->pluck('id') : collect([]);
            } elseif ($user->isFoldLeader()) {
                $fold = $user->getLedFold();
                $members = $fold ? $fold->members()->pluck('id') : collect([]);
            } else {
                $members = collect([]);
            }
            
            // Get first timers for this service date
            $firstTimers = FirstTimer::whereDate('first_visit_date', $serviceDate)->pluck('id');
            
            // Mark unmarked members as absent
            $markedMembers = Attendance::where('service_id', $service_id)
                                     ->whereNotNull('member_id')
                                     ->pluck('member_id');
            $absentMembers = $members->diff($markedMembers);
            
            foreach ($absentMembers as $member_id) {
                Attendance::create([
                    'service_id' => $service_id,
                    'member_id' => $member_id,
                    'present' => false,
                ]);
            }
            
            // Ensure all first timers are marked as present
            $markedFirstTimers = Attendance::where('service_id', $service_id)
                                         ->whereNotNull('first_timer_id')
                                         ->pluck('first_timer_id');
            $unmarkedFirstTimers = $firstTimers->diff($markedFirstTimers);
            
            foreach ($unmarkedFirstTimers as $first_timer_id) {
                Attendance::create([
                    'service_id' => $service_id,
                    'first_timer_id' => $first_timer_id,
                    'present' => true, // Auto-mark first timers as present
                ]);
            }
            
            DB::commit();
            return back()->with('success', 'Attendance finalized! All unmarked members marked as absent. First timers automatically marked as present.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error finalizing attendance: ' . $e->getMessage());
        }
    }

    // Get attendance statistics for a service
    public function statistics($service_id)
    {
        try {
            $totalMembers = Member::count();
            $totalFirstTimers = FirstTimer::whereDate('first_visit_date', date('Y-m-d'))->count();
            $totalAttendees = $totalMembers + $totalFirstTimers;
            
            $memberPresentCount = Attendance::where('service_id', $service_id)
                                          ->whereNotNull('member_id')
                                          ->where('present', true)
                                          ->count();
            $firstTimerPresentCount = Attendance::where('service_id', $service_id)
                                              ->whereNotNull('first_timer_id')
                                              ->where('present', true)
                                              ->count();
            $presentCount = $memberPresentCount + $firstTimerPresentCount;
            
            $memberAbsentCount = Attendance::where('service_id', $service_id)
                                         ->whereNotNull('member_id')
                                         ->where('present', false)
                                         ->count();
            $firstTimerAbsentCount = Attendance::where('service_id', $service_id)
                                             ->whereNotNull('first_timer_id')
                                             ->where('present', false)
                                             ->count();
            $absentCount = $memberAbsentCount + $firstTimerAbsentCount;
            
            $unmarkedCount = $totalMembers - $memberPresentCount - $memberAbsentCount;
            
            $attendanceRate = $totalAttendees > 0 ? round(($presentCount / $totalAttendees) * 100, 2) : 0;
            
            return response()->json([
                'total_members' => $totalMembers,
                'total_first_timers' => $totalFirstTimers,
                'total_attendees' => $totalAttendees,
                'present' => $presentCount,
                'absent' => $absentCount,
                'unmarked' => $unmarkedCount,
                'attendance_rate' => $attendanceRate,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Export attendance data for a service
    public function export($service_id)
    {
        try {
            // Check if it's an auto-generated service
            if (str_starts_with($service_id, 'auto_')) {
                $service = $this->serviceGenerator->parseServiceId($service_id);
                if (!$service) {
                    abort(404, 'Service not found');
                }
                $service = (object) $service;
            } else {
                $service = Service::findOrFail($service_id);
            }

            $memberAttendance = Attendance::where('service_id', $service_id)
                                        ->whereNotNull('member_id')
                                        ->with('member')
                                        ->get();

            $firstTimerAttendance = Attendance::where('service_id', $service_id)
                                            ->whereNotNull('first_timer_id')
                                            ->with('firstTimer')
                                            ->get();

            // Generate CSV content
            $csv = "Name,Type,Status\n";
            
            // Add member attendance
            foreach ($memberAttendance as $record) {
                $status = $record->present ? 'Present' : 'Absent';
                $csv .= "\"{$record->member->name}\",Member,{$status}\n";
            }
            
            // Add first timer attendance
            foreach ($firstTimerAttendance as $record) {
                $status = $record->present ? 'Present (Auto)' : 'Absent';
                $type = $record->firstTimer->purpose === 'stay' ? 'Member' : 'First Timer';
                $csv .= "\"{$record->firstTimer->name}\",{$type},{$status}\n";
            }

            $filename = "attendance_{$service_id}_" . date('Y-m-d_H-i-s') . ".csv";

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename={$filename}");
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting attendance: ' . $e->getMessage());
        }
    }

    /**
     * Mark a first timer's attendance status for a service.
     */
    public function markFirstTimer(Request $request, $service_id, $first_timer_id)
    {
        $request->validate(['present' => 'required|boolean']);

        $firstTimer = FirstTimer::findOrFail($first_timer_id);

        try {
            Attendance::updateOrCreate(
                ['service_id' => $service_id, 'first_timer_id' => $first_timer_id],
                ['present' => $request->present]
            );
            $status = $request->present ? 'present' : 'absent';
            return back()->with('success', "Marked first timer {$status}!");
        } catch (\Exception $e) {
            return back()->with('error', "Error marking first timer {$status}: " . $e->getMessage());
        }
    }
}
