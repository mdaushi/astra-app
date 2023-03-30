<?php

namespace App\Filament\Resources\ApprovalPaketResource\Pages;

use App\Filament\Resources\ApprovalPaketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApprovalPaket extends EditRecord
{
    protected static string $resource = ApprovalPaketResource::class;

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
