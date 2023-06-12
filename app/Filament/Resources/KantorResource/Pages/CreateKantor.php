<?php

namespace App\Filament\Resources\KantorResource\Pages;

use App\Filament\Resources\KantorResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKantor extends CreateRecord
{
    protected static string $resource = KantorResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
