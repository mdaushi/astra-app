<?php

namespace App\Filament\Resources\PengajuanPerjalananDinasResource\Pages;

use App\Filament\Resources\PengajuanPerjalananDinasResource;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\DB;

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
}
