<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportUpdate extends Mailable
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
        return $this->markdown('emails.reportUpdate',['message'=>$this->data['message'],'ref'=>$this->data['ref']])
        ->to($this->data['to'])
      ->from(env('MAIL_FROM_EMAIl','westsparta@gmail.com'));
    }
}