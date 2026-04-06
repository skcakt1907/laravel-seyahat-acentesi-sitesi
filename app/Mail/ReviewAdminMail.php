<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReviewAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $reviewName,
        public string $reviewLocation,
        public int $reviewRating,
        public string $reviewComment,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Review from {$this->reviewName} ({$this->reviewRating}/5) | Travel Center Marmaris",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.review-admin',
        );
    }
}
