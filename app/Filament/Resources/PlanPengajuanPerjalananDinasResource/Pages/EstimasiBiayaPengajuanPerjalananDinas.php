<?php

namespace App\Filament\Resources\PlanPengajuanPerjalananDinasResource\Pages;

use Akaunting\Money\Money;
use App\Models\User;
use Filament\Tables;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use AlperenErsoy\FilamentExport\FilamentExport;
use App\Models\PlanKegiatanPengajuanPerjalananDinas;
use Illuminate\Database\Eloquent\Relations\Relation;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Filament\Resources\PlanPengajuanPerjalananDinasResource;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class EstimasiBiayaPengajuanPerjalananDinas extends Page implements Tables\Contracts\HasTable 
{
    use Tables\Concerns\InteractsWithTable; 

    protected static string $resource = PlanPengajuanPerjalananDinasResource::class;

    protected static string $view = 'filament.resources.plan-pengajuan-perjalanan-dinas-resource.pages.estimasi-biaya-pengajuan-perjalanan-dinas';

    public $record;

    public function mount(): void
    {
        $this->record = Route::current()->record;
    }

    public function getTableQuery(): Builder
    {
        return PlanKegiatanPengajuanPerjalananDinas::query()
            ->where('pengajuan_perjalanan_dinas_id', $this->record);
    }

    protected function getTableColumns(): array 
    {
        return [ 
            Tables\Columns\TextColumn::make('kegiatan_pokok'),
            Tables\Columns\TextColumn::make('rate_hotel')
                ->label('Hotel')
                ->money('idr'),
            Tables\Columns\TextColumn::make('transportasi')->money('idr'),
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
