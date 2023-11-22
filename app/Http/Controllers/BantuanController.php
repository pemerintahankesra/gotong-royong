<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bantuan;
use App\Models\DetilBantuan;
use App\Models\Region;
use App\Models\Program;
use App\Models\Donatur;
use App\Http\Requests\Bantuan\StoreRequest;
use Auth, DB, DataTables, Storage;

class BantuanController extends Controller
{
    public function index()
    {
        return view('bantuan.index');
    }

    public function data(){
        $user = Auth::user();
        $bantuan = Bantuan::select('bantuan.id', 'kec.name as kecamatan', 'kel.name as kelurahan', 'd.nama as donatur', 'alamat', 'jenis', 'p.name as program', 'approval_bsp', 'tanggal')
        ->join('donatur as d', 'd.id', '=', 'bantuan.donatur_id')
        ->join('program as p', 'p.id', '=', 'bantuan.program_id')
        ->join('regions as kel', 'kel.id', '=', 'bantuan.tagged_by')
        ->join('regions as kec', 'kec.id', '=', 'kel.sub_id')
        ->orderBy('bantuan.tanggal', 'desc')
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

    public function create($kategori)
    {
        if($kategori == 'uang' || $kategori == 'barang'){
            $kecamatan = Region::where(['level' => 1, 'status' => 1]);
            $kelurahan = Region::where(['level' => 2, 'status' => 1]);
            if(Auth::user()->role == 'Kecamatan'){
                $kecamatan = $kecamatan->where('id', Auth::user()->region_id);
                $kelurahan = $kelurahan->where('sub_id', Auth::user()->region_id);
            } else if(Auth::user()-> role == 'Kelurahan'){
                $kecamatan = $kecamatan->where('id', Auth::user()->wilayah->sub_id);
                $kelurahan = $kelurahan->where('id', Auth::user()->region_id);
            }
            $kecamatan = $kecamatan->orderBy('name', 'asc')->get();
            $kelurahan = $kelurahan->orderBy('name', 'asc')->get();
            $program = Program::orderBy('id')->get();

            return view(($kategori == 'uang' ? 'bantuan.create_uang' : 'bantuan.create_barang'), [
                'kecamatan' => $kecamatan,
                'kelurahan' => $kelurahan,
                'program' => $program,
            ]);
        }

        return abort(404);
    }

    public function store(StoreRequest $request)
    {
        if($request->jenis == 'Uang Tunai' || $request->jenis == 'Barang'){
            $bantuan = new Bantuan;
            $bantuan->donatur_id = $request->donatur;
            $bantuan->tagged_by = $request->kelurahan;
            $bantuan->program_id = $request->program;
            $bantuan->jenis = $request->jenis;
            if($request->jenis == 'Barang'){
                $bantuan->approval_bsp = 1;
            } else if($request->jenis == 'Uang Tunai'){
                $bantuan->approval_bsp = 0;
            }
            $bantuan->tanggal = $request->tanggal;
            $bukti = $request->bukti;
            $path_bukti = $bukti->store('gotong_royong/stunting', 'public');
            $bantuan->bukti = $path_bukti;
            $bantuan->keterangan = $request->keterangan;
            $bantuan->save();
            
            if($request->jenis == 'Uang Tunai'){
                $uang = str_replace(',', '', $request->nominal[0]);
                $detil = new DetilBantuan;
                $detil->bantuan_id = $bantuan->id;
                $detil->kategori = 'Uang';
                $detil->item = 'Uang Tunai';
                $detil->jumlah = 1;
                $detil->nominal = $uang;
                $detil->total_nominal = $uang;
                $detil->save();
            } else if($request->jenis == 'Barang') {
                foreach($request->kategori as $i => $kategori){
                    $detil = new DetilBantuan;
                    $detil->bantuan_id = $bantuan->id;
                    $detil->kategori = $kategori;
                    $detil->item = $request->item[$i];
                    $detil->jumlah = $request->jumlah[$i];
                    $detil->nominal = str_replace(',', '', $request->nominal[$i]);
                    $detil->total_nominal = str_replace(',', '', $request->total_nominal[$i]);
                    $detil->save();
                }
            } 

            return redirect()->route('bantuan.index');
        }

        return abort(404);
    }

    public function edit(string $id)
    {
        $bantuan = Bantuan::findOrFail($id);
        $kecamatan = Region::where('level', 1)->get();
        $kelurahan = Region::where('level', 2)->where('sub_id', $bantuan->donatur->kelurahan->sub_id)->get();
        $donatur = Donatur::select('donatur.*')->join('regions', 'regions.id', '=', 'donatur.region_id')->where('region_id', $bantuan->donatur->region_id)->get();
        $program = Program::all();

        return view(($bantuan->jenis == 'Uang Tunai' ? 'bantuan.edit_uang' : 'bantuan.edit_barang'), [
            'bantuan' => $bantuan,
            'kecamatan' => $kecamatan,
            'kelurahan' => $kelurahan,
            'donatur' => $donatur,
            'program' => $program,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $bantuan = Bantuan::findOrFail($id);
        $bantuan->donatur_id = $request->donatur;
        $bantuan->tagged_by = $request->kelurahan;
        $bantuan->program_id = $request->program;
        $bantuan->jenis = $request->jenis;
        if($bantuan->approval_bsp == 21){
            $bantuan->approval_bsp = 20;
        }
        $bantuan->tanggal = $request->tanggal;
        $bukti = $request->bukti;
        if($bukti){
            Storage::disk('public')->delete($bangunan->bukti);

            $path_bukti = $bukti->store('gotong_royong/stunting', 'public');
            $bantuan->bukti = $path_bukti;
        }
        $bantuan->keterangan = $request->keterangan;
        $bantuan->save();

        if($request->jenis == 'Uang Tunai'){
            $uang = str_replace(',', '', $request->nominal[0]);
            $detil = DetilBantuan::where('bantuan_id', $id)->first();
            $detil->bantuan_id = $id;
            $detil->kategori = 'Uang';
            $detil->item = 'Uang Tunai';
            $detil->jumlah = 1;
            $detil->nominal = $uang;
            $detil->total_nominal = $uang;
            $detil->save();
        } else if($request->jenis == 'Barang') {
            $detil = DetilBantuan::where('bantuan_id', $id)->delete();
            foreach($request->kategori as $i => $kategori){
                $detil = new DetilBantuan;
                $detil->bantuan_id = $id;
                $detil->kategori = $kategori;
                $detil->item = $request->item[$i];
                $detil->jumlah = $request->jumlah[$i];
                $detil->nominal = str_replace(',', '', $request->nominal[$i]);
                $detil->total_nominal = str_replace(',', '', $request->total_nominal[$i]);
                $detil->save();
            }
        }

        return redirect()->route('bantuan.index');
        
    }

    public function destroy(string $id)
    {
        $bantuan = Bantuan::findOrFail($id)->delete();
        return redirect()->route('bantuan.index');
    }
}
