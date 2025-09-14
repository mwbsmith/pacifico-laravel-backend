<?php
// app/Mail/ContactMessage.php
namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Message $message) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'New Contact Message from ' . ($this->message->name ?? 'Website')
        );
    }

    public function content(): Content
    {
        return new Content(
            // Use a regular Blade view (not markdown)
            view: 'emails.contact-message',
            with: [
                // Match the variable your Blade expects
                'messageData' => $this->message,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}