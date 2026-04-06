<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $contactName,
        public string $contactEmail,
        public string $contactSubject,
        public string $contactMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Contact Message - {$this->contactSubject} | Travel Center Marmaris",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-admin',
        );
    }
}
