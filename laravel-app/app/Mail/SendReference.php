<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReference extends Mailable
{
    use Queueable, SerializesModels;
    protected $data = [];
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.sendReference',["ref"=> $this->data['reference_number']])
                    ->from(env('MAIL_FROM_EMAIl','westsparta@gmail.com'))
                    ->to($this->data['to']);
    }
}