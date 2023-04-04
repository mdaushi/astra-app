<?php

namespace App\Filament\Resources\EkspedisiResource\Pages;

use App\Filament\Resources\EkspedisiResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEkspedisi extends CreateRecord
{
    protected static string $resource = EkspedisiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
