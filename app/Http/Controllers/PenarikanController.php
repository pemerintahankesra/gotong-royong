<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penarikan;
use App\Models\Program;
use App\Models\Region;
use App\Models\DetilPenarikan;
use Auth, Cart;

class PenarikanController extends Controller
{
    public function index(){
        return view('penarikan.index');
    }

    public function data(){
        $user = Auth::user();
        $penarikan = Penarikan::select('penarikan.id', 'kec.name as kecamatan', 'kel.name as kelurahan', 'p.name as program', 'tanggal')
        ->join('program as p', 'p.id', '=', 'penarikan.program_id')
        ->join('regions as kel', 'kel.id', '=', 'penarikan.tagged_by')
        ->join('regions as kec', 'kec.id', '=', 'kel.sub_id')
        ->with(['detil_penarikan' => function($query){
            $query->join('penerima', 'penerima.id', '=', 'detil_penarikan.penerima_id');
        }]);
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

    public function create(){
        $program = Program::orderBy('id', 'asc')->get();
        $kecamatan = Region::where('level', 1);
        if(Auth::user()->role == 'Kecamatan'){
            $kecamatan = $kecamatan->where('id', Auth::user()->region_id);
        } else if(Auth::user()->role == 'Kelurahan'){
            $kecamatan = $kecamatan->where('id', Auth::user()->wilayah->sub_id);
        }
        $kecamatan = $kecamatan->orderBy('name', 'asc')->get();
        $cart = Cart::session('penarikan-'.Auth::user()->id)->getContent();

        return view('penarikan.create', [
            'program' => $program,
            'kecamatan' => $kecamatan,
            'cart' => $cart,
        ]);
    }

    public function store(){

    }

    public function edit($id){

    }

    public function update(Request $request, $id){

    }

    public function pelaporan(){
        return view('penarikan.pelaporan.index');
    }

    public function data_pelaporan(){
         
    }
}
