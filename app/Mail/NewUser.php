<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewUser extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $user;
    public $password;

    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenid@ a CEC!!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.newUser',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
