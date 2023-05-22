<?php

namespace App\Listeners;

use App\Events\RejectedProcessed;
use App\Models\User;
use App\Notifications\PengajuanRejected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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
    }
}
