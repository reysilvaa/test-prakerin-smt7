<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'nip', 'email', 'no_telepon', 'alamat', 
        'tanggal_lahir', 'jenis_kelamin', 'departemen_id', 
        'jabatan', 'tanggal_bergabung', 'status_kepegawaian', 'gaji'
    ];

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }
    
    public function user()
    {
        return $this->hasOne(User::class, 'pegawai_id');
    }
}