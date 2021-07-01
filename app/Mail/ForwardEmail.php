<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ForwardEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $forward_request;

    public $email_request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($forward_request)
    {
        $this->forward_request = $forward_request;
        $this->email_request = Auth::user()->email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Livewire Forward Email Request')
            ->view('emails.forward');
    }
}
