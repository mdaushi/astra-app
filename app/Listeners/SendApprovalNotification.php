<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\ApprovalProcessed;
use App\Notifications\ApprovalMail;
use App\Notifications\PengajuanAgreed;
use App\Notifications\PengajuanApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendApprovalNotification
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
    public function handle(ApprovalProcessed $event): void
    {
        $order = config('approval.order');
        $columns = config('approval.roleWithColumnFilter');

        // send mail for user
        // if(
        //     $event->pengajuanPerjalananDinas->sign_user_at &&
        //     !$event->pengajuanPerjalananDinas->{$columns[$order[2]]}
        //     ){
        //     $user = User::find($event->pengajuanPerjalananDinas->pegawai->user->id);
        // }

        // send mail for approval 1
        if(
            $event->pengajuanPerjalananDinas->sign_user_at &&
            !$event->pengajuanPerjalananDinas->{$columns[$order[2]]}
            ){
                $approval = $event->pengajuanPerjalananDinas->pegawai->approval1; 

                // send to approval
                $user = User::where('email', $approval)->first();
                Notification::send($user, new ApprovalMail($event->pengajuanPerjalananDinas, $user->name));

                // send to user
                $userPengaju = User::find($event->pengajuanPerjalananDinas->pegawai->user->id);
                Notification::send($userPengaju, new PengajuanApproved($event->pengajuanPerjalananDinas, $user->name));
        }

        // send mail for approval 2
        if(
            $event->pengajuanPerjalananDinas->{$columns[$order[0]]} &&
            !$event->pengajuanPerjalananDinas->{$columns[$order[2]]}
            ){
                $approval = $event->pengajuanPerjalananDinas->pegawai->approval2; 
                $user = User::where('email', $approval)->first();
                Notification::send($user, new ApprovalMail($event->pengajuanPerjalananDinas, $user->name));

                // send to user
                $userPengaju = User::find($event->pengajuanPerjalananDinas->pegawai->user->id);
                Notification::send($userPengaju, new PengajuanApproved($event->pengajuanPerjalananDinas, $user->name));
        }

        // send mail for approval 3
        if(
            $event->pengajuanPerjalananDinas->{$columns[$order[1]]} &&
            !$event->pengajuanPerjalananDinas->{$columns[$order[2]]}
            ){
                $approval = $event->pengajuanPerjalananDinas->pegawai->approval3; 
                $user = User::where('email', $approval)->first();
                Notification::send($user, new ApprovalMail($event->pengajuanPerjalananDinas, $user->name));

                // send to user
                $userPengaju = User::find($event->pengajuanPerjalananDinas->pegawai->user->id);
                Notification::send($userPengaju, new PengajuanApproved($event->pengajuanPerjalananDinas, $user->name));
        }

        // send mail for user if pengajuan agreed
        if(
            $event->pengajuanPerjalananDinas->{$columns[$order[0]]} &&
            $event->pengajuanPerjalananDinas->{$columns[$order[2]]}
            ){
                $pegawai = $event->pengajuanPerjalananDinas->pegawai;
                $user = User::find($pegawai->user->id);
                Notification::send($user, new PengajuanAgreed($event->pengajuanPerjalananDinas));
        }

        
    }
}
