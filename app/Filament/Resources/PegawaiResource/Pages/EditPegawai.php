<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use App\Models\User;
use Filament\Pages\Actions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PegawaiResource;

class EditPegawai extends EditRecord
{
    protected static string $resource = PegawaiResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
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

}
