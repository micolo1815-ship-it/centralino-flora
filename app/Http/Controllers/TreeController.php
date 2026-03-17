<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use App\Models\Location;
use App\Models\Tree;

class TreeController extends Controller
{
    // ✅ Suggested limits:
    // Cover image  → max 1MB  (1024KB) — single hero image, should be high quality but compressed
    // Gallery images → max 800KB each  — multiple images, keep total size manageable

    public function index()
    {
        $trees = Tree::with(['locations', 'createdBy', 'updatedBy'])->get();
        return view('auth.trees', compact('trees'));
    }

    public function create()
    {
        $locations = Location::query()
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);
        return view('auth.add-tree', compact('locations'));
    }

    public function edit(Tree $tree)
    {
        $locations = Location::query()
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        // ✅ Only show Active pivot locations as pre-selected
        $selectedLocations = $tree->locations()
            ->wherePivot('status', 1)
            ->pluck('locations.id')
            ->toArray();

        return view('auth.edit-tree', compact('tree', 'locations', 'selectedLocations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'scientific_name' => 'nullable|string|max:255',
            'common_name'     => 'nullable|string|max:255',
            'local_name'      => 'nullable|string|max:255',
            'location_id'     => 'nullable|array',
            'location_id.*'   => 'exists:locations,id',
            'description'     => 'nullable|string',
            'uses_filipino'   => 'nullable|string',
            'tree_facts'      => 'nullable|string',
            'tagged_trees'    => 'nullable|string',
            'domain'          => 'nullable|string|max:255',
            'kingdom'         => 'nullable|string|max:255',
            'phylum'          => 'nullable|string|max:255',
            'class'           => 'nullable|string|max:255',
            'order'           => 'nullable|string|max:255',
            'family'          => 'nullable|string|max:255',
            'genus'           => 'nullable|string|max:255',
            'species'         => 'nullable|string|max:255',
            'cover_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',  // ✅ 1MB
            'image_gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',   // ✅ 800KB
        ], [
            'cover_image.max'     => 'Cover image must not exceed 5MB.',
            'image_gallery.*.max' => 'Each gallery image must not exceed 5MB.',
        ]);

        // Cover Image
        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('trees/cover', 'public');
        }

        // Gallery
        $galleryPaths = [];
        if ($request->hasFile('image_gallery')) {
            foreach ($request->file('image_gallery') as $file) {
                $galleryPaths[] = $file->store('trees/gallery', 'public');
            }
        }

        // Create Tree
        $tree = Tree::create([
            'name'          => $validated['name'],
            'scientific_name' => $validated['scientific_name'] ?? null,
            'common_name'   => $validated['common_name'] ?? null,
            'local_name'    => $validated['local_name'] ?? null,
            'description'   => $this->cleanHtml($validated['description'] ?? null),
            'uses_filipino' => $this->cleanHtml($validated['uses_filipino'] ?? null),
            'tree_facts'    => $validated['tree_facts'] ?? null,
            'tagged_trees'  => $validated['tagged_trees'] ?? null,
            'domain'        => $validated['domain'] ?? null,
            'kingdom'       => $validated['kingdom'] ?? null,
            'phylum'        => $validated['phylum'] ?? null,
            'class'         => $validated['class'] ?? null,
            'order'         => $validated['order'] ?? null,
            'family'        => $validated['family'] ?? null,
            'genus'         => $validated['genus'] ?? null,
            'species'       => $validated['species'] ?? null,
            'status'        => 'active',
            'cover_image'   => $coverImagePath,
            'image_gallery' => json_encode($galleryPaths),
            'created_by'    => Auth::id(),
            'updated_by'    => Auth::id(),
        ]);

        // ✅ Sync locations with Active status — no deletion, no duplicate attach
        $locationIds = $request->input('location_id', []);
        if (!empty($locationIds)) {
            $syncData = collect($locationIds)->mapWithKeys(function ($id) {
                return [$id => ['status' => 1]];
            })->toArray();
            $tree->locations()->syncWithoutDetaching($syncData);
        }

        logActivity(
            'New tree added: ' . $tree->name,
            'created',
            'tree',
            $tree
        );

        return redirect()->route('tree.index')->with('success', 'Tree added successfully!');
    }

    private function cleanHtml(?string $html): ?string
    {
        if (!$html) return null;

        // ✅ Remove empty tags Quill adds: <p><br></p>, <p></p>, <p> </p>
        $html = preg_replace('/<p>(\s|&nbsp;|<br\s*\/?>)*<\/p>/i', '', $html);

        // ✅ Strip all tags except allowed formatting tags
        $html = strip_tags($html, '<p><br><strong><em><u><s><sub><sup><ol><ul><li><span>');

        // ✅ Trim whitespace
        $html = trim($html);

        return $html === '' ? null : $html;
    }

    public function update(Request $request, Tree $tree)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'scientific_name' => 'nullable|string|max:255',
            'common_name'     => 'nullable|string|max:255',
            'local_name'      => 'nullable|string|max:255',
            'location_id'     => 'nullable|array',
            'location_id.*'   => 'exists:locations,id',
            'description'     => 'nullable|string',
            'uses_filipino'   => 'nullable|string',
            'tree_facts'      => 'nullable|string',
            'tagged_trees'    => 'nullable|string',
            'domain'          => 'nullable|string|max:255',
            'kingdom'         => 'nullable|string|max:255',
            'phylum'          => 'nullable|string|max:255',
            'class'           => 'nullable|string|max:255',
            'order'           => 'nullable|string|max:255',
            'family'          => 'nullable|string|max:255',
            'genus'           => 'nullable|string|max:255',
            'species'         => 'nullable|string|max:255',
            'cover_image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',  // ✅ 1MB
            'image_gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',   // ✅ 800KB
            'status'          => 'required|string|in:active,archive',
            'remove_gallery'  => 'nullable|array',
            'remove_cover'    => 'nullable|boolean',
        ], [
            'cover_image.max'     => 'Cover image must not exceed 5MB.',
            'image_gallery.*.max' => 'Each gallery image must not exceed 5MB.',
        ]);

        $fieldNames = [
            'name'           => 'Name',
            'scientific_name' => 'Scientific name',
            'common_name'    => 'Common name',
            'local_name'     => 'Local name',
            'description'    => 'Description',
            'uses_filipino'  => 'Filipino Folklore',
            'tree_facts'     => 'Tree facts',
            'tagged_trees'   => 'Tagged trees',
            'domain'         => 'Domain',
            'kingdom'        => 'Kingdom',
            'phylum'         => 'Phylum',
            'class'          => 'Class',
            'order'          => 'Order',
            'family'         => 'Family',
            'genus'          => 'Genus',
            'species'        => 'Species',
            'status'         => 'Status',
        ];

        $changes = [];

        // Detect field changes
        foreach ($fieldNames as $field => $displayName) {
            $oldValue = $tree->$field ?? '';

            // ✅ Clean HTML fields before comparing so formatting changes are detected
            if (in_array($field, ['description', 'uses_filipino'])) {
                $newValue = $this->cleanHtml($validated[$field] ?? null) ?? '';
            } else {
                $newValue = $validated[$field] ?? '';
            }

            if ($oldValue != $newValue) {
                $changes[] = $displayName;
            }
        }

        // ✅ Detect cover image changes using boolean()
        $coverChanged = $request->hasFile('cover_image') || $request->boolean('remove_cover');
        if ($coverChanged) {
            $changes[] = 'Cover image';
        }

        // Detect gallery image changes
        $gallery = is_array($tree->image_gallery)
            ? $tree->image_gallery
            : json_decode($tree->image_gallery, true) ?? [];
        $galleryChanged = false;

        // Check for removals
        if ($request->filled('remove_gallery')) {
            foreach ($request->remove_gallery as $removeImg) {
                if (($key = array_search($removeImg, $gallery)) !== false) {
                    if (Storage::disk('public')->exists($removeImg)) {
                        Storage::disk('public')->delete($removeImg);
                    }
                    unset($gallery[$key]);
                    $galleryChanged = true;
                }
            }
        }

        // Check for additions
        if ($request->hasFile('image_gallery')) {
            foreach ($request->file('image_gallery') as $file) {
                if ($file) {
                    $gallery[] = $file->store('trees/gallery', 'public');
                    $galleryChanged = true;
                }
            }
        }

        if ($galleryChanged) {
            $changes[] = 'Image gallery';
        }

        // Detect location changes
        $locationIds      = $request->input('location_id', []);
        $currentLocations = $tree->locations()->wherePivot('status', 1)->pluck('locations.id')->toArray();
        $inactiveLocations = $tree->locations()->wherePivot('status', 0)->pluck('locations.id')->toArray();

        $addedLocs       = array_diff($locationIds, $currentLocations);
        $removedLocs     = array_diff($currentLocations, $locationIds);
        $reactivatedLocs = array_intersect($locationIds, $inactiveLocations);

        $locationChanged = !empty($addedLocs) || !empty($removedLocs) || !empty($reactivatedLocs);
        if ($locationChanged) {
            $changes[] = 'Location';
        }

        // No changes guard
        if (empty($changes)) {
            return redirect()->back()
                ->withErrors(['error' => 'No changes detected. Please modify at least one field, image, or location to update.'])
                ->withInput();
        }

        // ✅ Handle cover image using boolean()
        if ($request->hasFile('cover_image')) {
            if ($tree->cover_image && Storage::disk('public')->exists($tree->cover_image)) {
                Storage::disk('public')->delete($tree->cover_image);
            }
            $tree->cover_image = $request->file('cover_image')->store('trees/cover', 'public');
        } elseif ($request->boolean('remove_cover')) {
            if ($tree->cover_image && Storage::disk('public')->exists($tree->cover_image)) {
                Storage::disk('public')->delete($tree->cover_image);
            }
            $tree->cover_image = null;
        }

        // Update gallery
        $cleanGallery = array_values(array_filter($gallery, fn($item) => is_string($item) && !empty($item)));
        $tree->image_gallery = $cleanGallery;

        // Assign other fields
        $tree->name            = $validated['name'];
        $tree->scientific_name = $validated['scientific_name'] ?? null;
        $tree->common_name     = $validated['common_name'] ?? null;
        $tree->local_name      = $validated['local_name'] ?? null;
        $tree->description   = $this->cleanHtml($validated['description'] ?? null);
        $tree->uses_filipino = $this->cleanHtml($validated['uses_filipino'] ?? null);
        $tree->tree_facts      = $validated['tree_facts'] ?? null;
        $tree->tagged_trees    = $validated['tagged_trees'] ?? null;
        $tree->domain          = $validated['domain'] ?? null;
        $tree->kingdom         = $validated['kingdom'] ?? null;
        $tree->phylum          = $validated['phylum'] ?? null;
        $tree->class           = $validated['class'] ?? null;
        $tree->order           = $validated['order'] ?? null;
        $tree->family          = $validated['family'] ?? null;
        $tree->genus           = $validated['genus'] ?? null;
        $tree->species         = $validated['species'] ?? null;
        $tree->status          = $validated['status'];
        $tree->updated_by      = Auth::id();

        $tree->save();

        // ✅ No deletion — removed locations set to 0 (Inactive)
        foreach ($removedLocs as $locId) {
            $tree->locations()->updateExistingPivot($locId, ['status' => 0]);
        }

        // ✅ New or kept or reactivated locations — syncWithoutDetaching
        if (!empty($locationIds)) {
            $syncData = collect($locationIds)->mapWithKeys(function ($id) {
                return [$id => ['status' => 1]];
            })->toArray();
            $tree->locations()->syncWithoutDetaching($syncData);
        }

        logActivity(
            'Edited: ' . implode(', ', $changes),
            'updated',
            'tree',
            $tree
        );

        return redirect()->route('tree.index')->with('success', 'Tree updated successfully!');
    }
}
