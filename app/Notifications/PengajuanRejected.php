<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanRejected extends Notification
{
    use Queueable;

    public $namaPengaju;
    public $pengajuan;
    /**
     * Create a new notification instance.
     */
    public function __construct($pengajuan, $namaPengaju)
    {
        $this->pengajuan = $pengajuan;
        $this->namaPengaju = $namaPengaju;
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
                    ->subject('Pengajuan Perjalanan Dinas ditolak')
                    ->greeting('Hello, ' . $this->namaPengaju)
                    ->line('Maaf, Pengajuan anda pada tanggal ' . Carbon::parse($this->pengajuan->created_at)->format('d M Y') . ' Telah ditolak.')
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
