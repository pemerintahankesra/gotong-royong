<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Region;
use Cart, Auth;

class DistribusiController extends Controller
{
    public function index()
    {
        return view('distribusi.index');
    }

    public function create($jenis)
    {
        if($jenis == 'uang' || $jenis == 'barang'){
            $program = Program::orderBy('id', 'asc')->get();
            $kecamatan = Region::where('level', 1)->orderBy('name', 'asc')->get();
            $cart = Cart::session(Auth::user()->id)->getContent();

            return view('distribusi.create', [
                'jenis' => $jenis,
                'program' => $program,
                'kecamatan' => $kecamatan,
                'cart' => $cart,
            ]);
        }

        return abort(404);
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

    public function create_penerima(){
        return view('distribusi.modal.add_penerima');
    }

    public function penerima(){
        $carts = [];

        Cart::session(Auth::user()->id)->getContent()->each(function($item) use (&$carts)
        {
            $carts[] = $item;
        });

        return json_encode([
            'jumlah' => count($carts),
            'data' => $carts,
        ]);
    }

    public function store_penerima(Request $request){
        Cart::session(Auth::user()->id)->add([
            'id' => $request->nik,
            'name' => $request->nama_penerima,
            'quantity' => 1,
            'price' => $request->totalNominalPerBulan,
            'attributes' => [
                'alamat_domisili' => $request->alamat_penerima,
                'alamat_ktp' => $request->alamat_ktp,
                'kecamatan_domisili' => $request->kecamatan_penerima,
                'kecamatan_ktp' => $request->kecamatan_ktp,
                'kelurahan_domisili' => $request->kelurahan_penerima,
                'kelurahan_ktp' => $request->kelurahan_ktp,
                'flag_surabaya' => $request->flag_surabaya,
                'kategori' => $request->kategori,
                'item' => $request->item,
                'jumlah' => $request->jumlah,
                'nominal' => $request->nominal,
                'total_nominal' => $request->total_nominal,
            ]
        ]);

        return json_encode([
            'message' => 'success',
            'cart' => Cart::getContent(),
        ], 200);
    }

    public function destroy_penerima($id){
        Cart::session(Auth::user()->id)->remove($id);

        return json_encode([
            'message' => 'success',
            'card' => Cart::getContent(),
        ], 200);
    }
}
