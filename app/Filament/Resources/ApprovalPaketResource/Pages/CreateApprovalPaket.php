<?php

namespace App\Filament\Resources\ApprovalPaketResource\Pages;

use App\Filament\Resources\ApprovalPaketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateApprovalPaket extends CreateRecord
{
    protected static string $resource = ApprovalPaketResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
