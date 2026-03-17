<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class SessionController extends Controller
{
    public function create()
    {
        return view("auth.login");
    }

    public function store()
    {
        $attributes = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (! Auth::attempt($attributes)) {
            throw ValidationException::withMessages([
                'email' => ['Sorry, those credentials do not match']
            ]);
        }

        // Regenerate session to prevent fixation
        request()->session()->regenerate();

        // Get the authenticated user
        $user = Auth::user();

        // Log login activity
        logActivity(
            'User logged in',
            'login',
            'user',
            $user
        );

        return redirect('/dashboard');
    }

    public function destroy()
    {
        // Get the authenticated user before logout
        $user = Auth::user();

        // Log logout activity
        logActivity(
            'User logged out',
            'logout',
            'user',
            $user
        );

        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login');
    }

    public function forgot()
    {
        return view("auth.forgot-password");
    }
}
