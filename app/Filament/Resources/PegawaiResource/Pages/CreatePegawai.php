<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use App\Models\User;
use Filament\Pages\Actions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PegawaiResource;
use Illuminate\Support\Facades\DB;

class CreatePegawai extends CreateRecord
{
    protected static string $resource = PegawaiResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        DB::beginTransaction();
        try {
            $userCreated = $this->createUserBeforeCreatePegawai($data);
            $data['user_id'] = $userCreated->id;
            
            $pegawai = static::getModel()::create($data);
            // $pegawai->addMediaFromDisk($data['picture'], 'public')->toMediaCollection('picture');

            DB::commit();
            return $pegawai;
        } catch (\Throwable $th) {
            DB::rollBack();
        }

    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    private function createUserBeforeCreatePegawai(array $data)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['nama'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ])->assignRole($data['role']);

            $user->sendEmailVerificationNotification();

            DB::commit();

            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
