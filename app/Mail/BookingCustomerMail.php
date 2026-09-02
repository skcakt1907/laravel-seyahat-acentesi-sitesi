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

    public function __construct(public Customer $customer)
    {
        // Musteri hangi dilde rezervasyon yaptiysa mail o dilde gonderilir
        $dil = $this->customer->dil ?: 'en';
        if (in_array($dil, ['tr', 'en', 'de', 'nl', 'ru', 'ar'], true)) {
            $this->locale($dil);
        }
    }

    public function envelope(): Envelope
    {
        // Konu satiri da musterinin dilinde olmali; __() ucuncu parametresi ile dili sabitliyoruz
        $dil = $this->customer->dil ?: 'en';
        $tur = __($this->customer->type === 'transfer' ? 'Transfer' : 'Activity', [], $dil);

        return new Envelope(
            subject: __('Booking Confirmation', [], $dil)." - {$tur} | Marmaris Travel Center",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-customer',
        );
    }
}
