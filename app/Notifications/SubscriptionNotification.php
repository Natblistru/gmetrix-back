<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionNotification extends Notification
{
    use Queueable;

    protected $customText;

    public function __construct($customText = null)
    {
        $this->customText = $customText;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->line('Salut!');

        if ($this->customText) {
            $message->line($this->customText);
        } else {
            $message
                ->line('Bine ai venit în comunitatea noastră!')
                ->line('Ai fost cu succes abonat la știrile noastre.')
                ->line('Mulțumim pentru abonare!')
                ->line('Dacă dorești să vizitezi site-ul nostru, apasă butonul de mai jos:')
                ->action('Vizitează site-ul nostru', 'http://localhost:3000/home');
        }
        return $message;
    } 


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
