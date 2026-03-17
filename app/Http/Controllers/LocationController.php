<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Tree;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'active'); // ✅ default to active

        $locations = Location::with(['createdBy', 'updatedBy'])
            ->when($status && $status !== 'all', fn($q) => $q->where('status', $status))
            ->orderBy('name')
            ->latest()
            ->paginate(10);

        return view('auth.locations', compact('locations', 'status'));
    }

    // public function home()
    // {
    //     // Get only active locations
    //     $locations = Location::where('status', 'active')
    //         ->orderBy('name')
    //         ->get();

    //     return view('forestry.forestrylist', compact('locations'));
    // }

    public function create()
    {
        $trees = Tree::orderBy('name')->get();
        return view('auth.add-location', compact('trees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'location_name' => 'required|string|max:255',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'trees'         => 'nullable|array',
            'trees.*'       => 'exists:trees,id',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('locations', 'public');
        }

        $location = Location::create([
            'name'       => $request->location_name,
            'status'     => 'active',           // <-- forced server-side
            'image'      => $imagePath,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        if ($request->has('trees')) {
            $syncData = collect($request->trees)->mapWithKeys(function ($id) {
                return [$id => ['status' => 1]];
            })->toArray();
            $location->trees()->syncWithoutDetaching($syncData);
        }

        // Log activity with location name and additional details
        logActivity(
            'New location added: ' . $location->name,
            'created',
            'location',
            $location
        );

        return redirect()->route('location.index')->with('success', 'Location added successfully!');
    }

    public function edit(Location $location)
    {
        $trees = Tree::orderBy('name')->get();

        // ✅ Only show active pivot trees as selected
        $selectedTrees = $location->trees()
            ->wherePivot('status', 1)
            ->pluck('trees.id')
            ->toArray();

        return view('auth.edit-location', compact('location', 'trees', 'selectedTrees'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'location_name' => 'required|string|max:255',
            'status'        => 'required|in:active,archive',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'trees'         => 'nullable|array',
            'trees.*'       => 'exists:trees,id',
            'remove_image'  => 'nullable|boolean',
        ]);

        $changes = [];

        // Detect name change
        $oldName = trim($location->name ?? '');
        $newName = trim($validated['location_name'] ?? '');
        if ($oldName !== $newName) {
            $changes[] = "Location name from '{$oldName}' to '{$newName}'";
        }

        // Detect status change
        $oldStatus = $location->status ?? '';
        $newStatus = $validated['status'] ?? '';
        if ($oldStatus !== $newStatus) {
            $changes[] = "Status to '{$newStatus}'";
        }

        // ✅ Detect image change using boolean()
        $imageChanged = $request->hasFile('image') || $request->boolean('remove_image');
        if ($imageChanged) {
            $changes[] = 'Cover image';
        }

        // Detect trees change — added, removed, or reactivated
        $newTrees      = $request->input('trees', []);
        $currentTrees  = $location->trees()->wherePivot('status', 1)->pluck('trees.id')->toArray();
        $inactiveTrees = $location->trees()->wherePivot('status', 0)->pluck('trees.id')->toArray();

        $added       = array_diff($newTrees, $currentTrees);
        $removed     = array_diff($currentTrees, $newTrees);
        $reactivated = array_intersect($newTrees, $inactiveTrees);

        $treesChanged = !empty($added) || !empty($removed) || !empty($reactivated);
        if ($treesChanged) {
            $changes[] = 'Associated trees';
        }

        // No changes guard
        if (empty($changes)) {
            return redirect()->back()
                ->withErrors(['error' => 'No changes detected. Please modify at least one field, image, or tree association to update.'])
                ->withInput();
        }

        // ✅ Handle image using boolean() — image only removed if explicitly checked
        $imagePath = $location->image;
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('locations', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = null;
        }

        // Update location
        $location->update([
            'name'       => $validated['location_name'],
            'status'     => $validated['status'],
            'image'      => $imagePath,
            'updated_by' => Auth::id(),
        ]);

        // ✅ No deletion — removed trees set to 0 (Inactive)
        $removedTrees = array_diff($currentTrees, $newTrees);
        foreach ($removedTrees as $treeId) {
            $location->trees()->updateExistingPivot($treeId, ['status' => 0]);
        }

        // ✅ New or kept or reactivated trees — syncWithoutDetaching so no rows deleted
        if (!empty($newTrees)) {
            $syncData = collect($newTrees)->mapWithKeys(function ($id) {
                return [$id => ['status' => 1]];
            })->toArray();
            $location->trees()->syncWithoutDetaching($syncData);
        }

        // Log activity
        logActivity(
            'Edited: ' . implode(', ', $changes),
            'updated',
            'location',
            $location
        );

        return redirect()->route('location.index')->with('success', 'Location updated successfully!');
    }
}
