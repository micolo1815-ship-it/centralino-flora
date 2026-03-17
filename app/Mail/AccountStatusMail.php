<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $firstName,
        public string $email,
        public string $status // 'activated' or 'deactivated'
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->status === 'activated'
            ? 'Your Centralino Flora Account Has Been Activated'
            : 'Your Centralino Flora Account Has Been Deactivated';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account-status',
            with: [
                'firstName' => $this->firstName,
                'email'     => $this->email,
                'status'    => $this->status,
            ]
        );
    }
}