<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; background:#f4f4f4; padding:30px;">
    <div style="max-width:600px; margin:auto; background:#fff; border-radius:8px; padding:32px;">
        <h2 style="color:#2d6a4f;">Centralino Flora</h2>
        <p>Hi <strong>{{ $firstName }}</strong>,</p>
        <p>You requested to reset your password. Use the OTP below — it expires in <strong>5 minutes</strong>.</p>
        <div style="text-align:center; margin:24px 0;">
            <span style="font-size:36px; font-weight:bold; letter-spacing:8px; color:#2d6a4f; background:#f0f0f0; padding:16px 32px; border-radius:8px;">
                {{ $otp }}
            </span>
        </div>
        <p>If you did not request this, please ignore this email.</p>
        <hr style="margin:24px 0;">
        <p style="font-size:12px; color:#999;">This is an automated message from Centralino Flora. Please do not reply.</p>
    </div>
</body>
</html>