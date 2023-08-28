<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\watifier;
use App\Events\PDBarengProcessed;
use App\Services\WatifierService;
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
            
            foreach ($pengajuanAll as $key => $value) {

                $namingSendding = collect($nameAllPegawai)->reject(function(string $data, int $key) use($value){
                    return $data == $value->nama;
                })->values()->all();
    
                $nameSendingString = implode(', ', $namingSendding);

                $pegawai = $value->pegawai;
                $user = User::find($pegawai->user->id);
                
                Notification::send($user, new PDBarengNotification($pegawai->user->name, $nameSendingString));

                $this->sendToWhatsapp(
                    pegawai: [
                        'nomor' => $user->pegawai->whatsapp,
                        'nama' => $user->name
                    ],
                    pegawai_pd_bersama: $nameSendingString
                    );
            }
        }
    }

    private function sendToWhatsapp(array $pegawai, string $pegawai_pd_bersama)
    { 
        return watifier::sendMessage([
            'id' => $pegawai['nomor'], 
            'message' => WatifierService::PdBersamaMessage(pegawai: $pegawai['nama'], pegawai_pd_bersama: $pegawai_pd_bersama)
        ]);
    }
}
