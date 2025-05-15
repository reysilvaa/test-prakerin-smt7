<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PegawaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'string|max:255',
            'nip' => 'string|max:255|unique:pegawais,nip',
            'email' => 'string|max:255|unique:pegawais,email',
            'no_telepon' => 'string|max:255',
            'alamat' => 'string',
            'tanggal_lahir' => 'date',
            'jenis_kelamin' => 'in:Laki-laki,Perempuan',
            'departemen_id' => 'integer',
            'jabatan' => 'string|max:255',
            'tanggal_bergabung' => 'date',
            'status_kepegawaian' => 'in:Tetap,Kontrak,Magang',
            'gaji' => 'numeric',
        ];
    }
}
