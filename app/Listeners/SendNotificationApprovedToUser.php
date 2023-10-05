<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Models\User;
use App\Models\watifier;
use App\Events\ApprovedProcessed;
use App\Services\WatifierService;
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
        // $order = config('approval.order');
        // $columns = config('approval.roleWithColumnFilter');
        $approval1 = strtolower($event->pengajuanPerjalananDinas->pegawai->approval1);
        $approvalvalidate = strtolower($event->pegawaiApproval->user->email);
        if($approval1 == $approvalvalidate){
            $approval = $event->pengajuanPerjalananDinas->pegawai->approval1; 
            $user = User::where('email', $approval)->first();
            $userPengaju = User::with('pegawai')->find($event->pengajuanPerjalananDinas->pegawai->user->id);
            // Notification::send($userPengaju, new PengajuanApproved($event->pengajuanPerjalananDinas, $user->name));

            // send to whatsapp
            $this->sendToWhatsapp(
                approved_by: $user->name,
                tanggal_pengajuan: Carbon::parse($event->pengajuanPerjalananDinas->created_at)->format('d M Y'),
                nomor_pegawai: $userPengaju->pegawai->whatsapp
            );
        }

        $approval2 = strtolower($event->pengajuanPerjalananDinas->pegawai->approval2);
        if($approval2 == $approvalvalidate){
            $approval = $event->pengajuanPerjalananDinas->pegawai->approval2; 
            $user = User::where('email', $approval)->first();
            $userPengaju = User::with('pegawai')->find($event->pengajuanPerjalananDinas->pegawai->user->id);
            // Notification::send($userPengaju, new PengajuanApproved($event->pengajuanPerjalananDinas, $user->name));

            // send to whatsapp
            $this->sendToWhatsapp(
                approved_by: $user->name,
                tanggal_pengajuan: Carbon::parse($event->pengajuanPerjalananDinas->created_at)->format('d M Y'),
                nomor_pegawai: $userPengaju->pegawai->whatsapp
            );
        }

        $approval3 = strtolower($event->pengajuanPerjalananDinas->pegawai->approval3);
        if($approval3 == $approvalvalidate){
            $approval = $event->pengajuanPerjalananDinas->pegawai->approval3; 
            $user = User::where('email', $approval)->first();
            $userPengaju = User::with('pegawai')->find($event->pengajuanPerjalananDinas->pegawai->user->id);
            // Notification::send($userPengaju, new PengajuanApproved($event->pengajuanPerjalananDinas, $user->name));

            // send to whatsapp
            $this->sendToWhatsapp(
                approved_by: $user->name,
                tanggal_pengajuan: Carbon::parse($event->pengajuanPerjalananDinas->created_at)->format('d M Y'),
                nomor_pegawai: $userPengaju->pegawai->whatsapp
            );
        }
        
    }

    private function sendToWhatsapp(string $approved_by, string $tanggal_pengajuan, ?string $nomor_pegawai)
    { 
        return watifier::sendMessage([
            'id' => WatifierService::transformWhatsapp($nomor_pegawai), 
            'message' => WatifierService::processApprovalMessage(approved_by: $approved_by, tanggal_pengajuan: $tanggal_pengajuan)
        ]);
    }
}
