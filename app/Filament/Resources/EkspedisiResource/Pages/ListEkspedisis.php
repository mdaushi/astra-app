<?php

namespace App\Filament\Resources\EkspedisiResource\Pages;

use App\Filament\Resources\EkspedisiResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEkspedisis extends ListRecords
{
    protected static string $resource = EkspedisiResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
