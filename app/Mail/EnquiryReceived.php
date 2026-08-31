<?php

namespace App\Mail;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent to the studio when someone submits the contact form.
 *
 * Reply-To is set to the enquirer, so hitting reply in a mail client answers
 * the person rather than the site's own mailbox.
 */
class EnquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Enquiry $enquiry) {}

    public function envelope(): Envelope
    {
        $who = $this->enquiry->company
            ? "{$this->enquiry->name} ({$this->enquiry->company})"
            : $this->enquiry->name;

        return new Envelope(
            subject: "New enquiry — {$who}",
            replyTo: [new Address($this->enquiry->email, $this->enquiry->name)],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.enquiry');
    }
}
