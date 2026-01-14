<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $plainPassword;

    public function __construct(Student $student, string $plainPassword)
    {
        $this->student = $student;
        $this->plainPassword = $plainPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenido a CEC',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.studentWelcome',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
