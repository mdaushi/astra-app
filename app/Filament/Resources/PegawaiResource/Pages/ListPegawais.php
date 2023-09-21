<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Golongan;
use Filament\Pages\Actions;
use Illuminate\Support\Facades\Hash;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PegawaiResource;
use Konnco\FilamentImport\Actions\ImportField;
use Konnco\FilamentImport\Actions\ImportAction;

class ListPegawais extends ListRecords
{
    protected static string $resource = PegawaiResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportAction::make()
                // ->massCreate(false)
                ->handleBlankRows(true)

                // ->handleBlankRows(true)
                // ->uniqueField('email')
                ->fields([
                    // jabatan
                    ImportField::make('nama_jabatan')
                        ->required()
                        ->label('Nama Jabatan'),

                    // golongan
                    ImportField::make('nama_golongan')
                        ->required()
                        ->label('Nama Golongan'),
                    ImportField::make('rate_hotel')
                        ->required()
                        ->label('Rate Hotel'),

                    // pegawai
                    ImportField::make('npk')
                        ->required()
                        ->label('NPK'),

                    ImportField::make('nama')
                        ->required()
                        ->label('Nama Pegawai'),

                    ImportField::make('email')
                        ->required()
                        ->label('Email'),

                    ImportField::make('whatsapp')
                        ->required(),

                    ImportField::make('kode_area')
                        ->required()
                        ->label('Kode Area'),

                    ImportField::make('area')
                        ->required()
                        ->label('Area'),

                    ImportField::make('is_faktur_ekspedisi')
                        ->required()
                        ->label('Pegawai Faktur Ekspedisi'),

                    ImportField::make('role')
                        ->required()
                        ->label('Role'),

                    ImportField::make('approval1')
                        ->required()
                        ->label('Approval 1'),

                    ImportField::make('approval2')
                        ->required()
                        ->label('Approval 2'),

                    ImportField::make('approval3')
                        ->required()
                        ->label('Approval 3'),
                ], columns: 2)
                ->handleRecordCreation(function ($data) {

                    $golongan = $this->handleGolonganCreation($data);

                    $jabatan = $this->handleJabatanCreation($data);

                    $pegawai = $this->createOrUpdate($data, $golongan->id, $jabatan);

                    return $pegawai;
                })
        ];
    }

    /**
     * 
     * created golongan
     * 
     * @param  array  $data
     * @return  Golongan
     */
    private function handleGolonganCreation($data): Golongan
    {
        $query = Golongan::query();
        $golongan = $query->where('nama', $data['nama_golongan'])->first();

        if (!$golongan) {
            $golongan = $query->create([
                'nama' => $data['nama_golongan'],
                'rate_hotel' => $data['rate_hotel']
            ]);
        }

        return $golongan;
    }

     /**
     * 
     * created jabatan
     * 
     * @param  array   $data
     * @return  array   jabatan's id
     */
    private function handleJabatanCreation($data): array
    {
        $data = $data['nama_jabatan'];

        $dataArray = explode(",", $data);

        $jabatanIdArray = collect();

        foreach ($dataArray as $item) {
            $item = trim($item);
            $jabatan = Jabatan::where('nama', 'like' , $item)->first();

            if (!$jabatan) {
                $jabatan = Jabatan::create([
                    'nama' => $item
                ]);
            }

            $jabatanIdArray->push($jabatan->id);
        }

        return $jabatanIdArray->toArray();
    }

    /**
     * 
     * checking account exists
     * 
     * @param  string  $email
     * @return bool
     */
    private function isAccountExists($npk): bool
    {
        return ($this->getPegawai($npk))->user()->exists();
    }

    /**
     * 
     * checking pegawai exists
     * 
     * @param  string  $npk
     * @return bool
     */
    private function isPegawaiExists($npk): bool
    {
        return Pegawai::where('npk', $npk)->exists();
    }

     /**
     * 
     * get account user
     * 
     * @param  string  $email
     * @return  User
     */
    private function getAccount($npk): User
    {
        return ($this->getPegawai($npk))->user;
    }

    /**
     * 
     * get pegawai user
     * 
     * @param  string  $npk
     * @return  Pegawai 
     * 
     */
    private function getPegawai($npk): Pegawai
    {
        return Pegawai::where('npk', $npk)->first();
    }

    /**
     * create pegawai or update if exists
     * 
     * @param  array  $data
     * @return  Pegawai
     */
    private function createOrUpdate(array $data, int $golonganId, array $jabatan): Pegawai
    {
        if($this->isPegawaiExists($data['npk']))
        {
            // updated
            return $this->handlePegawaiUpdated($data, $golonganId, $jabatan);
        }
        // created
        return $this->handlePegawaiCreation($data, $golonganId, $jabatan);
    }

    /**
     * create pegawai
     * 
     * @param  array  $data
     * @param  int $golonganId
     * @param  array $jabatan
     * 
     * @return  Pegawai
     */
    private function handlePegawaiCreation(array $data, int $golonganId, array $jabatan): Pegawai
    {
        $user = $this->handleUserCreation($data);

        $pegawai = Pegawai::create([
            'nama' => $data['nama'],
            'npk' => $data['npk'],
            'golongan_id' => $golonganId,
            'kode_area' => $data['kode_area'],
            'area' => $data['area'],
            'user_id' => $user->id,
            'is_faktur_ekspedisi' => $data['is_faktur_ekspedisi'],
            'approval1' => $data['approval1'],
            'approval2' => $data['approval2'],
            'approval3' => $data['approval3'],
            'whatsapp' => $data['whatsapp']
        ]);

        $pegawai->jabatan()->sync($jabatan);
        return $pegawai;
    }

    /**
     * create user account
     * 
     * @param  array $data
     * @return  User
     */
    private function handleUserCreation(array $data): User
    {
        $rolesArrayCollect = $this->rolesStringToArray($data['role']);

        return User::create([
            'name' => $data['nama'],
            'email' => $data['email'],
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('1234567890')
        ])->assignRole($rolesArrayCollect);
    }

    /**
     * update pegawai
     * 
     * @param  array  $data
     * @param  int $golonganId
     * @param  array $jabatan
     * 
     * @return  Pegawai
     */
    private function handlePegawaiUpdated(array $data, int $golonganId, array $jabatan): Pegawai
    {
        $user = $this->handleUserUpdated($data);

        $pegawai = $this->getPegawai($data['npk']);
        $pegawai->update([
            'nama' => $data['nama'],
            'npk' => $data['npk'],
            'golongan_id' => $golonganId,
            'kode_area' => $data['kode_area'],
            'area' => $data['area'],
            'user_id' => $user->id,
            'is_faktur_ekspedisi' => $data['is_faktur_ekspedisi'],
            'approval1' => $data['approval1'],
            'approval2' => $data['approval2'],
            'approval3' => $data['approval3'],
            'whatsapp' => $data['whatsapp']
        ]);
        
        $pegawai->jabatan()->sync($jabatan);
        return $pegawai;
    }

    /**
     * update user account
     * 
     * @param  array $data
     * @return  User
     */
    private function handleUserUpdated(array $data): User
    {        
        $rolesArrayCollect = $this->rolesStringToArray($data['role']);

        $user = $this->getPegawai($data['npk'])->user;
        $user->update([
            'name' => $data['nama'],
            'email' => $data['email']
        ]);
        $user->syncRoles($rolesArrayCollect);

        return $user;
    }

    /**
     * convert roles string to array
     * 
     * @param  string  $role
     * @return  array
     */
    private function rolesStringToArray(string $role): array
    {
        $rolesArray = explode(',', $role);
        $rolesArrayCollect = collect();

        foreach ($rolesArray as $role) {
            $role = trim($role);
            $rolesArrayCollect->push($role);
        }

        return $rolesArrayCollect->toArray();
    }
}
