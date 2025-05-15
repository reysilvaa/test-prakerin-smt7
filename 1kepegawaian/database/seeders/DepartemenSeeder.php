<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'nama_departemen' => 'Human Resources',
                'kode_departemen' => 'HR001',
                'deskripsi' => 'Departemen yang mengelola sumber daya manusia'
            ],
            [
                'nama_departemen' => 'Finance',
                'kode_departemen' => 'FIN001',
                'deskripsi' => 'Departemen yang mengelola keuangan perusahaan'
            ],
            [
                'nama_departemen' => 'Information Technology',
                'kode_departemen' => 'IT001',
                'deskripsi' => 'Departemen yang mengelola teknologi informasi'
            ],
            [
                'nama_departemen' => 'Marketing',
                'kode_departemen' => 'MKT001',
                'deskripsi' => 'Departemen yang mengelola pemasaran'
            ],
            [
                'nama_departemen' => 'Operations',
                'kode_departemen' => 'OPS001',
                'deskripsi' => 'Departemen yang mengelola operasional perusahaan'
            ]
        ];

        foreach ($departments as $dept) {
            Departemen::create($dept);
        }
    }
}