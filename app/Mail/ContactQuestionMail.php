<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactQuestionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageData;

    public function __construct(ContactMessage $messageData)
    {
        $this->messageData = $messageData;
    }

    public function build()
    {
        return $this->subject('Jauns kontakta ziņojums')
            ->view('emails.contact_question');
    }
}
