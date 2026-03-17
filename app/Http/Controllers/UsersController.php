<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Officer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\NewUser;
use App\Mail\AccountStatusMail;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $schoolYears = Officer::select('school_year')
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->pluck('school_year');

        $selectedYear = $request->input('year', $schoolYears->first());
        $status       = $request->input('status', '');
        $search       = $request->input('search', '');

        $officers = Officer::with('user')
            ->when($status, function ($q) use ($status, $selectedYear) {
                // ✅ When filtering by status — show ALL years, not just selected year
                $actualStatus = $status === 'Activated' ? 'active' : 'inactive';
                $q->whereHas('user', fn($q2) => $q2->where('status', $actualStatus));
            }, function ($q) use ($selectedYear) {
                // ✅ No status filter — show only selected year
                $q->where('school_year', $selectedYear);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('firstname', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('school_year', 'desc')
            ->orderBy('id', 'asc')
            ->paginate($request->input('per_page', 10))
            ->appends($request->all());

        return view('auth.users', compact('officers', 'schoolYears', 'selectedYear', 'status', 'search'));
    }
    public function edit(User $user)
    {
        return view('auth.users-edit-users', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name'     => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:1',
            'last_name'      => 'required|string|max:255',
            'status'         => 'required|in:active,inactive',
            'email'          => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($user, $request) {
                    $isActivating = $user->status === 'inactive' && $request->status === 'active';
                    $emailChanged = $value !== $user->email;

                    // ✅ Check duplicate if:
                    // 1. Email is being changed, OR
                    // 2. Account is being activated (even with same email — another active user may have taken it)
                    if ($emailChanged || $isActivating) {
                        $duplicate = User::where('email', $value)
                            ->where('status', 'active')
                            ->where('id', '!=', $user->id)
                            ->exists();

                        if ($duplicate) {
                            if ($isActivating && !$emailChanged) {
                                $fail('Cannot activate this account. The email ' . $value . ' is already used by another active user. Please change the email first before activating.');
                            } else {
                                $fail('This email is already used by an active user.');
                            }
                        }
                    }
                }
            ],
        ]);

        // ✅ Detect changes
        $changes       = [];
        $emailChanged  = $user->email !== $request->email;

        if ($user->first_name     !== $request->first_name)     $changes[] = 'First name';
        if ($user->middle_initial !== $request->middle_initial) $changes[] = 'Middle initial';
        if ($user->last_name      !== $request->last_name)      $changes[] = 'Last name';
        if ($emailChanged)                                       $changes[] = 'Email';
        if ($user->status         !== $request->status)         $changes[] = 'Status';
        if ($request->hasFile('profile_image'))                  $changes[] = 'Profile image';

        // ✅ Block if no changes
        if (empty($changes)) {
            return redirect()->back()
                ->withErrors(['error' => 'No changes detected. Please modify at least one field.'])
                ->withInput();
        }

        // ✅ Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $user->profile_image = $request->file('profile_image')->store('uploads', 'public');
        }

        $user->first_name     = $request->first_name;
        $user->middle_initial = $request->middle_initial;
        $user->last_name      = $request->last_name;
        $user->email          = $request->email;
        $user->status         = $request->status;
        $user->save();

        // ✅ Send email when status changes
        if (in_array('Status', $changes)) {
            if ($user->status === 'active') {
                // ✅ Account activated email
                Mail::to($user->email)->send(new AccountStatusMail(
                    $user->first_name,
                    $user->email,
                    'activated'
                ));
            } else {
                // ✅ Account deactivated email
                Mail::to($user->email)->send(new AccountStatusMail(
                    $user->first_name,
                    $user->email,
                    'deactivated'
                ));
            }
        }

        logActivity(
            'Edited: ' . implode(', ', $changes) . ' for ' . $user->first_name . ' ' . $user->last_name,
            'updated',
            'user',
            $user
        );

        return redirect()->route('users.index')->with(
            'success',
            'User updated successfully.' .
                ($emailChanged ? ' New login credentials have been sent to ' . $user->email . '.' : '')
        );
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        logActivity('Reset password for: ' . $user->first_name . ' ' . $user->last_name, 'updated', 'user', $user);

        return redirect()->route('users.index')->with('success', 'Password updated successfully.');
    }
}
