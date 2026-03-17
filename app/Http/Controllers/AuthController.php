<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No account found with this email address.',
        ]);

        $email = $request->email;

        // ✅ Check if there's an unexpired OTP within last 2 mins — block resend
        $existing = DB::table('password_reset_otps')
            ->where('email', $email)
            ->where('expires_at', '>', Carbon::now()) // still valid
            ->orderBy('created_at', 'desc')
            ->first();

        if ($existing) {
            $canResendAt = Carbon::parse($existing->created_at)->addMinutes(2);
            if (Carbon::now()->isBefore($canResendAt)) {
                $secondsLeft = Carbon::now()->diffInSeconds($canResendAt);
                return back()->withErrors([
                    'email' => "Please wait {$secondsLeft} seconds before requesting a new OTP."
                ])->withInput();
            }
        }

        $user      = User::where('email', $email)->first();
        $otp       = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $userAgent = $request->userAgent();
        $ip        = $request->ip();
        $device    = 'Desktop';

        if (str_contains(strtolower($userAgent), 'mobile'))      $device = 'Mobile';
        elseif (str_contains(strtolower($userAgent), 'tablet'))  $device = 'Tablet';

        // ✅ Insert new record — no delete, just let old ones expire
        DB::table('password_reset_otps')->insert([
            'email'      => $email,
            'otp'        => Hash::make($otp),
            'expires_at' => Carbon::now()->addMinutes(2), // ✅ 2 mins
            'ip_address' => $ip,
            'device'     => $device,
            'user_agent' => $userAgent,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        session([
            'otp_email'      => $email,
            'otp_device'     => $device,
            'otp_ip'         => $ip,
            'otp_user_agent' => $userAgent,
        ]);

        Mail::to($email)->send(new OtpMail($otp, $user->first_name));

        logActivity("OTP requested from {$device} | IP: {$ip}", 'access', 'user', $user);

        return redirect()->route('auth.verifyOtp')
            ->with('success', 'OTP sent to ' . $email . '. It expires in 2 minutes.');
    }

    public function verifyOtpPage()
    {
        if (!session('otp_email')) {
            return redirect()->route('auth.forgot');
        }
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|string|size:6']);

        $email  = session('otp_email');
        $otp    = trim($request->otp);

        // ✅ Get latest unexpired OTP only
        $record = DB::table('password_reset_otps')
            ->where('email', $email)
            ->where('expires_at', '>', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'OTP has expired or is invalid. Please request a new one.']);
        }

        if (!Hash::check($otp, $record->otp)) {
            return back()->withErrors(['otp' => 'Incorrect OTP. Please try again.']);
        }

        session(['otp_verified' => true]);
        return redirect()->route('auth.reset');
    }

    public function resetPasswordPage()
    {
        if (!session('otp_email') || !session('otp_verified')) {
            return redirect()->route('auth.forgot');
        }
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        if (!session('otp_email') || !session('otp_verified')) {
            return redirect()->route('auth.forgot');
        }

        $request->validate([
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $email = session('otp_email');
        $user  = User::where('email', $email)->first();

        $user->update(['password' => Hash::make($request->password)]);

        // ✅ Cleanup
        DB::table('password_reset_otps')->where('email', $email)->delete();
        session()->forget(['otp_email', 'otp_verified']);

        $device    = session('otp_device', 'Unknown');
        $ip        = session('otp_ip', 'Unknown');
        $userAgent = session('otp_user_agent', 'Unknown');

        logActivity(
            "Password reset from {$device} | IP: {$ip} | Agent: {$userAgent}",
            'updated',
            'user',
            $user
        );

        return redirect()->route('login')->with('success', 'Password reset successfully. Please log in.');
    }


    public function resendOtp()
    {
        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('auth.forgot')
                ->withErrors(['email' => 'Session expired. Please enter your email again.']);
        }

        // ✅ Check if 2 mins have passed since last OTP
        $existing = DB::table('password_reset_otps')
            ->where('email', $email)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($existing) {
            $canResendAt = Carbon::parse($existing->created_at)->addMinutes(2);
            if (Carbon::now()->isBefore($canResendAt)) {
                $secondsLeft = Carbon::now()->diffInSeconds($canResendAt);
                return redirect()->route('auth.verifyOtp')
                    ->with('error', "Please wait {$secondsLeft} seconds before requesting a new OTP.");
            }
        }

        $user      = User::where('email', $email)->first();
        $otp       = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $ip        = request()->ip();
        $userAgent = request()->userAgent();
        $device    = 'Desktop';

        if (str_contains(strtolower($userAgent), 'mobile'))     $device = 'Mobile';
        elseif (str_contains(strtolower($userAgent), 'tablet')) $device = 'Tablet';

        // ✅ Insert new record — no delete
        DB::table('password_reset_otps')->insert([
            'email'      => $email,
            'otp'        => Hash::make($otp),
            'expires_at' => Carbon::now()->addMinutes(2), // ✅ 2 mins
            'ip_address' => $ip,
            'device'     => $device,
            'user_agent' => $userAgent,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        session(['otp_ip' => $ip, 'otp_device' => $device, 'otp_user_agent' => $userAgent]);

        Mail::to($email)->send(new OtpMail($otp, $user->first_name));

        logActivity("OTP resent from {$device} | IP: {$ip}", 'access', 'user', $user);

        return redirect()->route('auth.verifyOtp')
            ->with('success', 'New OTP sent to ' . $email . '. It expires in 2 minutes.');
    }
}
