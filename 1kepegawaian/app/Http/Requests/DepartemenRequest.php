<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartemenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_departemen' => 'string|max:255',
            'kode_departemen' => 'string|max:255|unique:departemens,kode_departemen',
            'deskripsi' => 'string',
        ];
    }
}
