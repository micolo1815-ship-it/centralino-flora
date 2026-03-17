<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view("auth.profile");
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return back()->with('error', 'No user found.');
        }

        $request->validate([
            'first_name'     => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:5',
            'last_name'      => 'required|string|max:255',
            'profile_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            // ✅ Email removed — not editable
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('uploads', 'public');
            $user->profile_image = $path;
        }

        $user->first_name     = $request->first_name;
        $user->middle_initial = $request->middle_initial;
        $user->last_name      = $request->last_name;
        // ✅ Email intentionally not updated

        $user->save();

        logActivity('Updated profile', 'updated', 'user', $user);

        return back()->with('success', 'Profile updated successfully.')->with('active_tab', 'edit');
    }

    public function updatePassword(Request $request)
    {
        // validate using Validator so we can control the "failed" redirect
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:8',
        ]);

        // If validation fails, redirect back with errors, old input, and active_tab
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'password');
        }

        // validation passed — update password
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        // Log activity with location name and additional details
        logActivity(
            'Updated password',
            'updated',
            'user',
            $user
        );
        return back()->with('success', 'Password updated successfully.')->with('active_tab', 'password');
    }
}
