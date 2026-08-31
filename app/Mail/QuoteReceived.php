<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Sent to the studio when someone completes the hero quote estimator. */
class QuoteReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Quote $quote) {}

    public function envelope(): Envelope
    {
        $range = '$' . number_format((int) $this->quote->price_low)
               . ' – $' . number_format((int) $this->quote->price_high);

        return new Envelope(
            subject: "New quote request — {$this->quote->name} ({$range})",
            replyTo: [new Address($this->quote->email, $this->quote->name)],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.quote');
    }
}
