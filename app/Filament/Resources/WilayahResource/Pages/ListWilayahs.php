<?php

namespace App\Filament\Resources\WilayahResource\Pages;

use App\Filament\Resources\WilayahResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWilayahs extends ListRecords
{
    protected static string $resource = WilayahResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
