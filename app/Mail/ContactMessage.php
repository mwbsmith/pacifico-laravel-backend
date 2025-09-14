<?php
/*
namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $messageData;

    public function __construct(Message $message)
    {
        $this->messageData = $message;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('New Contact Message from ' . $this->messageData->name)
                    ->view('emails.contact-message')
                    ->with([
                        'messageData' => $this->messageData,
                    ]);
    }
}
*/
// app/Mail/ContactMessage.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Message $message) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New contact message from website',
            // ❌ DO NOT set: to: [...], cc: [...], bcc: [...]
            // Let the controller provide recipients.
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-message', // or view: 'emails.contact'
            with: ['messageModel' => $this->message],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}