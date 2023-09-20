<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donatur;
use App\Models\Region;
use App\Http\Requests\Donatur\StoreRequest;
use App\Http\Requests\Donatur\UpdateRequest;
use Auth, DB, DataTables;
class DonaturController extends Controller
{
  public function index() {
    return view('donatur.index');
  }

  public function data(){
    $user = Auth::user();
    $donatur = DB::table('donatur as d')->select('d.id as id', 'kec.name as kecamatan', 'kel.name as kelurahan', 'd.nama as donatur', 'd.alamat as alamat')->join('regions as kel', 'kel.id', '=', 'd.region_id')->join('regions as kec', 'kec.id', '=', 'kel.sub_id')->orderBy('kec.name', 'asc')->orderBy('kel.name', 'asc')->whereNull('deleted_at');
    if($user->role == 2){
      $donatur = $donatur->where('kel.sub_id', $user->region_id);
    } else if($user->role == 3){
      $donatur = $donatur->where('kel.id', $user->region_id);
    }
    $donatur = $donatur->get();

    $datatable = DataTables::of($donatur)
    ->addIndexColumn();
    return $datatable->toJson();
  }

  public function create() {
    $kecamatan = Region::where('level', 1)->orderBy('name', 'asc')->get();

    return view('donatur.create', [
      'kecamatan' => $kecamatan,
    ]);
  }

  public function store(StoreRequest $request){
    $donatur = new Donatur;
    $donatur->region_id = $request->kelurahan;
    $donatur->nama = $request->donatur;
    $donatur->alamat = $request->alamat;
    $donatur->saveOrFail();

    return redirect()->route('donatur.index');
  }

  public function edit(string $id){
    $donatur = Donatur::findOrFail($id);
    $kecamatan = Region::where('level', 1)->orderBy('name', 'asc')->get();
    $kelurahan = Region::where('level', 2)->where('sub_id', $donatur->kelurahan->sub_id)->orderBy('name', 'asc')->get();
    return view('donatur.edit', [
      'donatur' => $donatur,
      'kecamatan' => $kecamatan,
      'kelurahan' => $kelurahan,
    ]);
  }

  public function update(UpdateRequest $request, string $id){
    $donatur = Donatur::findOrFail($id);
    $donatur->region_id = $request->kelurahan;
    $donatur->nama = $request->donatur;
    $donatur->alamat = $request->alamat;
    $donatur->saveOrFail();

    return redirect()->route('donatur.index');
  }

  public function destroy(string $id){
    $donatur = Donatur::findOrFail($id);
    $donatur->delete();

    return redirect()->route('donatur.index');
  }
}
