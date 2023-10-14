<?php

namespace App\Http\Requests\Penerima;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nik' => 'required',
            'nama_penerima' => 'required',
            'alamat_ktp' => 'nullable',
            'alamat_penerima' => 'required',
            'kecamatan_ktp' => 'nullable',
            'kecamatan_penerima' => 'required',
            'kelurahan_ktp' => 'nullable',
            'kelurahan_penerima' => 'required',
            'kategori.*' => 'required',
            'keterangan.*' => 'required',
            'jumlah.*' => 'required',
            'nominal.*' => 'required',
        ];
    }
}
