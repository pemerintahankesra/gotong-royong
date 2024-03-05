<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenarikanController extends Controller
{
    public function store(Request $request){
        $attributes = [
            'tanggal_pengajuan' => 'Tanggal pengajuan',
            'kelurahan' => 'Kelurahan',
            'program' => 'Program',
            'bank_pencairan' => 'Bank tujuan pencairan',
            'rekening_pencairan' => 'Rekening tujuan pencairan',
            'surat_permohonan' => 'Surat permohonan penarikan dana ke BSP',
            'keterangan' => 'Keterangan tambahan',
        ];

        $validator = Validator::make($request->all(), [
            'tanggal_pengajuan' => 'required|date',
            'kelurahan' => 'required',
            'program' => 'required',
            'bank_pencairan' => 'required|min:3',
            'rekening_pencairan' => 'required|numeric|min_digits:8|max_digits:20',
            'surat_penarikan_dana' => 'required|image|max:2042',
            'keterangan' => 'nullable|alpha_num',
          ]);
  
          $validator->setAttributeNames($attributes);
  
          if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
          }
    
        return response()->json([
            'message' => 'success',
            'data' => $request,
        ]);
    }
}
