<?php

namespace App\Filament\Resources\EkspedisiResource\Pages;

use Filament\Pages\Actions;
use Filament\Tables\Actions\Position;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\EkspedisiResource;

class ListEkspedisis extends ListRecords
{
    protected static string $resource = EkspedisiResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableActionsPosition(): ?string
    {
        return Position::BeforeCells;
    }
}
