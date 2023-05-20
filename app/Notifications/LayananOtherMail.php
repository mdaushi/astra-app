<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LayananOtherMail extends Notification
{
    use Queueable;

    public $pegawaiName;
    public $alasan;
    /**
     * Create a new notification instance.
     */
    public function __construct($pegawaiName, $alasan)
    {
        $this->pegawaiName = $pegawaiName;
        $this->alasan = $alasan;
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
                    ->subject('Pengiriman Barang Layanan YES')
                    ->line($this->pegawaiName . ' telah mengirim barang menggunakan jenis layanan YES dengan Alasan: ' . $this->alasan)
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
