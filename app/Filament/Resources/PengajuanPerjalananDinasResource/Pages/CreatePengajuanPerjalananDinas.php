<?php

namespace App\Filament\Resources\PengajuanPerjalananDinasResource\Pages;

use App\Events\ApprovalProcessed;
use Carbon\Carbon;
use App\Models\Golongan;
use App\Models\Rekening;
use Filament\Pages\Actions;
use Illuminate\Support\Facades\DB;
use App\Models\KegiatanPerjalananDinas;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PengajuanPerjalananDinasResource;

class CreatePengajuanPerjalananDinas extends CreateRecord
{
    protected static string $resource = PengajuanPerjalananDinasResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['no_surat'] = $this->getMaxNoSurat();
        $data['user_id'] = auth()->id();

        // $bank = $this->processRekening($data['bank']);

        // $data['nama_bank'] = $bank['nama'];
        // $data['no_rekening'] = $bank['rekening'];

        $data['pegawai_id'] = auth()->user()->pegawai->id;

        $data['sign_user_at'] = Carbon::now();
    
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        DB::beginTransaction();

        try {
            $saved = static::getModel()::create($data);
            
            $kegiatanAll = [];
            foreach ($data['kegiatan'] as $item) {
                $item['pengajuan_perjalanan_dinas_id'] = $saved->id;
    
                $rate_hotel = $this->findRateHotel();
                $item['rate_hotel'] = $rate_hotel;
    
                array_push($kegiatanAll, $item);
            }        
    
            $saved->kegiatan_perjalanan_dinas()->sync($kegiatanAll);
    
            // approval notification
            ApprovalProcessed::dispatch($saved);
    
            DB::commit();
            
            return $saved;
                
        } catch (\Throwable $th) {
            DB::rollBack();
        }

    }

    protected function getMaxNoSurat()
    {
        return $this->getModel()::max('no_surat') + 1;
    }

    protected function processRekening(string $rekening): array
    {
        $rekening = Rekening::where('rekening', $rekening)->first();

        return [
            'nama' => $rekening->nama,
            'rekening' => $rekening->rekening
        ];
    }

    protected function findRateHotel() {
        $golonganUser = auth()->user()->pegawai->golongan->id;
        $hotel = Golongan::find($golonganUser)->rate_hotel;
        return $hotel;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
