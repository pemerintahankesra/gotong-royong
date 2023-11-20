<?php

namespace App\Http\Requests\Penarikan;

use Illuminate\Foundation\Http\FormRequest;
use Cart;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal' => 'required|date',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
            'program' => 'required',
            'bank_tujuan' => 'required',
            'rekening_tujuan' => 'required|digits_between:10,18',
            'surat_pengajuan' => 'mimes:pdf|max:2048',
            'keterangan' => 'nullable',
            'cart' => 'required',
        ];
    }

    protected function prepareForValidation(): void
    {
        $cart = Cart::session('penarikan')->getContent();
        $cart_array = $cart->toArray();
        $this->merge([
            'cart' => $cart_array,
        ]);
    }

    public function attributes() : array
    {
        return [
            'tanggal' => 'Tanggal Pengajuan',
            'kecamatan' => 'Kecamatan',
            'kelurahan' => 'Kelurahan',
            'program' => 'Program',
            'rekening_tujuan' => 'Rekening Tujuan',
            'surat_pengajuan' => 'Surat Pengajuan Penarikan Dana',
            'keterangan' => 'Keterangan',
            'cart' => 'Rincian Dana'
        ];
    }
}
