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

    public function instanceWithqrcode(){

        $status = watifier::statusSession();
        
        if($status['error']){
            watifier::initInstanceQrcode();
            $status = watifier::statusSession();
        }

        $qrcode = watifier::getQrcode();

        $displaying = "<img class='text-center' src='" . $qrcode['qrcode'] . "'> </br> <p class'text-center'>Jika terdapat masalah saat scan qrcode, silahkan restart dan mulai ulang.</p>";

        if($qrcode['qrcode'] ==""){
            $displaying = "<p class='text-bold text-center text-xl'>Tunggu beberapa saat...</p>";
        }

        if($qrcode['qrcode'] == " "){
            $displaying = "<p class='text-bold text-center text-xl text-red-500'>Gagal memuat Qrcode atau telah expired. silahkan restart dan mulai ulang</p>";
        }

        if($status['instance_data']['user']){
            $displaying = '<p class="text-bold text-center text-xl">Perangkat telah tersambung</p>';
        }

        return $displaying;
    }


}
