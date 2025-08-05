<?php

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