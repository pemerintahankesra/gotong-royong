<?php

namespace App\Http\Requests\Donatur;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kecamatan' => 'required',
            'kelurahan' => 'required',
            'donatur' => 'required',
            'alamat' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'kecamatan' => 'Kecamatan',
            'kelurahan' => 'Kelurahan',
            'donatur' => 'Donatur',
            'alamat' => 'Alamat',
        ];
    }
}
