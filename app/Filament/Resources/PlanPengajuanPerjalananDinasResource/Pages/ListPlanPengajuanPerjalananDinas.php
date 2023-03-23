<?php

namespace App\Filament\Resources\PlanPengajuanPerjalananDinasResource\Pages;

use App\Filament\Resources\PlanPengajuanPerjalananDinasResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanPengajuanPerjalananDinas extends ListRecords
{
    protected static string $resource = PlanPengajuanPerjalananDinasResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
