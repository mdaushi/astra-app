<?php

namespace App\Filament\Resources\WatifierResource\Pages;

use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\WatifierResource;
use App\Models\watifier;

class ManageWatifiers extends ManageRecords
{
    protected static string $resource = WatifierResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return watifier::getSession();
    }

    public function qrcode(){

        $status = watifier::statusSession();
        if($status['error']){
            // init instance
            watifier::initInstanceQrcode();
            sleep(5);
        }

        $qrcode = watifier::getQrcode();

        return $qrcode['qrcode'];
    }


}
