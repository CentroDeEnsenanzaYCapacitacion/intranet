<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $resetUrl;

    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->resetUrl = url('/reset-password/' . $token);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Restablecer contraseña - IntraCEC',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.resetPassword',
        );
    }

    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        return $this->withSwiftMessage(function ($message) {
            $message->getHeaders()->addTextHeader('Content-Type', 'text/html; charset=UTF-8');
        });
    }
}
