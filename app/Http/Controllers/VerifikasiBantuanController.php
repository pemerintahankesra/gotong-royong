<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bantuan;
use Auth, DataTables;

class VerifikasiBantuanController extends Controller
{
    public function index(){
        return view('bantuan.verifikasi.index');
    }

    public function data(){
        $user = Auth::user();
        $bantuan = Bantuan::select('bantuan.id', 'kec.name as kecamatan', 'kel.name as kelurahan', 'd.nama as donatur', 'alamat', 'jenis', 'p.name as program', 'approval_bsp', 'tanggal')
        ->join('donatur as d', 'd.id', '=', 'bantuan.donatur_id')
        ->join('program as p', 'p.id', '=', 'bantuan.program_id')
        ->join('regions as kel', 'kel.id', '=', 'bantuan.tagged_by')
        ->join('regions as kec', 'kec.id', '=', 'kel.sub_id')
        ->where('jenis', 'Uang Tunai')
        ->with('detil_bantuan');
        if($user->role == 'Kecamatan'){
        $bantuan = $bantuan->where('kel.sub_id', $user->region_id);
        } else if($user->role == 'Kelurahan'){
        $bantuan = $bantuan->where('u.id', $user->id);
        }
        $bantuan = $bantuan->get();

        $datatable = DataTables::of($bantuan)
        ->addIndexColumn();
        return $datatable->toJson();
    }

    public function edit($id){
        $bantuan = Bantuan::findOrFail($id);

        return view('bantuan.verifikasi.edit', [
            'bantuan' => $bantuan
        ]);
    }

    public function update(Request $request, $id){
        $bantuan = Bantuan::findOrFail($id);
        $bantuan->approval_bsp = $request->hasil_verifikasi;
        $bantuan->keterangan_bsp = $request->keterangan_bsp;
        $bantuan->save();
        
        return redirect()->route('bantuan.verifikasi.index');
    }
}
