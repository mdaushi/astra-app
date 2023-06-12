<?php

namespace App\Filament\Resources\KantorResource\Pages;

use App\Filament\Resources\KantorResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKantor extends EditRecord
{
    protected static string $resource = KantorResource::class;

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
}
