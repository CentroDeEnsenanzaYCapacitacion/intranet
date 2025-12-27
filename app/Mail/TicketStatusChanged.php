<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class TicketStatusChanged extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $ticket;
    public $oldStatus;

    public function __construct($ticket, $oldStatus)
    {
        $this->ticket = $ticket;
        $this->oldStatus = $oldStatus;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ticket actualizado - ' . $this->ticket->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.ticketStatusChanged',
        );
    }

    public function attachments(): array
    {
        return [];
    }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'Content-Type' => 'text/html; charset=UTF-8',
                'Content-Transfer-Encoding' => '8bit',
            ],
        );
    }
}
