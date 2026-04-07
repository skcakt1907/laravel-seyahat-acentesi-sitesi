<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Customer $customer) {}

    public function envelope(): Envelope
    {
        $type = $this->customer->type === 'transfer' ? 'Transfer' : 'Activity';
        return new Envelope(
            subject: "Booking Confirmation - {$type} | Marmaris Travel Center",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-customer',
        );
    }
}
