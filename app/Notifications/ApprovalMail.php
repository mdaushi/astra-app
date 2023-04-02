<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovalMail extends Notification
{
    use Queueable;

    public $pengajuan;
    public $userApproval;
    /**
     * Create a new notification instance.
     */
    public function __construct($pengajuan, $userApproval)
    {
        $this->pengajuan = $pengajuan;
        $this->userApproval = $userApproval;
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
        $pegawai = $this->pengajuan->nama;
        $url = route('filament.resources.pengajuan-perjalanan-dinas.view', $this->pengajuan->id);
        return (new MailMessage)
                    ->subject('Approval Pengajuan Perjalanan Dinas')
                    ->greeting('Hello, ' . $this->userApproval)
                    ->line('Pegawai ' . $pegawai . ' telah pengajukan perjalanan dinas.')
                    ->line('klik tombol dibawah ini untuk approve pengajuan')
                    ->action('Approve Pengajuan', $url)
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
