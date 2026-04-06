<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Customer $customer) {}

    public function envelope(): Envelope
    {
        $type = $this->customer->type === 'transfer' ? 'Transfer' : 'Activity';
        $name = $this->customer->first_name . ' ' . $this->customer->last_name;
        return new Envelope(
            subject: "New {$type} Booking - {$name} | #TCM" . str_pad($this->customer->id, 5, '0', STR_PAD_LEFT),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-admin',
        );
    }
}
