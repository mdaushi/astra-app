<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use App\Models\User;
use Filament\Pages\Actions;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PegawaiResource;
use Filament\Support\Actions\Concerns\CanCustomizeProcess;


class EditPegawai extends EditRecord
{
    use CanCustomizeProcess;

    protected static string $resource = PegawaiResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('Delete')
                ->label(__('filament-support::actions/delete.single.label'))
                ->modalHeading(fn (): string => __('filament-support::actions/delete.single.modal.heading', ['label' => $this->getRecordTitle()]))
                ->modalButton(__('filament-support::actions/delete.single.modal.actions.delete.label'))
                ->color('danger')
                ->successNotificationTitle(__('filament-support::actions/delete.single.messages.deleted'))
                ->requiresConfirmation()
                ->groupedIcon('heroicon-s-trash')
                ->keyBindings(['mod+d'])
                ->action('delete')
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        DB::beginTransaction();
        try {
            $this->updateUserBeforeUpdatePegawai($data, $record['user_id']);
            // $data['user_id'] = $userCreated->id;
            $record->update($data);

            DB::commit();
            return $record;
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    private function updateUserBeforeUpdatePegawai(array $data, $id)
    {
        $user = User::find($id);
        $user->name = $data['nama'];
        $user->syncRoles($data['role']);
        $user->save();       
    }

    public function beforeDelete()
    {
        $this->getRecord()->user()->delete();
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

}
