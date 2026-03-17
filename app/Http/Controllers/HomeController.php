<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Tree;
use App\Models\TreeView;
use App\Models\Officer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Home page
    public function index()
    {
        $trees = Tree::where('status', 'active')
            ->orderBy('name')
            ->get();

        $locations = Location::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('index', compact('trees', 'locations'));
    }

    public function abouts()
    {
        $locations = Location::where('status', 'active')
            ->orderBy('name')
            ->get();

        $school_year = Officer::max('school_year');

        $currentOfficers = Officer::where('school_year', $school_year)
            ->orderByRaw("FIELD(position,
            'Program Chair', 'Adviser', 'President',
            'Vice President Internal', 'Vice President External',
            'Secretary', 'Treasurer', 'Auditor', 'PRO',
            '1st Year Representative', '2nd Year Representative',
            '3rd Year Representative', '4th Year Representative'
        )")
            ->get();

        $usersMap = User::whereIn('officer_id', $currentOfficers->pluck('id'))
            ->get()
            ->keyBy('officer_id');

        return view('about', compact('locations', 'currentOfficers', 'school_year', 'usersMap'));
    }

    public function historical_officers()
    {
        $locations = Location::where('status', 'active')->orderBy('name')->get();

        $latestYear = Officer::max('school_year');

        // ✅ Get all years EXCEPT the current/latest, grouped
        $previousOfficers = Officer::where('school_year', '!=', $latestYear)
            ->orderByRaw("FIELD(position,
            'Program Chair', 'Adviser', 'President',
            'Vice President Internal', 'Vice President External',
            'Secretary', 'Treasurer', 'Auditor', 'PRO',
            '1st Year Representative', '2nd Year Representative',
            '3rd Year Representative', '4th Year Representative'
        )")
            ->get()
            ->groupBy('school_year')
            ->sortKeysDesc(); // ✅ Latest year first

        // ✅ Grab user images for all officers
        $usersMap = User::whereIn('officer_id', $previousOfficers->flatten()->pluck('id'))
            ->get()
            ->keyBy('officer_id');

        return view('history-sy-officers', compact('locations', 'previousOfficers', 'usersMap'));
    }

    // Forest list page
    public function show()
    {
        $locations = Location::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('forestry.forestrylist', compact('locations'));
    }

    public function location($locationId)
    {
        $location = Location::with(['trees' => function ($query) {
            $query->wherePivot('status', 1)->orderBy('name', 'asc');
        }])
            ->where('status', 'active')
            ->findOrFail($locationId);

        $locations = Location::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('forestry.location', compact('location', 'locations'));
    }


    // Single tree page
    public function tree($locationId, $treeId)
    {
        $location = Location::findOrFail($locationId);
        $tree = Tree::findOrFail($treeId);
        $locations = Location::where('status', 'active')->orderBy('name')->get();

        abort_unless($location->trees->contains($tree), 404);

        $today = Carbon::today();

        $alreadyViewed = TreeView::where([
            'tree_id' => $tree->id,
            'location_id' => $location->id,
            'ip_address' => request()->ip(),
            'view_date' => $today,
        ])->exists();

        if (!$alreadyViewed) {
            TreeView::create([
                'tree_id' => $tree->id,
                'location_id' => $location->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'view_date' => $today,
            ]);

            // increment quick counter
            $location->trees()
                ->where('tree_id', $tree->id)
                ->increment('view_count');
        }

        return view('forestry.puno', compact('locations', 'location', 'tree'));
    }
    public function trees($treeId)
    {
        $tree = Tree::findOrFail($treeId);
        $locations = Location::where('status', 'active')->orderBy('name')->get();

        $today = Carbon::today();

        $alreadyViewed = TreeView::where([
            'tree_id' => $tree->id,
            'ip_address' => request()->ip(),
            'view_date' => $today,
        ])->exists();

        if (!$alreadyViewed) {
            TreeView::create([
                'tree_id' => $tree->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'view_date' => $today,
            ]);
        }

        return view('tree', compact('locations', 'tree'));
    }
}
