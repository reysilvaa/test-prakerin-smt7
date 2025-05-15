<?php

namespace Database\Factories;

use App\Models\Departemen;
use Illuminate\Database\Eloquent\Factories\Factory;

class PegawaiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'nip' => 'NIP' . fake()->unique()->numerify('##########'),
            'email' => fake()->unique()->safeEmail(),
            'no_telepon' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'tanggal_lahir' => fake()->date('Y-m-d', '-20 years'),
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'departemen_id' => Departemen::factory(),
            'jabatan' => fake()->randomElement(['Manager', 'Supervisor', 'Staff', 'Asisten', 'Operator']),
            'tanggal_bergabung' => fake()->date('Y-m-d', 'now'),
            'status_kepegawaian' => fake()->randomElement(['Tetap', 'Kontrak', 'Magang']),
            'gaji' => fake()->numberBetween(3000000, 15000000)
        ];
    }
}