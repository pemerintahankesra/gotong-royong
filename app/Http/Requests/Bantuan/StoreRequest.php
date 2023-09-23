<?php

namespace App\Http\Requests\Bantuan;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
            'donatur' => 'required',
            'program' => 'required',
            'bukti' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',

            'kategori.*' => 'required_if:kategori,"Barang"',
            'item.*' => 'required_if:kategori,"Barang"',
            'nominal.*' => 'required',
            'total_nominal.*' => 'required_if:kategori,"Barang"',
            'jumlah.*' => 'required_if:kategori,"Barang"',
        ];
    }

    public function attribute(): array
    {

    }
}
