<?php

namespace App\Filament\Resources\PengajuanPerjalananDinasResource\Pages;

use App\Filament\Resources\PengajuanPerjalananDinasResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanPerjalananDinas extends ListRecords
{
    protected static string $resource = PengajuanPerjalananDinasResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
