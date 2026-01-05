<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $invitationUrl;

    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->invitationUrl = url("/set-password?token={$token}");
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitación a establecer tu contraseña',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.userInvitation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
