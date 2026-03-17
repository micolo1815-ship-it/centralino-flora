<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; background:#f4f4f4; padding:30px;">
    <div style="max-width:600px; margin:auto; background:#fff; border-radius:8px; padding:32px;">

        <h2 style="color:#2d6a4f;">Centralino Flora</h2>

        <p>Hi <strong>{{ $firstName }}</strong>,</p>

        @if($status === 'activated')
            <p>We are pleased to inform you that your account in <strong>Centralino Flora</strong> has been
                <span style="color:#2d6a4f; font-weight:bold;">activated</span>.
            </p>
            <p>You may now log in using your registered email:</p>
            <p style="background:#f0f0f0; padding:10px; border-radius:6px;"><strong>{{ $email }}</strong></p>
            <p>Welcome back!</p>
        @else
            <p>We would like to inform you that your account in <strong>Centralino Flora</strong> has been
                <span style="color:#c0392b; font-weight:bold;">deactivated</span>.
            </p>
            <p>If you want to activate your account again, please reach out to the
                <strong>Program Chair</strong> or <strong>IT</strong> to activate your account.
            </p>
            <p>Thank you for using our website.</p>
        @endif

        <hr style="margin:24px 0;">
        <p style="font-size:12px; color:#999;">
            This is an automated message from Centralino Flora. Please do not reply to this email.
        </p>
    </div>
</body>
</html>