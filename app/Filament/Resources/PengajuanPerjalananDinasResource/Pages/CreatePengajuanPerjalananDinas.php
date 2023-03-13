<?php

namespace App\Filament\Resources\PengajuanPerjalananDinasResource\Pages;

use Carbon\Carbon;
use App\Models\Rekening;
use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PengajuanPerjalananDinasResource;
use App\Models\KegiatanPerjalananDinas;
use Illuminate\Support\Facades\DB;

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

        $saved = static::getModel()::create($data);
        
        $kegiatanAll = [];
        foreach ($data['kegiatan'] as $item) {
            $item['pengajuan_perjalanan_dinas_id'] = $saved->id;
            array_push($kegiatanAll, $item);
        }        

        $saved->kegiatan_perjalanan_dinas()->sync($kegiatanAll);
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
