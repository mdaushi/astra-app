<?php

namespace App\Filament\Resources\EkspedisiResource\Pages;

use App\Filament\Resources\EkspedisiResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEkspedisi extends EditRecord
{
    protected static string $resource = EkspedisiResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['tempat_tujuan_nonfaktur'] = $data['tempat_tujuan']; 
        $data['tempat_tujuan_faktur'] = $data['tempat_tujuan']; 
       
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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
}
