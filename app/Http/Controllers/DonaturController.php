<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, DB, DataTables;
class DonaturController extends Controller
{
  public function index() {
    return view('donatur.index');
  }

  public function data(){
    $user = Auth::user();
    $donatur = DB::table('donatur as d')->select('d.id as id', 'kec.name as kecamatan', 'kel.name as kelurahan', 'd.nama as donatur', 'd.alamat as alamat')->join('regions as kel', 'kel.id', '=', 'd.region_id')->join('regions as kec', 'kec.id', '=', 'kel.sub_id')->orderBy('kec.name', 'asc')->orderBy('kel.name', 'asc');
    if($user->role == 2){
      $donatur = $donatur->where('region.sub_id', $user->region_id);
    } else if($user->role == 3){
      $donatur = $donatur->where('region.id', $user->region_id);
    }
    $donatur = $donatur->get();

    $datatable = DataTables::of($donatur)
    ->addIndexColumn();
    return $datatable->toJson();
  }

  public function create() {

  }

  public function store(Request $request){

  }

  public function edit($id){

  }

  public function update(Request $request, $id){

  }
}
