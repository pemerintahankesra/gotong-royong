<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bantuan;
use App\Models\DetilBantuan;
use Auth, DB, DataTables;

class BantuanController extends Controller
{
    public function index()
    {
        return view('bantuan.index');
    }

    public function data(){
        $user = Auth::user();
        $bantuan = DB::table('bantuan as b')->select('b.id', 'kec.name as kecamatan', 'kel.name as kelurahan', 'd.name as donatur', 'd.alamat as alamat')
        ->join('donatur as d', 'd.id', '=', 'b.donatur_id')
        ->join('users as u', 'u.id', '=', 'b.tagged_by')
        ->join('regions as kel', 'kel.id', '=', 'u.region_id')
        ->join('regions as kec', 'kec.id', '=', 'kel.sub_id');
        if($user->role == 2){
        $bantuan = $bantuan->where('kel.sub_id', $user->region_id);
        } else if($user->role == 3){
        $bantuan = $bantuan->where('u.id', $user->id);
        }
        $bantuan = $bantuan->get();

        dd($bantuan);

        $datatable = DataTables::of($bantuan)
        ->addIndexColumn();
        return $datatable->toJson();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
