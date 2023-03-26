<?php

namespace App\Filament\Resources\PengajuanPerjalananDinasResource\Pages;

use Carbon\Carbon;
use App\Models\Golongan;
use App\Models\Rekening;
use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PengajuanPerjalananDinasResource;

class EditPengajuanPerjalananDinas extends EditRecord
{
    protected static string $resource = PengajuanPerjalananDinasResource::class;

    protected function getActions(): array
    {
        return [
            actions\Action::make('Delete')
                ->label(__('filament-support::actions/delete.single.label'))
                ->modalHeading(fn (): string => __('filament-support::actions/delete.single.modal.heading', ['label' => $this->getRecordTitle()]))
                ->modalButton(__('filament-support::actions/delete.single.modal.actions.delete.label'))
                ->color('danger')
                ->successNotificationTitle(__('filament-support::actions/delete.single.messages.deleted'))
                ->requiresConfirmation()
                ->groupedIcon('heroicon-s-trash')
                ->keyBindings(['mod+d'])
                ->action('delete'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_id'] = auth()->id();

        // $bank = $this->processRekening($data['bank']);

        // $data['nama_bank'] = $bank['nama'];
        // $data['no_rekening'] = $bank['rekening'];

        $data['pegawai_id'] = auth()->user()->pegawai->id;

        $data['sign_user_at'] = Carbon::now();
    
        return $data;
    }

    protected function processRekening(string $rekening): array
    {
        $rekening = Rekening::where('rekening', $rekening)->first();

        return [
            'nama' => $rekening->nama,
            'rekening' => $rekening->rekening
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        $kegiatanAll = [];
        foreach ($data['kegiatan'] as $item) {
            $item['pengajuan_perjalanan_dinas_id'] = $record->id;

            $rate_hotel = $this->findRateHotel();
            $item['rate_hotel'] = $rate_hotel;
            
            array_push($kegiatanAll, $item);
        }


        $record->kegiatan_perjalanan_dinas()->sync($kegiatanAll);
    
        return $record;
    }

    public function beforeDelete()
    {
        $this->getRecord()->kegiatan_perjalanan_dinas()->delete();
    }

    public function delete(): void
    {
        abort_unless(static::getResource()::canDelete($this->getRecord()), 403);
        
        $this->callHook('beforeDelete');

        $this->getRecord()->delete();

        $this->callHook('afterDelete');

        $this->getDeletedNotification()?->send();

        $this->redirect($this->getDeleteRedirectUrl());
    }

    protected function findRateHotel() {
        $golonganUser = auth()->user()->pegawai->golongan->id;
        $hotel = Golongan::find($golonganUser)->rate_hotel;
        return $hotel;
    }

}
