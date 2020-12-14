<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ReportUpdate extends Notification implements ShouldQueue
{
    use Queueable;

     protected $data = [];
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = [];
        //check if user is active
        //do not send report update to in active user account
        $user = User::where('email',$this->data['to'])->first();
        if($user->is_active){
        $via = ['database','mail'];
        }else{
        $via = ['database'];
        }


        return $via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                  ->markdown('emails.reportUpdate',['message'=>$this->data['message'],'ref'=>$this->data['reference_number']])
                  ->from(env('MAIL_FROM_EMAIl','westsparta@gmail.com'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

      public function toDatabase($notifiable)
    {
        return [
            "status" => $this->data['status'],
            "message"=>$this->data['message'],
            "reference_number"=>$this->data['reference_number']
        ];
    }
}
