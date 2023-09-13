<?php

namespace App\Filament\Resources\PlanPengajuanPerjalananDinasResource\Pages;

use Carbon\Carbon;
use App\Models\Rekening;
use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PlanPengajuanPerjalananDinasResource;
use App\Models\Golongan;
use App\Models\PlanKegiatanPengajuanPerjalananDinas;

class CreatePlanPengajuanPerjalananDinas extends CreateRecord
{
    protected static string $resource = PlanPengajuanPerjalananDinasResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // $data['no_surat'] = $this->getMaxNoSurat();
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

        $saved = static::getModel()::create($data);
        
        $kegiatanAll = collect();
        foreach ($data['kegiatan'] as $item) {
            $item['pengajuan_perjalanan_dinas_id'] = $saved->id;

            $rate_hotel = $this->findRateHotel();
            $item['rate_hotel'] = $rate_hotel;

            $instanceKegiatanModel = new PlanKegiatanPengajuanPerjalananDinas($item);

            $kegiatanAll->push($instanceKegiatanModel);

        }        

        $saved->kegiatan_perjalanan_dinas()->saveMany($kegiatanAll);
        // KegiatanPerjalananDinas::insert($kegiatanAll);
        return $saved;
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
