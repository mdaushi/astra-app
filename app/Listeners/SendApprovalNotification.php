<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\watifier;
use App\Events\ApprovalProcessed;
use App\Services\WatifierService;
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
                $user = User::with('pegawai')->where('email', $approval)->first();
                // Notification::send($user, new ApprovalMail($event->pengajuanPerjalananDinas, $user->name));

                // send notif whatsapp
                $this->sendToWhatsapp(
                    approved_by: ['nomor' => $user->pegawai->whatsapp, 'nama' => $user->name],
                    pegawai: $event->pengajuanPerjalananDinas->nama,
                    link_pengajuan: route('filament.resources.pengajuan-perjalanan-dinas.view', $event->pengajuanPerjalananDinas->id)
                );
                
        }

        // send mail for approval 2
        if(
            $event->pengajuanPerjalananDinas->{$columns[$order[0]]} &&
            !$event->pengajuanPerjalananDinas->{$columns[$order[2]]}
            ){
                $approval = $event->pengajuanPerjalananDinas->pegawai->approval2; 
                $user = User::with('pegawai')->where('email', $approval)->first();
                // Notification::send($user, new ApprovalMail($event->pengajuanPerjalananDinas, $user->name));

                // send notif whatsapp
                $this->sendToWhatsapp(
                    approved_by: ['nomor' => $user->pegawai->whatsapp, 'nama' => $user->name],
                    pegawai: $event->pengajuanPerjalananDinas->nama,
                    link_pengajuan: route('filament.resources.pengajuan-perjalanan-dinas.view', $event->pengajuanPerjalananDinas->id)
                );
        }

        // send mail for approval 3
        if(
            $event->pengajuanPerjalananDinas->{$columns[$order[1]]} &&
            !$event->pengajuanPerjalananDinas->{$columns[$order[2]]}
            ){
                $approval = $event->pengajuanPerjalananDinas->pegawai->approval3; 
                $user = User::with('pegawai')->where('email', $approval)->first();
                // Notification::send($user, new ApprovalMail($event->pengajuanPerjalananDinas, $user->name));

                // send notif whatsapp
                $this->sendToWhatsapp(
                    approved_by: ['nomor' => $user->pegawai->whatsapp, 'nama' => $user->name],
                    pegawai: $event->pengajuanPerjalananDinas->nama,
                    link_pengajuan: route('filament.resources.pengajuan-perjalanan-dinas.view', $event->pengajuanPerjalananDinas->id)
                );
        }

        // send mail for user if pengajuan agreed
        // if(
        //     $event->pengajuanPerjalananDinas->{$columns[$order[0]]} &&
        //     $event->pengajuanPerjalananDinas->{$columns[$order[2]]}
        //     ){
        //         $pegawai = $event->pengajuanPerjalananDinas->pegawai;
        //         // $user = User::find($pegawai->user->id);
        //         // Notification::send($user, new PengajuanAgreed($event->pengajuanPerjalananDinas));
        // }

        
    }

    private function sendToWhatsapp(array $approved_by, string $pegawai, string $link_pengajuan)
    { 
        return watifier::sendMessage([
            'id' => $approved_by['nomor'], 
            'message' => WatifierService::requestApprovalMessage(approved_by: $approved_by['nama'], pegawai: $pegawai, link_pengajuan: $link_pengajuan)
        ]);
    }
}
