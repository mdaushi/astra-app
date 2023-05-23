<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\ApprovedProcessed;
use App\Notifications\ApprovalMail;
use App\Notifications\PengajuanAgreed;
use App\Notifications\PengajuanApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNotificationApprovedToUser
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
    public function handle(ApprovedProcessed $event): void
    {
        $order = config('approval.order');
        $columns = config('approval.roleWithColumnFilter');

        if($event->pengajuanPerjalananDinas->pegawai->approval1 == auth()->user()->email){
            $approval = $event->pengajuanPerjalananDinas->pegawai->approval1; 
            $user = User::where('email', $approval)->first();
            $userPengaju = User::find($event->pengajuanPerjalananDinas->pegawai->user->id);
            Notification::send($userPengaju, new PengajuanApproved($event->pengajuanPerjalananDinas, $user->name));
        }

        if($event->pengajuanPerjalananDinas->pegawai->approval2 == auth()->user()->email){
            $approval = $event->pengajuanPerjalananDinas->pegawai->approval2; 
            $user = User::where('email', $approval)->first();
            $userPengaju = User::find($event->pengajuanPerjalananDinas->pegawai->user->id);
            Notification::send($userPengaju, new PengajuanApproved($event->pengajuanPerjalananDinas, $user->name));
        }

        if($event->pengajuanPerjalananDinas->pegawai->approval3 == auth()->user()->email){
            $approval = $event->pengajuanPerjalananDinas->pegawai->approval3; 
            $user = User::where('email', $approval)->first();
            $userPengaju = User::find($event->pengajuanPerjalananDinas->pegawai->user->id);
            Notification::send($userPengaju, new PengajuanApproved($event->pengajuanPerjalananDinas, $user->name));
        }
        
    }
}
