<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertFinanceManager extends Notification
{
    use Queueable;
    protected $count;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($count)
    {
        $this->count=$count;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        //dd($notifiable);
        return (new MailMessage)
            ->subject('Fuel Requests Approval')
            ->greeting('Good day, '.$notifiable->first_name)
            ->line('There are currently '.$this->count .' requests that have not yet been approved. May you please attend to them.')
            ->action('View Requests', route('manage.requests'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        //dd($notifiable);
        return [
            'request_count' => $this->count
        ];
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
