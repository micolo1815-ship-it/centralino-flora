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

class GenerateReportController extends Controller
{
    // Method 1: just display the form
    public function generateReport()
    {
        $trees         = Tree::select('id', 'name', 'status')->orderBy('name')->get();
        $locations     = Location::select('id', 'name', 'status')->orderBy('name')->get();
        $activityTypes = ActivityType::select('id', 'name')->orderBy('name')->get();
        $schoolYears   = Officer::select('school_year')->distinct()->orderBy('school_year', 'desc')->pluck('school_year');

        $users = User::leftJoin('officers', 'users.officer_id', '=', 'officers.id')
            ->select('users.id', 'users.first_name', 'users.last_name', 'users.status', 'officers.school_year')
            ->orderBy('users.first_name')->get();

        $treesData     = $trees->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'status' => $t->status]);
        $locationsData = $locations->map(fn($l) => ['id' => $l->id, 'name' => $l->name, 'status' => $l->status]);
        $usersData     = $users->map(fn($u) => [
            'id' => $u->id,
            'name' => $u->first_name . ' ' . $u->last_name,
            'status' => $u->status,
            'school_year' => $u->school_year,
        ]);

        $treesWithVisits     = Tree::select('id', 'name')->withCount('views')->orderBy('name')->get();
        $locationsWithVisits = Location::select('id', 'name')->withCount('views')->orderBy('name')->get();

        return view('auth.generate-report', compact(
            'trees',
            'locations',
            'users',
            'activityTypes',
            'schoolYears',
            'treesData',
            'locationsData',
            'usersData',
            'treesWithVisits',
            'locationsWithVisits'
        ));
    }

    // Method 2: handle the form submission and generate
    public function display(Request $request)
    {
        $type      = $request->report_type;
        $startDate = $request->start_date;
        $endDate   = $request->end_date;
        $status    = $request->status;
        $items     = $request->filter_items;

        // ✅ Guard — return early if no type
        if (!$type) {
            return response()->json(['type' => null, 'data' => [], 'message' => 'No report type selected.'], 400);
        }

        $data = match ($type) {
            'trees' => Tree::query()
                ->when($status && $status !== 'all', fn($q) => $q->where('status', $status))
                ->when($items, fn($q) => $q->whereIn('id', $items))
                ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                ->when($endDate,   fn($q) => $q->whereDate('created_at', '<=', $endDate))
                ->orderBy('name')
                ->get(['id', 'name', 'status', 'scientific_name', 'common_name', 'created_at']),

            'locations' => Location::query()
                ->with('trees:id,name')
                ->when($status && $status !== 'all', fn($q) => $q->where('status', $status))
                ->when($items,     fn($q) => $q->whereIn('id', $items))
                ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                ->when($endDate,   fn($q) => $q->whereDate('created_at', '<=', $endDate))
                ->orderBy('name')
                ->get(['id', 'name', 'status', 'image', 'created_at']),

            'users' => User::query()
                ->leftJoin('officers', 'users.officer_id', '=', 'officers.id')
                ->when($status && $status !== 'all', fn($q) => $q->where('users.status', $status))
                ->when($items, fn($q) => $q->whereIn('users.id', $items))
                ->when($request->filter_position, fn($q) => $q->whereIn('users.position', $request->filter_position))
                ->when($request->filter_school_years, fn($q) => $q->whereIn('officers.school_year', $request->filter_school_years)) // ✅
                ->when($startDate, fn($q) => $q->whereDate('users.created_at', '>=', $startDate))
                ->when($endDate,   fn($q) => $q->whereDate('users.created_at', '<=', $endDate))
                ->orderBy('users.first_name')->orderBy('users.last_name')
                ->get([
                    'users.id',
                    'users.first_name',
                    'users.middle_initial',
                    'users.last_name',
                    'users.email',
                    'users.position',
                    'users.status',
                    'users.created_at',
                    'officers.school_year',
                    'officers.image_path'
                ]),

            'activity-log' => ActivityLog::with('user')
                ->when($request->filter_activity_user, fn($q) => $q->where('user_id', $request->filter_activity_user))
                ->when($request->filter_activity_type, fn($q) => $q->whereIn('type_id', $request->filter_activity_type))
                ->when($request->filter_activity_event, fn($q) => $q->whereIn('event', $request->filter_activity_event)) // ✅ handles updated & login
                ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                ->when($endDate,   fn($q) => $q->whereDate('created_at', '<=', $endDate))
                ->orderBy('created_at', 'desc')
                ->get(),

            'analytics' => TreeView::query()
                ->with(['tree:id,name', 'location:id,name'])
                ->when($items, fn($q) => $q->whereIn('tree_id', $items))
                ->when($request->filter_location, fn($q) => $q->whereIn('location_id', $request->filter_location))
                ->when($startDate, fn($q) => $q->whereDate('view_date', '>=', $startDate))
                ->when($endDate,   fn($q) => $q->whereDate('view_date', '<=', $endDate))
                ->select('tree_id', 'location_id', DB::raw('COUNT(*) as total'), DB::raw('MAX(view_date) as last_visit'))
                ->groupBy('tree_id', 'location_id')
                ->orderBy('total', 'desc')
                ->get(),
            default => collect()
        };

        return response()->json([
            'type' => $type,
            'data' => $data
        ]);
    }
    public function downloadPdf(Request $request)
    {
        $type      = $request->report_type;
        $startDate = $request->start_date;
        $endDate   = $request->end_date;
        $status    = $request->status;
        $items     = $request->filter_items;

        // ✅ Reuse same query logic as display()
        $data = match ($type) {
            'trees' => Tree::query()
                ->when($status && $status !== 'all', fn($q) => $q->where('status', $status))
                ->when($items,     fn($q) => $q->whereIn('id', $items))
                ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                ->when($endDate,   fn($q) => $q->whereDate('created_at', '<=', $endDate))
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                    'status',
                    'scientific_name',
                    'common_name',
                    'local_name',
                    'description',
                    'uses_filipino',
                    'tree_facts',
                    'tagged_trees',
                    'domain',
                    'kingdom',
                    'phylum',
                    'class',
                    'order',
                    'family',
                    'genus',
                    'species',
                    'cover_image',
                    'created_at'
                    // ✅ image_gallery excluded
                ]),

            'locations' => Location::query()
                ->with('trees:id,name')
                ->when($status && $status !== 'all', fn($q) => $q->where('status', $status))
                ->when($items,     fn($q) => $q->whereIn('id', $items))
                ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                ->when($endDate,   fn($q) => $q->whereDate('created_at', '<=', $endDate))
                ->orderBy('name')
                ->get(['id', 'name', 'status', 'image', 'created_at']),

            'users' => User::leftJoin('officers', 'users.officer_id', '=', 'officers.id')
                ->when($status && $status !== 'all', fn($q) => $q->where('users.status', $status))
                ->when($items,                       fn($q) => $q->whereIn('users.id', $items))
                ->when($request->filter_position,    fn($q) => $q->whereIn('users.position', $request->filter_position))
                ->when($request->filter_school_years, fn($q) => $q->whereIn('officers.school_year', $request->filter_school_years))
                ->when($startDate, fn($q) => $q->whereDate('users.created_at', '>=', $startDate))
                ->when($endDate,   fn($q) => $q->whereDate('users.created_at', '<=', $endDate))
                ->orderBy('users.first_name')
                ->get(['users.id', 'users.first_name', 'users.middle_initial', 'users.last_name', 'users.email', 'users.position', 'users.status', 'users.created_at', 'officers.school_year']),

            'activity-log' => ActivityLog::with('user')
                ->when($request->filter_activity_user,  fn($q) => $q->where('user_id', $request->filter_activity_user))
                ->when($request->filter_activity_type,  fn($q) => $q->whereIn('type_id', $request->filter_activity_type))
                ->when($request->filter_activity_event, fn($q) => $q->whereIn('event', $request->filter_activity_event))
                ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                ->when($endDate,   fn($q) => $q->whereDate('created_at', '<=', $endDate))
                ->orderBy('created_at', 'desc')
                ->get(),

            'analytics' => TreeView::query()
                ->with(['tree:id,name', 'location:id,name'])
                ->when($items,                    fn($q) => $q->whereIn('tree_id', $items))
                ->when($request->filter_location, fn($q) => $q->whereIn('location_id', $request->filter_location))
                ->when($startDate, fn($q) => $q->whereDate('view_date', '>=', $startDate))
                ->when($endDate,   fn($q) => $q->whereDate('view_date', '<=', $endDate))
                ->select('tree_id', 'location_id', DB::raw('COUNT(*) as total'), DB::raw('MAX(view_date) as last_visit'))
                ->groupBy('tree_id', 'location_id')
                ->orderBy('total', 'desc')
                ->get(),

            default => collect()
        };

        // ✅ Convert cover images to base64 for PDF
        if ($type === 'trees') {
            $data = $data->map(function ($tree) {
                $tree->cover_base64 = $tree->cover_image
                    ? $this->imageToBase64(storage_path('app/public/' . $tree->cover_image))
                    : null;
                return $tree;
            });
        }
        if ($type === 'locations') {
            $data = $data->map(function ($location) {
                $location->cover_base64 = $location->image
                    ? $this->imageToBase64(storage_path('app/public/' . $location->image))
                    : null;
                return $location;
            });
        }

        $orientation = $type === 'trees' ? 'portrait' : 'landscape';

        $pdf = Pdf::loadView('exports.report-pdf', [
            'type'        => $type,
            'data'        => $data,
            'startDate'   => $startDate,
            'endDate'     => $endDate,
            'generatedAt' => now()->format('F d, Y h:i A'),
        ])->setPaper('a4', $orientation);

        return $pdf->download('report-' . $type . '-' . now()->format('Ymd') . '.pdf');
    }
    private function imageToBase64(string $path): ?string
    {
        if (!file_exists($path)) return null;

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        try {
            if (in_array($ext, ['jpg', 'jpeg'])) {
                $image = imagecreatefromjpeg($path);
            } elseif ($ext === 'png') {
                $image = imagecreatefrompng($path);
            } elseif ($ext === 'webp') {
                if (function_exists('imagecreatefromwebp')) {
                    $image = imagecreatefromwebp($path);
                } else {
                    // ✅ GD webp not available — return raw base64 as jpeg workaround
                    // Just skip the image gracefully
                    return null;
                }
            } else {
                return null;
            }

            if (!$image) return null;

            // ✅ Convert to JPEG (smaller, faster for PDF)
            ob_start();
            imagejpeg($image, null, 85);
            $data = ob_get_clean();
            imagedestroy($image);

            return 'data:image/jpeg;base64,' . base64_encode($data);
        } catch (\Exception $e) {
            return null;
        }
    }
}
