<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Models\User;
use App\Models\watifier;
use App\Events\RejectedProcessed;
use App\Services\WatifierService;
use App\Notifications\PengajuanRejected;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNotificationRejected
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RejectedProcessed $event): void
    {
        $userPengaju = User::find($event->pengajuanPerjalananDinas->pegawai->user->id);
        Notification::send($userPengaju, new PengajuanRejected($event->pengajuanPerjalananDinas, $userPengaju->name));

        // send notif wa
        $this->sendToWhatsapp(
            pegawai: ['nomor' => $userPengaju->pegawai->whatsapp, 'nama' => $userPengaju->name],
            tanggal_pengajuan: Carbon::parse($event->pengajuanPerjalananDinas->created_at)->format('d M Y'),
        );
    }

    private function sendToWhatsapp(array $pegawai, string $tanggal_pengajuan)
    { 
        return watifier::sendMessage([
            'id' => $pegawai['nomor'], 
            'message' => WatifierService::rejectedApprovalMessage(pegawai: $pegawai['nama'], tanggal_pengajuan: $tanggal_pengajuan)
        ]);
    }
}
