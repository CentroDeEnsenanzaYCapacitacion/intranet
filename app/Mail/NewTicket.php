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
            subject: mb_encode_mimeheader('Nuevo ticket creado - ' . $this->ticket->title, 'UTF-8'),
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
}
