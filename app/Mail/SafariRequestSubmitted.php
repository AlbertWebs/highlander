<?php

namespace App\Mail;

use App\Models\SafariRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SafariRequestSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SafariRequest $safariRequest) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('New Safari Request').' — '.$this->safariRequest->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.safari-request-submitted',
        );
    }
}
