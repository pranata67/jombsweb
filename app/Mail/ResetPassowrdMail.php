<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ResetPassowrdMail extends Mailable
{ 
    use Queueable, SerializesModels;

    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function build()
    {
        // dd($this->token);
        return $this->view('auth.forgot-password.link-toreset-password')->with(['token' => $this->token]);
        // return $this->view('auth.forgot-password.reset')->with(['token' => $this->token]);
    }
}
