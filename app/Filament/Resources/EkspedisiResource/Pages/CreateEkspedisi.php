<?php

namespace App\Filament\Resources\EkspedisiResource\Pages;

use App\Events\EkspedisiProcessed;
use App\Models\Pegawai;
use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\EkspedisiResource;
use Illuminate\Support\Facades\DB;

class CreateEkspedisi extends CreateRecord
{
    protected static string $resource = EkspedisiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['pegawai_id'] = auth()->user()->pegawai->id;
        if(isset($data['tempat_tujuan_nonfaktur'])){
            $data['tempat_tujuan'] = $data['tempat_tujuan_nonfaktur'];
            unset($data['tempat_tujuan_nonfaktur']);
        }else{
            $data['tempat_tujuan'] = $data['tempat_tujuan_faktur'];
            unset($data['tempat_tujuan_faktur']);
        }
    
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $datas = static::getModel()::create($data);
        EkspedisiProcessed::dispatch($datas);
        return $datas;
    }
}
