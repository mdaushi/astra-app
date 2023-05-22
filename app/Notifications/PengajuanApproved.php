<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanApproved extends Notification
{
    use Queueable;

    public $pengajuan;
    public $approval_nama;
    /**
     * Create a new notification instance.
     */
    public function __construct($pengajuan, $approval_nama)
    {
        $this->pengajuan = $pengajuan;
        $this->approval_nama = $approval_nama;
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
                    ->subject('Pengajuan Perjalanan approval process')
                    ->line('Pengajuan anda pada tanggal ' . Carbon::parse($this->pengajuan->created_at)->format('d M Y') . ' telah diapprove oleh ' . $this->approval_nama)
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
