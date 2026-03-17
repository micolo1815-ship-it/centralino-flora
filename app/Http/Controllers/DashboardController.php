<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use App\Models\Tree;
use App\Models\Location;
use App\Models\User;
use App\Models\Officer;
use App\Models\ActivityType;
use Carbon\Carbon;
use App\Models\TreeView;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $trees = Tree::with('locations')->paginate(5);
        $treesCount = Tree::count();
        $locationCount = Location::count();
        $archiveTreesCount = Tree::where('status', 'archive')->count();
        $totalWebsiteViews = TreeView::count();

        $activityLogs = ActivityLog::with(['type', 'user'])
            ->latest()
            ->paginate(3);

        $activityLogs->getCollection()->transform(function ($log) {
            $subjectClass = $log->subject_type;
            if (in_array($log->type_id, [1, 2]) && class_exists($subjectClass)) {
                $log->subjectModel = $subjectClass::find($log->subject_id);
            } else {
                $log->subjectModel = null;
            }
            return $log;
        });

        // ✅ Weekly bar chart data for dashboard
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $views = TreeView::select(
            DB::raw('DAYNAME(view_date) as day'),
            DB::raw('COUNT(*) as total')
        )
            ->whereBetween('view_date', [$startOfWeek, $endOfWeek])
            ->groupBy('day')
            ->get()
            ->pluck('total', 'day');

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $weeklyViews = [];
        foreach ($days as $day) {
            $weeklyViews[] = ['x' => substr($day, 0, 3), 'y' => $views[$day] ?? 0];
        }

        $totalViews    = collect($weeklyViews)->sum('y');
        $perDayAverage = $totalViews ? round($totalViews / 7) : 0;

        // ✅ Monthly/yearly needed by the JS (even if not shown on dashboard)
        $monthlyData  = [];
        $yearlyData   = [];
        $monthlyTotal = 0;
        $yearlyTotal  = 0;

        $activeUsers = User::where('status', 'active')
            ->orderBy('created_at', 'asc')
            ->get();
        return view("auth.dashboard", compact(
            'trees',
            'activityLogs',
            'treesCount',
            'locationCount',
            'archiveTreesCount',
            'weeklyViews',
            'totalViews',
            'perDayAverage',
            'monthlyData',
            'yearlyData',
            'monthlyTotal',
            'yearlyTotal',
            'totalWebsiteViews',
            'activeUsers'
        ));
    }

    public function trees()
    {
        return view("auth.trees");
    }
    public function locations()
    {
        return view("auth.locations");
    }

    public function about()
    {
        return view("auth.about");
    }
    // public function activity()
    // {
    //     $activityLogs = ActivityLog::with(['type', 'user'])
    //         ->latest()
    //         ->paginate(10);

    //     // Map subject models for each log entry
    //     $activityLogs->getCollection()->transform(function ($log) {
    //         $subjectClass = $log->subject_type;

    //         if (in_array($log->type_id, [1, 2]) && class_exists($subjectClass)) {
    //             $log->subjectModel = $subjectClass::find($log->subject_id);
    //         } else {
    //             $log->subjectModel = null;
    //         }

    //         return $log;
    //     });

    //     return view('auth.activity-log', compact('activityLogs'));
    // }

    public function activity(Request $request)
    {
        $query = ActivityLog::with(['type', 'user'])->latest();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Search in the event description
                $q->where('event', 'like', "%{$search}%")

                    // Search in the User who performed the action
                    ->orWhereHas('user', function ($q2) use ($search) {
                        // Adjust 'name' if your User table uses 'first_name' or 'full_name'
                        $q2->where(User::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
                    })

                    // Search in Tree names (if subject is a Tree)
                    ->orWhereExists(function ($query) use ($search) {
                        $query->select(Tree::raw(1))
                            ->from('trees')
                            ->whereRaw('trees.id = activity_logs.subject_id')
                            ->where('trees.name', 'like', "%{$search}%");
                    })

                    // Search in Location names (if subject is a Location)
                    ->orWhereExists(function ($query) use ($search) {
                        $query->select(Location::raw(1))
                            ->from('locations')
                            ->whereRaw('locations.id = activity_logs.subject_id')
                            ->where('locations.name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'Access') {
                $query->where('type_id', 3);
            } elseif ($request->status === 'Resource') {
                $query->whereIn('type_id', [1, 2]);
            } else {
                $query->whereIn('type_id', [1, 2, 3]);
            }
        }

        // 🔹 Paginate
        $activityLogs = $query->paginate(10, ['*'], 'logs_page')->appends($request->all());

        // 🔹 Transform
        $activityLogs->getCollection()->transform(function ($log) {
            $subjectClass = $log->subject_type;

            if (in_array($log->type_id, [1, 2]) && class_exists($subjectClass)) {
                $log->subjectModel = $subjectClass::find($log->subject_id);
            } else {
                $log->subjectModel = null;
            }

            $log->typeName = in_array($log->type_id, [1, 2])
                ? 'Resource'
                : ($log->type_id == 3 ? 'Access' : 'N/A');

            return $log;
        });

        return view('auth.activity-log', compact('activityLogs'));
    }

    public function analytics()
    {
        $startOfWeek = Carbon::now()->startOfWeek(); // Monday
        $endOfWeek   = Carbon::now()->endOfWeek();   // Sunday

        $views = TreeView::select(
            DB::raw('DAYNAME(view_date) as day'),
            DB::raw('COUNT(*) as total')
        )
            ->whereBetween('view_date', [$startOfWeek, $endOfWeek])
            ->groupBy('day')
            ->get()
            ->pluck('total', 'day');

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $weeklyViews = [];
        foreach ($days as $day) {
            $weeklyViews[] = [
                'x' => substr($day, 0, 3),
                'y' => $views[$day] ?? 0
            ];
        }

        $totalViews   = collect($weeklyViews)->sum('y');
        $perDayAverage = $totalViews ? round($totalViews / 7) : 0;
        // Monthly views
        $monthlyViews = TreeView::select(
            DB::raw('DAY(view_date) as day'),
            DB::raw('COUNT(*) as total')
        )
            ->whereMonth('view_date', Carbon::now()->month)
            ->whereYear('view_date', Carbon::now()->year)
            ->groupBy('day')
            ->get()
            ->pluck('total', 'day');

        $daysInMonth = Carbon::now()->daysInMonth;
        $monthlyData = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $monthlyData[] = [
                'x' => (string) $i,
                'y' => $monthlyViews[$i] ?? 0
            ];
        }
        // Yearly views
        $yearlyViews = TreeView::select(
            DB::raw('MONTH(view_date) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('view_date', Carbon::now()->year)
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month');

        $yearlyData = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        for ($i = 1; $i <= 12; $i++) {
            $yearlyData[] = [
                'x' => $months[$i - 1],
                'y' => $yearlyViews[$i] ?? 0
            ];
        }

        // Stats for the card
        $monthlyTotal = collect($monthlyData)->sum('y');
        $yearlyTotal  = collect($yearlyData)->sum('y');

        // ✅ Total visits
        $totalVisits = TreeView::count();

        // ✅ Visits per day (last 30 days)
        $visitsPerDay = TreeView::select(
            DB::raw('DATE(view_date) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->where('view_date', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // ✅ Visits per tree
        $visitsPerTree = TreeView::select(
            'tree_id',
            DB::raw('COUNT(*) as total')
        )
            ->with('tree:id,name')
            ->groupBy('tree_id')
            ->orderBy('total', 'desc')
            ->get();

        // ✅ Visits per location
        $visitsPerLocation = TreeView::select(
            'location_id',
            DB::raw('COUNT(*) as total')
        )
            ->with('location:id,name')
            ->whereNotNull('location_id')
            ->groupBy('location_id')
            ->orderBy('total', 'desc')
            ->get();

        return view('auth.analytics', compact(
            'weeklyViews',
            'totalViews',
            'perDayAverage',
            'monthlyData',
            'monthlyTotal',
            'yearlyData',
            'yearlyTotal',
            'totalVisits',
            'visitsPerDay',
            'visitsPerTree',
            'visitsPerLocation'
        ));
    }
    public function users()
    {
        $schoolYears = Officer::select('school_year')
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->pluck('school_year');

        $selectedYear = request('year', $schoolYears->first());

        $users = User::join('officers', 'users.officer_id', '=', 'officers.id')
            ->where('officers.school_year', $selectedYear)
            ->select('users.*', 'officers.school_year', 'officers.position as officer_position')
            ->orderBy('users.created_at', 'asc')
            ->get();

        return view('auth.users', compact('users', 'schoolYears', 'selectedYear'));
    }
}
