<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;

    protected $fillable = ['nama_departemen', 'kode_departemen', 'deskripsi'];

    public function pegawais()
    {
        return $this->hasMany(Pegawai::class);
    }
}