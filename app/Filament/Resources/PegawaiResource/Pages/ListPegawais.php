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

                    // record golongan
                    $golongan = $this->handleGolonganCreation($data);

                    // // record jabatan
                    $jabatan = $this->handleJabatanCreation($data);


                    // roles string to array
                    $rolesArray = explode(',', $data['role']);
                    $rolesArrayCollect = collect();

                    foreach ($rolesArray as $role) {
                        $role = trim($role);
                        $rolesArrayCollect->push($role);
                    }

                    // record akun
                    $user = User::create([
                        'name' => $data['nama'],
                        'email' => $data['email'],
                        'email_verified_at' => Carbon::now(),
                        'password' => Hash::make('1234567890')
                    ])->assignRole($rolesArrayCollect);

                    $pegawai = Pegawai::create([
                        'nama' => $data['nama'],
                        'npk' => $data['npk'],
                        'golongan_id' => $golongan->id,
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
                })
        ];
    }

    private function handleGolonganCreation($data)
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

    private function handleJabatanCreation($data)
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

        return $jabatanIdArray;
    }

    private function handlePegawaiCreation($data, $golongan, $jabatan)
    {
        $user = $this->handleUserCreation($data);
        return Pegawai::updateOrCreate(
            ['npk', $data['npk']],
            [
                'nama' => $data['nama'],
                // 'npk' => $data['npk'],
                'golongan_id' => $golongan->id,
                'jabatan_id' => $jabatan->id,
                'kode_area' => $data['kode_area'],
                'area' => $data['area'],
                'user_id' => $user->id,
                'is_faktur_ekspedisi' => $data['is_faktur_ekspedisi'],
                'approval1' => $data['approval1'],
                'approval2' => $data['approval2'],
                'approval3' => $data['approval3']
            ]
        );
    }

    private function handleUserCreation($data)
    {
        $user = User::create([
            'name' => $data['nama'],
            'email' => $data['email'],
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('1234567890')
        ])->assignRole($data['role']);

        return $user;
    }
}
