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
    
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $datas = static::getModel()::create($data);
        EkspedisiProcessed::dispatch($datas);
        return $datas;
    }
}
