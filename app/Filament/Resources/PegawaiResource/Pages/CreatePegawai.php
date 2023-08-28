<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use App\Models\User;
use Filament\Pages\Actions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PegawaiResource;
use Illuminate\Support\Facades\DB;
use Filament\Forms;
use Filament\Resources\Pages\Page;
use Spatie\Permission\Models\Role;

class CreatePegawai extends CreateRecord
{
    protected static string $resource = PegawaiResource::class;
   
    protected function handleRecordCreation(array $data): Model
    {
        DB::beginTransaction();
        try {
            $userCreated = $this->createUserBeforeCreatePegawai($data);
            $data['user_id'] = $userCreated->id;

            $pegawai = static::getModel()::create([
                'nama' => $data['nama'],
                'whatsapp' => $data['whatsapp'],
                'npk' => $data['npk'],
                'golongan_id' => $data['golongan_id'],
                'kode_area' => $data['kode_area'],
                'area' => $data['area'],
                'user_id' => $data['user_id'],
                'is_faktur_ekspedisi' => $data['is_faktur_ekspedisi']
            ]);

            $this->form->model($pegawai)->saveRelationships();
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
