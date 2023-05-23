<?php

namespace App\Filament\Resources\PengajuanPerjalananDinasResource\Pages;

use Carbon\Carbon;
use Filament\Pages\Actions;
use App\Events\ApprovalProcessed;
use App\Events\ApprovedProcessed;
use App\Events\PDBarengProcessed;
use App\Events\RejectedProcessed;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\PengajuanPerjalananDinasResource;
use App\Http\Controllers\ExportController;

class ViewPengajuanPerjalananDinas extends ViewRecord
{
    protected static string $resource = PengajuanPerjalananDinasResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('submit')
                ->color('success')
                ->requiresConfirmation()
                ->modalContent(view('filament.resources.pengajuan-perjalanan-dinas-resource.actions.submit'))
                ->action('submit')
                ->hidden(fn (): bool => $this->record->sign_user_at != null),
            Actions\EditAction::make()
                ->hidden(fn (): bool => $this->record->sign_user_at != null),
            Actions\Action::make('approve')
                ->color('success')
                ->requiresConfirmation()
                ->action('approve')
                ->modalSubheading('Sebelum approve, pasikan anda telah membaca seluruh isi pengajuan ini')
                ->hidden(fn(): bool => !$this->record->roleCanApproved())
                ->disabled(fn(): bool => $this->record->disableByRole()),

            Actions\Action::make('reject')
                ->color('danger')
                ->requiresConfirmation()
                ->action('reject')
                ->hidden(fn(): bool => !$this->record->roleCanApproved())
                ->disabled(fn(): bool => $this->record->disableRejected())
                ->modalSubheading('Tolak Pengajuan ini?'),

            Actions\Action::make('print')
                ->url(function(){
                    return route('export', ['id' => $this->record]);
                })
                ->openUrlInNewTab()
            ];
    }

    public function submit(): void
    {
        try {
            $this->record->sign_user_at = Carbon::now();
            $this->record->save();
            
            Notification::make()
                ->title('Pengajuan berhasil disubmit')
                ->success()
                ->send();

        } catch (\Throwable $th) {
            Notification::make()
                ->title('Error, ada kendala')
                ->danger()
                ->send();
        }
    }

    public function approve(): void
    {
        $roleUser = auth()->user()->roles()->first()->name;
        DB::beginTransaction();
        try {
            $this->record->processApprove(strtolower($roleUser));

            // send mail approval
            ApprovalProcessed::dispatch($this->record);

            // send notif to user
            ApprovedProcessed::dispatch($this->record);
            
            // send notif pd bersama
            PDBarengProcessed::dispatch($this->record);
            
            DB::commit();
            Notification::make()
                ->title('Pengajuan berhasil diapprove')
                ->success()
                ->send();

        } catch (\Throwable $th) {
            DB::rollBack();
            Notification::make()
                ->title('Error, ada kendala')
                ->danger()
                ->send();
        }
    }

    public function reject(): void
    {
        DB::beginTransaction();
        try {
            $this->record->rejected_at = Carbon::now();
            $this->record->save();

            RejectedProcessed::dispatch($this->record);

            DB::commit();

            Notification::make()
                ->title('Pengajuan ditolak')
                ->success()
                ->send();
        } catch (\Throwable $th) {
            DB::rollBack();
            Notification::make()
                ->title('Error, ada kendala')
                ->danger()
                ->send();
        }
    }

    public function print(): void
    {
        $datas = $this->record;
        app()->call([new ExportController, 'export'], ['datas' => $datas]);
        
        Notification::make()
                ->title('Pengajuan terdownload')
                ->success()
                ->send();
    }
}
