<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penarikan;
use App\Models\Penerima;
use App\Models\DetilPenarikan;
use Illuminate\Support\Facades\Validator;
use DB, Storage, App;

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
    
    $data_bantuan = json_decode($request->data_bantuan);
    if(empty($data_bantuan)){
      return response()->json(['errors' => ['data_bantuan' => 'Daftar bantuan belum diisikan']], 422);
    }

    DB::beginTransaction();
    try {

      $penarikan = new Penarikan;
      $penarikan->program_id = $request->program;
      $penarikan->tagged_by = $request->kelurahan;
      $penarikan->tanggal_pengajuan = $request->tanggal_pengajuan;
      $penarikan->keterangan = $request->keterangan;
      $penarikan->rekening_tujuan_pencairan = $request->rekening_pencairan;
      $penarikan->bank_tujuan_pencairan = $request->bank_pencairan;
      $path_surat_pengajuan = $request->surat_penarikan_dana->store('penarikan/surat', 'public');
      $penarikan->surat_pengajuan = $path_surat_pengajuan;
      $penarikan->save();
  
      foreach($data_bantuan as $bantuan){
        if($bantuan->kategori == 'penerima'){
          $penerima = Penerima::where('nik', $bantuan->penerima->nik)->first();
          if(!$penerima){
            $penerima = new Penerima;
            $penerima->region_id = $request->kelurahan;
            $penerima->nik = $bantuan->penerima->nik;
            $penerima->namalengkap = $bantuan->penerima->nama;
            $penerima->alamatdomisili = $bantuan->penerima->alamat_domisili;
            $penerima->kecamatandomisili = $bantuan->penerima->kecamatan_domisili;
            $penerima->kelurahandomisili = $bantuan->penerima->kelurahan_domisili;
            $penerima->alamatktp = $bantuan->penerima->alamat_ktp;
            $penerima->kecamatanktp = $bantuan->penerima->kecamatan_ktp;
            $penerima->kelurahanktp = $bantuan->penerima->kelurahan_ktp;
            $penerima->save();
          }
          foreach($bantuan->barang as $barang){
            $detil_penarikan = new DetilPenarikan;
            $detil_penarikan->penarikan_id = $penarikan->id;
            $detil_penarikan->penerima_id = $penerima->id;
            $detil_penarikan->kategori = $barang->jenis;
            $detil_penarikan->item = $barang->nama_item;
            $detil_penarikan->jumlah = str_replace(',', '', $barang->jumlah);
            $detil_penarikan->nominal = str_replace(',', '', $barang->harga_satuan);
            $detil_penarikan->total_nominal = str_replace(',', '', $bantuan->total);
            $detil_penarikan->jenis = $bantuan->kategori;
            $detil_penarikan->save();
          }
        } else if($bantuan->kategori == 'barang'){
          $detil_penarikan = new DetilPenarikan;
          $detil_penarikan->penarikan_id = $penarikan->id;
          $detil_penarikan->penerima_id = null;
          $detil_penarikan->kategori = $bantuan->barang[0]->jenis;
          $detil_penarikan->item = $bantuan->barang[0]->nama_item;
          $detil_penarikan->jumlah = str_replace(',', '', $bantuan->barang[0]->jumlah);
          $detil_penarikan->nominal = str_replace(',', '', $bantuan->barang[0]->harga_satuan);
          $detil_penarikan->total_nominal = str_replace(',', '', $bantuan->total);
          $detil_penarikan->jenis = $bantuan->kategori;
          $detil_penarikan->save();
          $penerima = null;
        }
      }

      DB::commit();
  
      return response()->json([
          'message' => 'success',
          'result' => [
            'rencana_penarikan' => $penarikan,
            'bantuan' => $data_bantuan,
            'penerima' => $penerima,
          ],
      ]);
    } catch(\Exception $e){
      DB::rollback();
      return response()->json(['warning' => $e->getMessage()], 500);
    }
  }

  public function update(Request $request, $id){
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
      'surat_penarikan_dana' => 'image|max:2042',
      'keterangan' => 'nullable|alpha_num',
    ]);

    $validator->setAttributeNames($attributes);

    if($validator->fails()){
      return response()->json(['errors' => $validator->errors()], 422);
    }
    
    $data_bantuan = json_decode($request->data_bantuan);
    if(empty($data_bantuan)){
      return response()->json(['errors' => ['data_bantuan' => 'Daftar bantuan belum diisikan']], 422);
    }

    DB::beginTransaction();
    try {
      $old_penarikan = Penarikan::findOrFail($id);

      $penarikan = new Penarikan;
      $penarikan->program_id = $request->program;
      $penarikan->tagged_by = $request->kelurahan;
      $penarikan->tanggal_pengajuan = $request->tanggal_pengajuan;
      $penarikan->keterangan = $request->keterangan;
      $penarikan->rekening_tujuan_pencairan = $request->rekening_pencairan;
      $penarikan->bank_tujuan_pencairan = $request->bank_pencairan;
      $penarikan->surat_pengajuan = $old_penarikan->surat_pengajuan;
      $penarikan->save();
  
      foreach($data_bantuan as $bantuan){
        if($bantuan->kategori == 'penerima'){
          $penerima = Penerima::where('nik', $bantuan->penerima->nik)->first();
          if(!$penerima){
            $penerima = new Penerima;
            $penerima->region_id = $request->kelurahan;
            $penerima->nik = $bantuan->penerima->nik;
            $penerima->namalengkap = $bantuan->penerima->nama;
            $penerima->alamatdomisili = $bantuan->penerima->alamat_domisili;
            $penerima->kecamatandomisili = $bantuan->penerima->kecamatan_domisili;
            $penerima->kelurahandomisili = $bantuan->penerima->kelurahan_domisili;
            $penerima->alamatktp = $bantuan->penerima->alamat_ktp;
            $penerima->kecamatanktp = $bantuan->penerima->kecamatan_ktp;
            $penerima->kelurahanktp = $bantuan->penerima->kelurahan_ktp;
            $penerima->save();
          }
          foreach($bantuan->barang as $barang){
            $detil_penarikan = new DetilPenarikan;
            $detil_penarikan->penarikan_id = $penarikan->id;
            $detil_penarikan->penerima_id = $penerima->id;
            $detil_penarikan->kategori = $barang->jenis;
            $detil_penarikan->item = $barang->nama_item;
            $detil_penarikan->jumlah = str_replace(',', '', $barang->jumlah);
            $detil_penarikan->nominal = str_replace(',', '', $barang->harga_satuan);
            $detil_penarikan->total_nominal = str_replace(',', '', $bantuan->total);
            $detil_penarikan->jenis = $bantuan->kategori;
            $detil_penarikan->save();
          }
        } else if($bantuan->kategori == 'barang'){
          $detil_penarikan = new DetilPenarikan;
          $detil_penarikan->penarikan_id = $penarikan->id;
          $detil_penarikan->penerima_id = null;
          $detil_penarikan->kategori = $bantuan->barang[0]->jenis;
          $detil_penarikan->item = $bantuan->barang[0]->nama_item;
          $detil_penarikan->jumlah = str_replace(',', '', $bantuan->barang[0]->jumlah);
          $detil_penarikan->nominal = str_replace(',', '', $bantuan->barang[0]->harga_satuan);
          $detil_penarikan->total_nominal = str_replace(',', '', $bantuan->total);
          $detil_penarikan->jenis = $bantuan->kategori;
          $detil_penarikan->save();
          $penerima = null;
        }
      }

      $old_penarikan->delete();

      DB::commit();
  
      return response()->json([
          'message' => 'success',
          'result' => [
            'rencana_penarikan' => $penarikan,
            'bantuan' => $data_bantuan,
            'penerima' => $penerima,
          ],
      ]);
    } catch(\Exception $e){
      DB::rollback();
      return response()->json(['warning' => $e->getMessage()], 500);
    }
  }
}
