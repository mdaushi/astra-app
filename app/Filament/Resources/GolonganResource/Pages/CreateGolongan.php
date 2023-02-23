<?php

namespace App\Filament\Resources\GolonganResource\Pages;

use App\Filament\Resources\GolonganResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGolongan extends CreateRecord
{
    protected static string $resource = GolonganResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
