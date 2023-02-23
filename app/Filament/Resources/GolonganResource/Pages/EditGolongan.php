<?php

namespace App\Filament\Resources\GolonganResource\Pages;

use App\Filament\Resources\GolonganResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGolongan extends EditRecord
{
    protected static string $resource = GolonganResource::class;

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
