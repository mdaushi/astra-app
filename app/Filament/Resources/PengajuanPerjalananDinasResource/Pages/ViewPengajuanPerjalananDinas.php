<?php

namespace App\Filament\Resources\PengajuanPerjalananDinasResource\Pages;

use App\Filament\Resources\PengajuanPerjalananDinasResource;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

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
}
