<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostProjectInfo extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public function __construct($email)
    {
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject('New Problem Posted')->view('mails.post_project_info');
    }
}
?>