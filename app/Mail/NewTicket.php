<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewTicket extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo ticket creado - ' . $this->ticket->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.newTicket',
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
