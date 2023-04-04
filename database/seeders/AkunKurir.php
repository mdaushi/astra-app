<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;

class AkunKurir extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // POS
        User::create([
            'name' => 'POS',
            'email' => 'pos@gmail.com',
            'password' => Hash::make('1234567890'),
            'email_verified_at' => Carbon::now(),
        ])->assignRole('kurir');

        // Pandusiwi
        User::create([
            'name' => 'Pandusiwi',
            'email' => 'pandusiwi@gmail.com',
            'password' => Hash::make('1234567890'),
            'email_verified_at' => Carbon::now(),
        ])->assignRole('kurir');

        // Jne
        User::create([
            'name' => 'JNE',
            'email' => 'jne@gmail.com',
            'password' => Hash::make('1234567890'),
            'email_verified_at' => Carbon::now(),
        ])->assignRole('kurir');

        // KGP
        User::create([
            'name' => 'KGP',
            'email' => 'kgp@gmail.com',
            'password' => Hash::make('1234567890'),
            'email_verified_at' => Carbon::now(),
        ])->assignRole('kurir');

        // Lion parcel
        User::create([
            'name' => 'Lion parcel',
            'email' => 'lion-parcel@gmail.com',
            'password' => Hash::make('1234567890'),
            'email_verified_at' => Carbon::now(),
        ])->assignRole('kurir');
    }
}
