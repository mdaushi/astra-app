<?php

namespace App\Filament\Resources\ApprovalPaketResource\Pages;

use App\Filament\Resources\ApprovalPaketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovalPakets extends ListRecords
{
    protected static string $resource = ApprovalPaketResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
