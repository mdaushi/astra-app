<?php

namespace App\Listeners;

use App\Models\watifier;
use App\Services\WatifierService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotificationAgreedPengajuan
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
    public function handle(object $event): void
    {
        $order = config('approval.order');
        $columns = config('approval.roleWithColumnFilter');
        // send mail for user if pengajuan agreed
        if(
            $event->pengajuanPerjalananDinas->{$columns[$order[0]]} &&
            $event->pengajuanPerjalananDinas->{$columns[$order[2]]}
            ){
                $pegawai = $event->pengajuanPerjalananDinas->pegawai;
                // Notification::send($user, new PengajuanAgreed($event->pengajuanPerjalananDinas));

                // send notif whatsapp
                watifier::sendMessage([
                    'id' => WatifierService::transformWhatsapp($pegawai->whatsapp),
                    'message' => WatifierService::agreedPengajuanMessage($pegawai->nama)
                ]);
        }
    }
}
