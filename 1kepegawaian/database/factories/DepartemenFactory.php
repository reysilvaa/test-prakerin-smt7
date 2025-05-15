<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartemenFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_departemen' => fake()->company(),
            'kode_departemen' => 'DEP' . fake()->unique()->numerify('###'),
            'deskripsi' => fake()->paragraph()
        ];
    }
}