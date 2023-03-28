<?php

namespace App\Filament\Resources\PlanPengajuanPerjalananDinasResource\Pages;

use Filament\Pages\Actions;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanPerjalananDinas;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Models\PlanPengajuanPerjalananDinas;
use App\Filament\Resources\PlanPengajuanPerjalananDinasResource;

class ListPlanPengajuanPerjalananDinas extends ListRecords
{
    protected static string $resource = PlanPengajuanPerjalananDinasResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('kalender')
                ->label('Kalender')
                ->color('success')
                ->url(route('filament.resources.plan-pengajuan-perjalanan-dinas.kalender'))
        ];
    }

    public function ajukanPlan(Model $record): void
    {
        $plan = PlanPengajuanPerjalananDinas::find($record->id);
        $kegiatan = $record->kegiatan;
        
        DB::beginTransaction();
        try {
            $pengajuanReplicate = $plan->replicate()->toArray();

            $pengajuan = PengajuanPerjalananDinas::create($pengajuanReplicate);

            foreach ($kegiatan as $item) {
                $newKegiatan = $item->replicate()->toArray();
                $pengajuan->kegiatan_perjalanan_dinas()->sync([$newKegiatan], false);
            }

            DB::commit();

            Notification::make() 
            ->title('Berhasil diajukan')
            ->success()
            ->send(); 
        } catch (\Throwable $th) {
            DB::rollBack();

            dd($th->getMessage());

            Notification::make() 
            ->title('Gagal diajukan')
            ->danger()
            ->send(); 
        }
    }
}
