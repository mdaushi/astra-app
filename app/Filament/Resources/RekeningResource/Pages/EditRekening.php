<?php

namespace App\Filament\Resources\RekeningResource\Pages;

use App\Filament\Resources\RekeningResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRekening extends EditRecord
{
    protected static string $resource = RekeningResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['pegawai_id'] = auth()->user()->pegawai->id;
    
        return $data;
    }

    protected function getRedirectUrl(): string
    {   
        return $this->getResource()::getUrl('index');
    }
}
