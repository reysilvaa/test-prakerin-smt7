<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PegawaiUserSeeder extends Seeder
{
    public function run(): void
    {
        $pegawais = Pegawai::all();
        
        foreach ($pegawais as $pegawai) {
            // Buat user untuk setiap pegawai dengan password default
            User::create([
                'name' => $pegawai->nama,
                'email' => $pegawai->email,
                'password' => Hash::make('pegawai123'),
                'role' => 'pegawai',
                'pegawai_id' => $pegawai->id,
            ]);
        }
    }
}