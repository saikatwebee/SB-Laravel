<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class otpSent extends Mailable
{
    use Queueable, SerializesModels;

    public $email_data;
    public function __construct($email_data)
    {
        $this->email_data = $email_data;
    }
    public function build()
    {
        return $this->subject('One Time Password')->view('mails.otp_mail')->with(['email_data' => $this->email_data]);
    }
}
