<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUser  ;

class TestEmail extends Command
{
    protected $signature = 'email:test';
    protected $description = 'Test email sending';

    public function handle()
    {
        $to = 'jereykodelacruz05@gmail.com';  // Change this
        try {
            Mail::to($to)->send(new NewUser  ('Test', $to, 'tempPass123'));
            $this->info('Email sent to ' . $to);
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
