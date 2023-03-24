<?php

namespace App\Filament\Resources\PlanPengajuanPerjalananDinasResource\Pages;

use Filament\Resources\Pages\Page;
use App\Filament\Resources\PlanPengajuanPerjalananDinasResource;
use App\Models\PlanKegiatanPengajuanPerjalananDinas;

class KalenderPengajuanPerjalananDinas extends Page
{
    protected static string $resource = PlanPengajuanPerjalananDinasResource::class;
    protected static string $view = 'filament.resources.plan-pengajuan-perjalanan-dinas-resource.pages.kalender-pengajuan-perjalanan-dinas';

    public function data()
    {
        $datas = PlanKegiatanPengajuanPerjalananDinas::with('pengajuan_perjalanan_dinas')->get()->map(function($item){
            return [
                'id' => $item->id,
                'name' => $item->pengajuan_perjalanan_dinas->nama,
                'description' => $item->dari_kota . ' - '. $item->ke_kota . ' ('. $item->kegiatan_pokok .')',
                'date' => $item->tanggal,
                'type' => 'event',
                'color' => fake()->hexColor()
            ];
        });    

        return $datas;
    }
}
