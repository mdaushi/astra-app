<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\PDBarengProcessed;
use App\Models\KegiatanPerjalananDinas;
use App\Models\PengajuanPerjalananDinas;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\PDBarengNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendPDBarengNotification
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
    public function handle(PDBarengProcessed $event): void
    {
        $order = config('approval.order');
        $columns = config('approval.roleWithColumnFilter');

        if($event->pengajuanPerjalananDinas->{$columns[$order[2]]})
        {
            $pengajuan = $event->pengajuanPerjalananDinas;
            $kegiatan = $pengajuan->kegiatan_perjalanan_dinas()->select('id', 'tanggal', 'ke_kota', 'pengajuan_perjalanan_dinas_id')->get();

            $findSame = $kegiatan->map(function($value){
                return KegiatanPerjalananDinas::whereNot('pengajuan_perjalanan_dinas_id', $value->pengajuan_perjalanan_dinas_id)
                    ->where('tanggal', $value->tanggal)
                    ->where('ke_kota', $value->ke_kota)
                    ->get();
            });

            $mergeArray = collect();
            foreach ($findSame as $key => $value) {
                $mergeArray->merge($value);
            }

            $removeDuplicate = $findSame->flatten(1)->unique('pengajuan_perjalanan_dinas_id')->values()->all();

            $pluckPengajuanId = collect($removeDuplicate)->pluck('pengajuan_perjalanan_dinas_id')->concat([$kegiatan[0]->pengajuan_perjalanan_dinas_id]);

            $pengajuanAll = PengajuanPerjalananDinas::whereIn('id', $pluckPengajuanId)->get(); 

            $nameAllPegawai = $pengajuanAll->pluck('nama')->toArray();
            $namingSendding = collect($nameAllPegawai)->reject(function(string $value, int $key) use($pengajuan){
                return $value == $pengajuan->nama;
            })->values()->all();

            $nameSendingString = implode(', ', $namingSendding);

            foreach ($pengajuanAll as $key => $value) {
                $pegawai = $value->pegawai;
                $user = User::find($pegawai->user->id);
                Notification::send($user, new PDBarengNotification($pegawai->user->nama, $nameSendingString));
            }
        }
    }
}
