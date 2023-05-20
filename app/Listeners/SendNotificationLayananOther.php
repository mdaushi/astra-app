<?php

namespace App\Listeners;

use App\Events\EkspedisiProcessed;
use App\Models\Pegawai;
use App\Models\User;
use App\Notifications\LayananOtherMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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
            Notification::send($gaUser, new LayananOtherMail($event->ekspedisi->pegawai->nama, $event->ekspedisi->alasan_jenis_layanan_other));
        }
    }
}
