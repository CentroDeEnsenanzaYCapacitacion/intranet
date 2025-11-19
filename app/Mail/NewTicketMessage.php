<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewTicketMessage extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $ticket;
    public $ticketMessage;
    public $sender;

    public function __construct($ticket, $message)
    {
        $this->ticket = $ticket;
        $this->ticketMessage = $message;
        $this->sender = $message->user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo mensaje en ticket - ' . $this->ticket->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.newTicketMessage',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
