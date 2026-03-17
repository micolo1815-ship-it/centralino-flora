<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUser extends Mailable
{
    use Queueable, SerializesModels;

    public $firstname;
    public $email;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct($firstname, $email, $password)
    {
        $this->firstname = $firstname;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your New Account Details')
                    ->view('mail.new_user_account_created');
    }
}
