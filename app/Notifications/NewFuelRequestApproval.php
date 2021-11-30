<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFuelRequestApproval extends Notification
{
    use Queueable;
public $details;
    public $approveUrl;
    public $reviewUrl;public $rejectUrl;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $approveUrl = 'http://192.168.1.242:8080/whelsonfuel/frequests/approve/'.$this->details['id'];
        $reviewUrl = 'http://192.168.1.242:8080/whelsonfuel/frequests/preview/'.$this->details['id'];
        $rejectUrl = 'http://192.168.1.242:8080/whelsonfuel/frequests/reject/'.$this->details['id'];
        return (new MailMessage)
            ->subject('Fuel Request Approval')
            ->greeting($this->details['greeting'])
            ->line($this->details['body'])
            ->line($this->details['body1'])
            ->line($this->details['body2'])
            ->line($this->details['body3'])
            ->line($this->details['body4'])
            ->line($this->details['body5'])
            ->markdown('mails.fuelrequest', [
                'details' => $this->details,
                'approveUrl' => $approveUrl,
                'reviewUrl'  => $reviewUrl,
                'rejectUrl'  => $rejectUrl
            ]);
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
}
