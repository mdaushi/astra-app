<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanAgreed extends Notification
{
    use Queueable;

    public $pengajuan;
    /**
     * Create a new notification instance.
     */
    public function __construct($pengajuan)
    {
        $this->pengajuan = $pengajuan;
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
                ->subject('Pengajuan Perjalanan Dinas Disetujui')
                ->greeting('Hello, ' . $this->pengajuan->pegawai->nama)
                ->line('Pengajuan Perjalanan dinas anda sudah disetujui.')
                // ->line('klik tombol dibawah ini untuk meli pengajuan')
                // ->action('Approve Pengajuan', $url)
                ->line('Selamat bertugas, be safety!');
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
