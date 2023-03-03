<?php

namespace App\Filament\Resources\RekeningResource\Pages;

use App\Filament\Resources\RekeningResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRekening extends CreateRecord
{
    protected static string $resource = RekeningResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['pegawai_id'] = auth()->user()->pegawai->id;
    
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
