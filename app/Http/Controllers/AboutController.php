<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreOfficerRequest;
use App\Models\User;
use App\Models\Officer;
use App\Mail\NewUser;

class AboutController extends Controller
{
    // ✅ Shared positions map
    private array $positions = [
        'program_chair'  => 'Program Chair',
        'adviser'        => 'Adviser',
        'president'      => 'President',
        'viceP_internal' => 'Vice President Internal',
        'viceP_external' => 'Vice President External',
        'secretary'      => 'Secretary',
        'treasurer'      => 'Treasurer',
        'auditor'        => 'Auditor',
        'pro'            => 'PRO',
        '1st_rep'        => '1st Year Representative',
        '2nd_rep'        => '2nd Year Representative',
        '3rd_rep'        => '3rd Year Representative',
        '4th_rep'        => '4th Year Representative',
    ];

    // ✅ Shared validation rules for edit forms
    private function officerRules(): array
    {
        $rules = [];
        foreach ($this->positions as $key => $label) {
            $rules["{$key}_firstname"]      = 'required|string|max:255';
            $rules["{$key}_middle_initial"] = 'nullable|string|max:1';
            $rules["{$key}_lastname"]       = 'required|string|max:255';
            $rules["{$key}_image"]          = 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120';
        }
        return $rules;
    }

    // ✅ Shared logic for saving officer changes (edit current + edit previous)
    private function saveOfficerChanges(Request $request, string $school_year, bool $syncUser = false): array
    {
        $changes = [];

        foreach ($this->positions as $key => $positionName) {
            $officer = Officer::where('school_year', $school_year)
                ->where('position', $positionName)
                ->first();

            if (!$officer) continue;

            $newFirstname = $request->input("{$key}_firstname");
            $newMi        = $request->input("{$key}_middle_initial");
            $newLastname  = $request->input("{$key}_lastname");

            $hasChange = $officer->firstname      !== $newFirstname
                || $officer->middle_initial !== $newMi
                || $officer->lastname       !== $newLastname
                || $request->hasFile("{$key}_image");

            if (!$hasChange) continue;

            $officer->firstname      = $newFirstname;
            $officer->middle_initial = $newMi;
            $officer->lastname       = $newLastname;

            if ($request->hasFile("{$key}_image")) {
                if ($officer->image_path && Storage::disk('public')->exists($officer->image_path)) {
                    Storage::disk('public')->delete($officer->image_path);
                }
                $newImagePath        = $request->file("{$key}_image")->store('officers', 'public');
                $officer->image_path = $newImagePath;

                // ✅ Only sync to user's profile_image when editing CURRENT officers
                if ($syncUser) {
                    $linkedUser = User::where('officer_id', $officer->id)->first();
                    if ($linkedUser) {
                        if ($linkedUser->profile_image && Storage::disk('public')->exists($linkedUser->profile_image)) {
                            Storage::disk('public')->delete($linkedUser->profile_image);
                        }
                        $linkedUser->update(['profile_image' => $newImagePath]);
                    }
                }
            }

            $officer->save();
            $changes[] = $positionName;
        }

        return $changes;
    }

    // ✅ Show all officers (current school year)
    public function index()
    {
        $officer = Officer::get();
        return view('auth.about', compact('officer'));
    }

    // ✅ Show create form
    public function create()
    {
        $latestYear = Officer::max('school_year');

        $prevProgramChair = Officer::where('school_year', $latestYear)
            ->where('position', 'Program Chair')->first();

        $prevAdviser = Officer::where('school_year', $latestYear)
            ->where('position', 'Adviser')->first();

        // ✅ Get linked users via officer_id
        $prevPCUser  = $prevProgramChair
            ? User::where('officer_id', $prevProgramChair->id)->first()
            : null;

        $prevAdvUser = $prevAdviser
            ? User::where('officer_id', $prevAdviser->id)->first()
            : null;

        return view('auth.add-new-officers', compact(
            'prevProgramChair',
            'prevAdviser',
            'prevPCUser',
            'prevAdvUser'
        ));
    }

    // ✅ Store new officers
    public function store(StoreOfficerRequest $request)
    {
        $data = $request->validated();

        foreach ($this->positions as $key => $positionName) {

            $retainSame = $data["retain_same_person_{$key}"] ?? false;
            $isSpecial  = in_array($key, ['program_chair', 'adviser']);

            // ✅ Get the most recent previous officer for this position
            $prevOfficer = Officer::where('position', $positionName)
                ->where('school_year', '!=', $data['school_year'])
                ->orderBy('school_year', 'desc')
                ->first();

            // ✅ Get linked user via officer_id — reliable over email
            $prevUser = $prevOfficer
                ? User::where('officer_id', $prevOfficer->id)->first()
                : null;

            // ✅ Resolve image path before creating officer record
            if ($request->hasFile("{$key}_image")) {
                // New image uploaded
                if ($prevOfficer?->image_path && Storage::disk('public')->exists($prevOfficer->image_path)) {
                    Storage::disk('public')->delete($prevOfficer->image_path);
                }
                $imagePath = $request->file("{$key}_image")->store('officers', 'public');
            } elseif ($isSpecial && $retainSame) {
                // ✅ Retained — carry image from user profile first, officer image as fallback
                $imagePath = $prevUser?->profile_image
                    ?? $prevOfficer?->image_path
                    ?? null;
            } else {
                $imagePath = null;
            }

            Log::info("STORE [{$positionName}]", [
                'retainSame'     => $retainSame,
                'prevOfficer_id' => $prevOfficer?->id,
                'prevUser_id'    => $prevUser?->id,
                'prevUserImg'    => $prevUser?->profile_image,
                'officerImg'     => $prevOfficer?->image_path,
                'resolvedImg'    => $imagePath,
            ]);

            // ✅ Create or update officer record for new school year
            $officer = Officer::updateOrCreate(
                [
                    'position'    => $positionName,
                    'school_year' => $data['school_year'],
                ],
                [
                    'firstname'      => $data["{$key}_firstname"],
                    'middle_initial' => $data["{$key}_middle_initial"] ?? null,
                    'lastname'       => $data["{$key}_lastname"],
                    'email'          => $data["{$key}_email"],
                    'image_path'     => $imagePath,
                ]
            );

            $officer->refresh();

            // ✅ Retained special officer — only update officer_id link + image if new upload
            if ($isSpecial && $retainSame && $prevUser) {
                $updateData = ['officer_id' => $officer->id];

                if ($request->hasFile("{$key}_image")) {
                    if ($prevUser->profile_image && Storage::disk('public')->exists($prevUser->profile_image)) {
                        Storage::disk('public')->delete($prevUser->profile_image);
                    }
                    $updateData['profile_image'] = $imagePath;
                }
                // ✅ No name, email, or password changes — login stays same

                $prevUser->update($updateData);
                Log::info("Retained {$positionName} — user {$prevUser->id} → officer {$officer->id} | img: {$imagePath}");
                continue;
            }

            // ✅ Not retained — deactivate old users for this position
            User::where('position', $positionName)
                ->where('status', 'active')
                ->where('officer_id', '!=', $officer->id)
                ->update(['status' => 'inactive']);

            // ✅ Create new user and email credentials
            $password = Str::random(9);
            $user = User::create([
                'first_name'     => $data["{$key}_firstname"],
                'middle_initial' => $data["{$key}_middle_initial"] ?? null,
                'last_name'      => $data["{$key}_lastname"],
                'position'       => $positionName,
                'email'          => $data["{$key}_email"],
                'profile_image'  => $imagePath,
                'status'         => 'active',
                'officer_id'     => $officer->id,
                'password'       => Hash::make($password),
            ]);

            Mail::to($user->email)->send(new NewUser($user->first_name, $user->email, $password));
            Log::info("Created new user for {$positionName}: {$user->email}");
        }

        return redirect()->route('about.index')->with('success', 'New officers added successfully.');
    }

    // ✅ View previous school year officers
    public function previous_view(Request $request)
    {
        $latestYear = Officer::max('school_year');
        $search     = $request->input('search', '');
        $perPage    = (int) $request->input('per_page', 10);
        $page       = (int) $request->input('page', 1);

        $allYears = Officer::where('school_year', '!=', $latestYear)
            ->when($search, fn($q) => $q->where('school_year', 'like', "%{$search}%"))
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->pluck('school_year');

        $total      = $allYears->count();
        $lastPage   = max(1, (int) ceil($total / $perPage));
        $page       = min($page, $lastPage);
        $pagedYears = $allYears->slice(($page - 1) * $perPage, $perPage);

        $allOfficers    = Officer::whereIn('school_year', $pagedYears)->get()->groupBy('school_year');
        $usersMap       = User::whereIn('officer_id', $allOfficers->flatten()->pluck('id'))->get()->keyBy('officer_id');
        $lastUpdatedMap = Officer::whereIn('school_year', $pagedYears)
            ->selectRaw('school_year, MAX(updated_at) as last_updated')
            ->groupBy('school_year')
            ->pluck('last_updated', 'school_year');

        return view('auth.history-prev-sy-officers', compact(
            'allOfficers',
            'pagedYears',
            'usersMap',
            'search',
            'perPage',
            'page',
            'total',
            'lastPage',
            'lastUpdatedMap'
        ));
    }

    // ✅ Edit previous officers (show form)
    public function previous_edit(string $school_year)
    {
        $officers = Officer::where('school_year', $school_year)
            ->orderByRaw("FIELD(position,
                'Program Chair', 'Adviser', 'President',
                'Vice President Internal', 'Vice President External',
                'Secretary', 'Treasurer', 'Auditor', 'PRO',
                '1st Year Representative', '2nd Year Representative',
                '3rd Year Representative', '4th Year Representative'
            )")
            ->get();

        $usersMap = User::whereIn('officer_id', $officers->pluck('id'))
            ->get()
            ->keyBy('officer_id');

        return view('auth.edit-prev-officers', compact('officers', 'school_year', 'usersMap'));
    }

    // ✅ Edit current officers (show form)
    public function edit_current(Request $request)
    {
        $school_year     = Officer::max('school_year');
        $currentOfficers = Officer::where('school_year', $school_year)->get();
        $usersMap        = User::whereIn('officer_id', $currentOfficers->pluck('id'))
            ->get()
            ->keyBy('officer_id');

        return view('auth.edit-current-officers', compact('currentOfficers', 'school_year', 'usersMap'));
    }

    // ✅ Update current officers
    public function update_current(Request $request)
    {
        $request->validate($this->officerRules());
        $changes = $this->saveOfficerChanges($request, $request->input('school_year'), true); // ✅ true

        if (empty($changes)) {
            return redirect()->back()->with('error', 'No changes detected.')->withInput();
        }

        logActivity('Updated current officers: ' . implode(', ', $changes), 'updated', 'officer', null);

        return redirect()->route('about.index')
            ->with('success', 'Officers updated. Changes are now visible on the About page.');
    }

    // ✅ Previous officers — do NOT sync user profile_image
    public function previous_edit_update(Request $request, string $school_year)
    {
        $request->validate($this->officerRules());
        $changes = $this->saveOfficerChanges($request, $school_year, false); // ✅ false

        if (empty($changes)) {
            return redirect()->back()->with('error', 'No changes detected.')->withInput();
        }

        logActivity("Updated previous officers ({$school_year}): " . implode(', ', $changes), 'updated', 'officer', null);

        return redirect()->route('about.previous_view')
            ->with('success', "Officers for {$school_year} updated successfully.");
    }
}
