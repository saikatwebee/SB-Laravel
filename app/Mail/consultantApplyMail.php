<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class consultantApplyMail extends Mailable
{
    use Queueable, SerializesModels;
    public $email_data;
    public function __construct($email_data)
    {
        $this->email_data = $email_data;
    }

    public function build()
    {
       return $this->subject('Project Applied')->view('Mails.apply_project')->with(['email_data' => $this->email_data]);
           
    }
}
