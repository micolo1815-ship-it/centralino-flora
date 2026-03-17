<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Helpers\GmailSentHelper;

class TestEmailController extends Controller
{
    public function sendTestEmail()
    {
        $to = "jereykodelacruz@gmail.com";
        $subject = "Test Email with IMAP Append";
        $body = "Hello GT, this email should appear in your Gmail Sent folder.";

        // 1. Send email normally using SMTP
        Mail::raw($body, function ($message) use ($to, $subject) {
            $message->to($to)
                    ->subject($subject)
                    ->from(env('MAIL_FROM_ADDRESS'));
        });

        // 2. Build RFC822 raw message for IMAP append
        $raw =
            "From: " . env('MAIL_FROM_ADDRESS') . "\r\n" .
            "To: $to\r\n" .
            "Subject: $subject\r\n" .
            "MIME-Version: 1.0\r\n" .
            "Content-Type: text/plain; charset=UTF-8\r\n\r\n" .
            $body;

        // 3. Append to Gmail Sent Mail
        GmailSentHelper::appendToSent($raw);

        return "Email sent AND added to Gmail Sent folder!";
    }
}
