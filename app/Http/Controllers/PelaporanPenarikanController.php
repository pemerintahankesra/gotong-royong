<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penarikan;
use App\Models\DetilPenarikan;
use Auth, DB, DataTables, Cart;

class PelaporanPenarikanController extends Controller
{
    public function index(){
        return view('penarikan.pelaporan.index');
    }

    public function data(){
        $user = Auth::user();
        $penarikan = Penarikan::select('penarikan.id', 'kec.name as kecamatan', 'kel.name as kelurahan', 'p.name as program', 'tanggal_pengajuan', 'status_laporan', DB::raw('SUM(total_nominal) as total'))
        ->join('program as p', 'p.id', '=', 'penarikan.program_id')
        ->join('regions as kel', 'kel.id', '=', 'penarikan.tagged_by')
        ->join('regions as kec', 'kec.id', '=', 'kel.sub_id')
        ->join('detil_penarikan as dp', 'penarikan.id', '=', 'dp.penarikan_id')
        ->groupBy('penarikan.id', 'kecamatan', 'kelurahan', 'program', 'tanggal_pengajuan')
        ->with(['detil_penarikan' => function($query){
            $query->join('penerima', 'penerima.id', '=', 'detil_penarikan.penerima_id');
        }])
        ->where('approval_bsp', 12);
        if($user->role == 'Kecamatan'){
            $penarikan = $penarikan->where('kel.sub_id', $user->region_id);
        } else if($user->role == 'Kelurahan'){
            $penarikan = $penarikan->where('u.id', $user->id);
        }
        $penarikan = $penarikan->get();

        $datatable = DataTables::of($penarikan)
        ->addIndexColumn();
        return $datatable->toJson();
    }

    public function edit($id){
        $penarikan = Penarikan::findOrFail($id);

        return view('penarikan.pelaporan.edit', [
            'penarikan' => $penarikan,
        ]);
    }

    public function laporan($id){
        $detil = DetilPenarikan::findOrFail($id);
        return view('penarikan.modals.add_laporan', [
            'detil' => $detil,
        ]);
    }

    public function update(Request $request, $id){
        $detil = DetilPenarikan::findOrFail($id);
        $path_upload_laporan = $request->upload_laporan->store('laporan', 'public');
        $detil->foto_laporan = $path_upload_laporan;
        $detil->save();

        return response()->json([
            'success' => true,
            'detil' => $detil,
        ], 200);
    }
}