<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 20 pegawai secara otomatis menggunakan factory
        Pegawai::factory()->count(20)->create();
    }
}