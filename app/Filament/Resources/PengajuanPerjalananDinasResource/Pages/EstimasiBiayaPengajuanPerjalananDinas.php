<?php

namespace App\Filament\Resources\PengajuanPerjalananDinasResource\Pages;

use Filament\Tables;
use Filament\Resources\Pages\Page;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Route;
use App\Models\KegiatanPerjalananDinas;
use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanPerjalananDinas;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PengajuanPerjalananDinasResource;

class EstimasiBiayaPengajuanPerjalananDinas extends Page implements Tables\Contracts\HasTable 
{
    use Tables\Concerns\InteractsWithTable; 

    protected static string $resource = PengajuanPerjalananDinasResource::class;

    protected static string $view = 'filament.resources.pengajuan-perjalanan-dinas-resource.pages.estimasi-biaya-pengajuan-perjalanan-dinas';

    protected function getTableQuery(): Builder 
    {
        $record = Route::current()->record;
        return KegiatanPerjalananDinas::query()->where('pengajuan_perjalanan_dinas_id', $record);
    }

    protected function getTableColumns(): array 
    {
        return [ 
            Tables\Columns\TextColumn::make('tanggal'),
            Tables\Columns\TextColumn::make('dari_kota'),
            Tables\Columns\TextColumn::make('ke_kota'),
            Tables\Columns\TextColumn::make('kegiatan_pokok'),
            Tables\Columns\TextColumn::make('berangkat_jam'),
            Tables\Columns\TextColumn::make('tiba_jam'),
            Tables\Columns\TextColumn::make('maskapai'),
            Tables\Columns\TextColumn::make('payment_via'),
            // Tables\Columns\BadgeColumn::make('status')
            //     ->colors([
            //         'danger' => 'draft',
            //         'warning' => 'reviewing',
            //         'success' => 'published',
            //     ]),
            // Tables\Columns\IconColumn::make('is_featured')->boolean(),
        ]; 
    }
}
