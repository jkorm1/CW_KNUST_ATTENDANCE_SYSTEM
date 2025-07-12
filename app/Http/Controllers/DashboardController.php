<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;
use App\Models\Attendance;
use App\Models\FirstTimer;
use App\Models\Cell;
use App\Models\Fold;
use App\Services\ServiceGeneratorService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $serviceGenerator;

    public function __construct(ServiceGeneratorService $serviceGenerator)
    {
        $this->serviceGenerator = $serviceGenerator;
    }

    public function index()
    {
        $user = Auth::user();

        // Get manageable members query
        $membersQuery = $user->getManageableMembers();

        // Basic Stats
        $totalMembers = $membersQuery->count();
        $activeMembers = (clone $membersQuery)->where('status', 'active')->count();
        
        // Use clone to avoid affecting the original query
        $memberIds = (clone $membersQuery)->pluck('id');

        // Attendance stats
        $totalAttendance = Attendance::whereIn('member_id', $memberIds)->count();

        // First Timers and Conversions Analytics
        $firstTimersAnalytics = $this->getFirstTimersAnalytics();
        $conversionAnalytics = $this->getConversionAnalytics();
        $newMembersAnalytics = $this->getNewMembersAnalytics();

        // Services
        $allServices = $this->serviceGenerator->getAllServices();
        $upcomingServices = $this->serviceGenerator->getUpcomingServices();
        $todayServices = $this->serviceGenerator->getTodayServices();
        $nextServices = array_slice(array_merge($todayServices, $upcomingServices), 0, 5);

        // Recent Activity
        $recentAttendance = Attendance::with('member')
            ->whereIn('member_id', $memberIds)
            ->latest()
            ->take(5)
            ->get();

        // Top Performers
        $topInviters = $this->getTopInviters();
        $topPlanters = $this->getTopPlanters();
        $topCells = $this->getTopCells();
        $topFolds = $this->getTopFolds();

        // Time-based Analytics
        $weeklyStats = $this->getWeeklyStats();
        $monthlyStats = $this->getMonthlyStats();
        $yearlyStats = $this->getYearlyStats();

        $topInvitersByCell = $this->getTopInvitersByCell();
        $topPlantersByCell = $this->getTopPlantersByCell();
        $topInvitersByFold = $this->getTopInvitersByFold();
        $topPlantersByFold = $this->getTopPlantersByFold();

        return view('dashboard', [
            'totalMembers' => $totalMembers,
            'activeMembers' => $activeMembers,
            'totalAttendance' => $totalAttendance,
            'upcomingServiceCount' => count($allServices),
            'nextServices' => $nextServices,
            'recentAttendance' => $recentAttendance,
            'firstTimersAnalytics' => $firstTimersAnalytics,
            'conversionAnalytics' => $conversionAnalytics,
            'newMembersAnalytics' => $newMembersAnalytics,
            'topInviters' => $topInviters,
            'topPlanters' => $topPlanters,
            'topCells' => $topCells,
            'topFolds' => $topFolds,
            'weeklyStats' => $weeklyStats,
            'monthlyStats' => $monthlyStats,
            'yearlyStats' => $yearlyStats,
            'topInvitersByCell' => $topInvitersByCell,
            'topPlantersByCell' => $topPlantersByCell,
            'topInvitersByFold' => $topInvitersByFold,
            'topPlantersByFold' => $topPlantersByFold,
        ]);
    }

    private function getFirstTimersAnalytics()
    {
        $now = Carbon::now();
        
        return [
            'total' => FirstTimer::count(),
            'this_week' => FirstTimer::whereBetween('first_visit_date', [$now->startOfWeek(), $now->endOfWeek()])->count(),
            'this_month' => FirstTimer::whereMonth('first_visit_date', $now->month)->whereYear('first_visit_date', $now->year)->count(),
            'this_year' => FirstTimer::whereYear('first_visit_date', $now->year)->count(),
            'visit' => FirstTimer::where('purpose', 'visit')->count(),
            'stay' => FirstTimer::where('purpose', 'stay')->count(),
        ];
    }

    private function getConversionAnalytics()
    {
        $now = Carbon::now();
        
        // Get first timers who chose to stay (converted to members)
        $convertedThisMonth = FirstTimer::where('purpose', 'stay')
            ->whereMonth('first_visit_date', $now->month)
            ->whereYear('first_visit_date', $now->year)
            ->count();
            
        $totalFirstTimersThisMonth = FirstTimer::whereMonth('first_visit_date', $now->month)
            ->whereYear('first_visit_date', $now->year)
            ->count();

        $convertedThisYear = FirstTimer::where('purpose', 'stay')
            ->whereYear('first_visit_date', $now->year)
            ->count();
            
        $totalFirstTimersThisYear = FirstTimer::whereYear('first_visit_date', $now->year)->count();

        return [
            'monthly_rate' => $totalFirstTimersThisMonth > 0 ? round(($convertedThisMonth / $totalFirstTimersThisMonth) * 100, 1) : 0,
            'yearly_rate' => $totalFirstTimersThisYear > 0 ? round(($convertedThisYear / $totalFirstTimersThisYear) * 100, 1) : 0,
            'converted_this_month' => $convertedThisMonth,
            'converted_this_year' => $convertedThisYear,
            'total_converted' => FirstTimer::where('purpose', 'stay')->count(),
        ];
    }

    private function getNewMembersAnalytics()
    {
        $now = Carbon::now();
        
        // New members from first timers who chose to stay
        $newMembersThisMonth = FirstTimer::where('purpose', 'stay')
            ->whereMonth('first_visit_date', $now->month)
            ->whereYear('first_visit_date', $now->year)
            ->count();
            
        $newMembersThisYear = FirstTimer::where('purpose', 'stay')
            ->whereYear('first_visit_date', $now->year)
            ->count();

        return [
            'this_month' => $newMembersThisMonth,
            'this_year' => $newMembersThisYear,
            'total_new' => FirstTimer::where('purpose', 'stay')->count(),
        ];
    }

    private function getTopInviters()
    {
        return Member::withCount(['invitees'])
            ->having('invitees_count', '>', 0)
            ->orderByDesc('invitees_count')
            ->take(10)
            ->get();
    }

    private function getTopPlanters()
    {
        return Member::withCount(['invitees as planters_count' => function($query) {
            $query->where('purpose', 'stay');
        }])
        ->having('planters_count', '>', 0)
        ->orderByDesc('planters_count')
        ->take(10)
        ->get();
    }

    private function getTopCells()
    {
        return Cell::withCount(['members', 'folds'])
            ->with(['members' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderByDesc('members_count')
            ->take(5)
            ->get();
    }

    private function getTopFolds()
    {
        return Fold::withCount(['members'])
            ->with(['members' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderByDesc('members_count')
            ->take(5)
            ->get();
    }

    private function getWeeklyStats()
    {
        $now = Carbon::now();
        $startOfWeek = $now->copy()->startOfWeek();
        $endOfWeek = $now->copy()->endOfWeek();

        return [
            'first_timers' => FirstTimer::whereBetween('first_visit_date', [$startOfWeek, $endOfWeek])->count(),
            'conversions' => FirstTimer::where('purpose', 'stay')->whereBetween('first_visit_date', [$startOfWeek, $endOfWeek])->count(),
            'attendance' => Attendance::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count(),
        ];
    }

    private function getMonthlyStats()
    {
        $now = Carbon::now();
        
        return [
            'first_timers' => FirstTimer::whereMonth('first_visit_date', $now->month)->whereYear('first_visit_date', $now->year)->count(),
            'conversions' => FirstTimer::where('purpose', 'stay')->whereMonth('first_visit_date', $now->month)->whereYear('first_visit_date', $now->year)->count(),
            'attendance' => Attendance::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count(),
        ];
    }

    private function getYearlyStats()
    {
        $now = Carbon::now();
        
        return [
            'first_timers' => FirstTimer::whereYear('first_visit_date', $now->year)->count(),
            'conversions' => FirstTimer::where('purpose', 'stay')->whereYear('first_visit_date', $now->year)->count(),
            'attendance' => Attendance::whereYear('created_at', $now->year)->count(),
        ];
    }

    public function analytics(Request $request)
    {
        $period = $request->get('period', 'month');
        $type = $request->get('type', 'overview');
        
        $data = [];
        
        switch($type) {
            case 'first_timers':
                $data = $this->getFirstTimersDetailedAnalytics($period);
                break;
            case 'conversions':
                $data = $this->getConversionsDetailedAnalytics($period);
                break;
            case 'attendance':
                $data = $this->getAttendanceDetailedAnalytics($period);
                break;
            case 'cells':
                $data = $this->getCellsDetailedAnalytics($period);
                break;
            case 'folds':
                $data = $this->getFoldsDetailedAnalytics($period);
                break;
            default:
                $data = $this->getOverviewAnalytics($period);
        }
        
        return view('dashboard.analytics', compact('data', 'period', 'type'));
    }

    private function getFirstTimersDetailedAnalytics($period)
    {
        $query = FirstTimer::query();
        
        switch($period) {
            case 'week':
                $query->whereBetween('first_visit_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('first_visit_date', Carbon::now()->month)->whereYear('first_visit_date', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('first_visit_date', Carbon::now()->year);
                break;
        }
        
        return [
            'total' => $query->count(),
            'by_purpose' => $query->select('purpose', DB::raw('count(*) as count'))->groupBy('purpose')->get(),
            'by_inviter' => $query->with('inviter')->select('invited_by', DB::raw('count(*) as count'))->groupBy('invited_by')->orderByDesc('count')->take(10)->get(),
            'daily_trend' => $query->select(DB::raw('DATE(first_visit_date) as date'), DB::raw('count(*) as count'))->groupBy('date')->orderBy('date')->get(),
        ];
    }

    private function getConversionsDetailedAnalytics($period)
    {
        $query = FirstTimer::where('purpose', 'stay');
        
        switch($period) {
            case 'week':
                $query->whereBetween('first_visit_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('first_visit_date', Carbon::now()->month)->whereYear('first_visit_date', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('first_visit_date', Carbon::now()->year);
                break;
        }
        
        $totalFirstTimers = FirstTimer::query();
        switch($period) {
            case 'week':
                $totalFirstTimers->whereBetween('first_visit_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $totalFirstTimers->whereMonth('first_visit_date', Carbon::now()->month)->whereYear('first_visit_date', Carbon::now()->year);
                break;
            case 'year':
                $totalFirstTimers->whereYear('first_visit_date', Carbon::now()->year);
                break;
        }
        
        $converted = $query->count();
        $total = $totalFirstTimers->count();
        
        return [
            'converted' => $converted,
            'total_first_timers' => $total,
            'conversion_rate' => $total > 0 ? round(($converted / $total) * 100, 1) : 0,
            'by_inviter' => $query->with('inviter')->select('invited_by', DB::raw('count(*) as count'))->groupBy('invited_by')->orderByDesc('count')->take(10)->get(),
        ];
    }

    private function getAttendanceDetailedAnalytics($period)
    {
        $query = Attendance::query();
        
        switch($period) {
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
        }
        
        return [
            'total' => $query->count(),
            'present' => (clone $query)->where('present', true)->count(),
            'absent' => (clone $query)->where('present', false)->count(),
            'by_service' => $query->with('service')->select('service_id', DB::raw('count(*) as count'))->groupBy('service_id')->orderByDesc('count')->take(10)->get(),
        ];
    }

    private function getCellsDetailedAnalytics($period)
    {
        return Cell::withCount(['members', 'folds'])
            ->with(['members' => function($query) use ($period) {
                switch($period) {
                    case 'week':
                        $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                        break;
                    case 'month':
                        $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
                        break;
                    case 'year':
                        $query->whereYear('created_at', Carbon::now()->year);
                        break;
                }
            }])
            ->orderByDesc('members_count')
            ->get();
    }

    private function getFoldsDetailedAnalytics($period)
    {
        return Fold::withCount(['members'])
            ->with(['members' => function($query) use ($period) {
                switch($period) {
                    case 'week':
                        $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                        break;
                    case 'month':
                        $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
                        break;
                    case 'year':
                        $query->whereYear('created_at', Carbon::now()->year);
                        break;
                }
            }])
            ->orderByDesc('members_count')
            ->get();
    }

    private function getOverviewAnalytics($period)
    {
        return [
            'first_timers' => $this->getFirstTimersDetailedAnalytics($period),
            'conversions' => $this->getConversionsDetailedAnalytics($period),
            'attendance' => $this->getAttendanceDetailedAnalytics($period),
            'cells' => $this->getCellsDetailedAnalytics($period),
            'folds' => $this->getFoldsDetailedAnalytics($period),
        ];
    }

    public function cells()
    {
        $cells = \App\Models\Cell::with('folds')->get();
        return view('dashboard.cells', compact('cells'));
    }

    public function folds()
    {
        $folds = \App\Models\Fold::with('cell')->get();
        return view('dashboard.folds', compact('folds'));
    }

    public function members()
    {
        $members = \App\Models\Member::with('cell', 'fold')->get();
        return view('dashboard.members', compact('members'));
    }

    public function cellDetail($id)
    {
        $cell = \App\Models\Cell::with('folds', 'members')->findOrFail($id);
        return view('dashboard.cell_detail', compact('cell'));
    }

    public function foldDetail($id)
    {
        $fold = \App\Models\Fold::with('cell', 'members')->findOrFail($id);
        return view('dashboard.fold_detail', compact('fold'));
    }

    public function memberDetail($id)
    {
        $member = \App\Models\Member::with('cell', 'fold')->findOrFail($id);
        return view('dashboard.member_detail', compact('member'));
    }

    private function getTopInvitersByCell()
    {
        return \App\Models\Cell::with(['members' => function($q) {
            $q->withCount('invitees');
        }])->get()->map(function($cell) {
            $best = $cell->members->sortByDesc('invitees_count')->first();
            return $best && $best->invitees_count > 0 ? [
                'cell' => $cell->name,
                'member' => $best->name,
                'count' => $best->invitees_count
            ] : null;
        })->filter()->values();
    }

    private function getTopPlantersByCell()
    {
        return \App\Models\Cell::with(['members' => function($q) {
            $q->withCount(['invitees as planters_count' => function($query) {
                $query->where('purpose', 'stay');
            }]);
        }])->get()->map(function($cell) {
            $best = $cell->members->sortByDesc('planters_count')->first();
            return $best && $best->planters_count > 0 ? [
                'cell' => $cell->name,
                'member' => $best->name,
                'count' => $best->planters_count
            ] : null;
        })->filter()->values();
    }

    private function getTopInvitersByFold()
    {
        return \App\Models\Fold::with(['members' => function($q) {
            $q->withCount('invitees');
        }])->get()->map(function($fold) {
            $best = $fold->members->sortByDesc('invitees_count')->first();
            return $best && $best->invitees_count > 0 ? [
                'fold' => $fold->name,
                'member' => $best->name,
                'count' => $best->invitees_count
            ] : null;
        })->filter()->values();
    }

    private function getTopPlantersByFold()
    {
        return \App\Models\Fold::with(['members' => function($q) {
            $q->withCount(['invitees as planters_count' => function($query) {
                $query->where('purpose', 'stay');
            }]);
        }])->get()->map(function($fold) {
            $best = $fold->members->sortByDesc('planters_count')->first();
            return $best && $best->planters_count > 0 ? [
                'fold' => $fold->name,
                'member' => $best->name,
                'count' => $best->planters_count
            ] : null;
        })->filter()->values();
    }
}
