<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PDBarengNotification extends Notification
{
    use Queueable;

    public $namaSending;
    public $namaPenerima;
    /**
     * Create a new notification instance.
     */
    public function __construct($namaPenerima, $namaSending)
    {
        $this->namaPenerima = $namaPenerima;
        $this->namaSending = $namaSending;
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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Perjalanan Dinas Bersama')
                    ->greeting('Hello, ' . $this->namaPenerima)
                    ->line($this->namaSending . ' akan PD dengan waktu dan tujuan yang bersamaan dengan anda, anda akan diatur untuk PD bersamaan yaa')
                    // ->action('Notification Action', url('/'))
                    ->line('Terimakasih!');
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
