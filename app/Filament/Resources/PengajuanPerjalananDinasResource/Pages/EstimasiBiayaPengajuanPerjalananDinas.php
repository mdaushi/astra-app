<?php

namespace App\Filament\Resources\PengajuanPerjalananDinasResource\Pages;

use Filament\Tables;
use Akaunting\Money\Money;
use Filament\Resources\Pages\Page;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Route;
use App\Models\KegiatanPerjalananDinas;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanPerjalananDinas;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use AlperenErsoy\FilamentExport\FilamentExport;
use App\Models\PlanKegiatanPengajuanPerjalananDinas;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Filament\Resources\PengajuanPerjalananDinasResource;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class EstimasiBiayaPengajuanPerjalananDinas extends Page implements Tables\Contracts\HasTable 
{
    use Tables\Concerns\InteractsWithTable; 

    protected static string $resource = PengajuanPerjalananDinasResource::class;

    protected static string $view = 'filament.resources.pengajuan-perjalanan-dinas-resource.pages.estimasi-biaya-pengajuan-perjalanan-dinas';

    public $record;

    public function mount(): void
    {
        $this->record = Route::current()->record;
    }

    protected function getTableQuery(): Builder 
    {
        return KegiatanPerjalananDinas::query()->where('pengajuan_perjalanan_dinas_id', $this->record);
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

    public function totalBiaya() {
        $sum = PlanKegiatanPengajuanPerjalananDinas::where('pengajuan_perjalanan_dinas_id', (int)$this->record)
            ->get()
            ->map(function($item){
                return $item->rate_hotel + $item->transportasi;
            });

        return Money::IDR(collect($sum)->sum());
    }

    public function getTableHeaderActions(): array
    { 
        return [
            FilamentExportHeaderAction::make('Export')->extraViewData([
                'total' => $this->totalBiaya()
            ])->form(function ($action, $livewire): array {
                if ($action->shouldDownloadDirect()) {
                    return [];
                }

                $action->paginator($action->getModel()::exportQuery((int)$this->record)->paginate($livewire->tableRecordsPerPage, ['*'], 'exportPage'));

                return FilamentExport::getFormComponents($action);
            })->action(function ($action, $data): StreamedResponse {
                $action->fillDefaultData($data);

                $records = $action->getModel()::exportQuery((int)$this->record)->get();

                return FilamentExport::callDownload($action, $records, $data);
            })
        ];
    }

}
