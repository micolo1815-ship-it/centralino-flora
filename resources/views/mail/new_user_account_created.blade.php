<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; background:#f4f4f4; padding:30px;">
    <div style="max-width:600px; margin:auto; background:#fff; border-radius:8px; padding:32px;">

        <h2 style="color:#2d6a4f;">Centralino Flora</h2>

        <p>Hi <strong>{{ $firstname }}</strong>,</p>

        <p>Welcome! Your account in <strong>Centralino Flora</strong> has been successfully created. Here are your login credentials:</p>

        <div style="background:#f0f0f0; padding:16px; border-radius:6px; margin:20px 0;">
            <p style="margin:0 0 8px;"><strong>Email:</strong> {{ $email }}</p>
            <p style="margin:0;"><strong>Password:</strong> {{ $password }}</p>
        </div>

        <p>Please log in and <strong>change your password</strong> as soon as possible to keep your account secure.</p>

        <a href="{{ config('app.url') }}/login"
            style="display:inline-block; margin-top:16px; padding:12px 28px; background:#2d6a4f; color:#fff; text-decoration:none; border-radius:6px; font-weight:bold;">
            Log In Now
        </a>

        <hr style="margin:24px 0;">
        <p style="font-size:12px; color:#999;">This is an automated message from Centralino Flora. Please do not reply to this email.</p>
    </div>
</body>
</html>