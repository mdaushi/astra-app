<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\watifier;
use App\Services\WatifierService;
use App\Events\EkspedisiProcessed;
use App\Notifications\LayananOtherMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNotificationLayananOther
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
    public function handle(EkspedisiProcessed $event): void
    {
        if($event->ekspedisi->jenis_layanan == 'other'){
            // get GA User
            $gaColumn = config('approval.column.ga');
            $emailGa = auth()->user()->pegawai->{$gaColumn};
            $gaUser = User::where('email', $emailGa)->first();
            // Notification::send($gaUser, new LayananOtherMail($event->ekspedisi->pegawai->nama, $event->ekspedisi->alasan_jenis_layanan_other));

            // send notif wa
            $this->sendToWhatsapp(
                pegawai: $event->ekspedisi->pegawai->nama,
                alasan: $event->ekspedisi->alasan_jenis_layanan_other,
                wa_approved_by: $gaUser->pegawai->whatsapp
            );
        }
    }
    
    private function sendToWhatsapp(string $pegawai, string $alasan, string $wa_approved_by)
    { 
        return watifier::sendMessage([
            'id' => WatifierService::transformWhatsapp($wa_approved_by), 
            'message' => WatifierService::layananOtherMessage(pegawai: $pegawai, alasan: $alasan)
        ]);
    }
}
