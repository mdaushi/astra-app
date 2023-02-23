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

    // protected function handleRecordUpdate(Model $record, array $data): Model
    // {
    //     DB::beginTransaction();
    //     try {
    //         $userCreated = $this->updateUserBeforeCreatePegawai($data, $data['user_id']);
    //         // $data['user_id'] = $userCreated->id;
            
    //         $pegawai = static::getModel()::update([
    //             'nama' => $data['nama']
    //         ]);

    //         DB::commit();
    //         return $pegawai;
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //     }
    // }

    // private function updateUserBeforeCreatePegawai(array $data, $id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $user = User::find($id)->update([
    //             'name' => $data['nama'],
    //             'email' => $data['email'],
    //         ])->syncRoles($data['role']);

    //         DB::commit();

    //         return $user;
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //     }
    // }

}
