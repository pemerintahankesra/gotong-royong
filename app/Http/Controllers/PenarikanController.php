<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penarikan;
use App\Models\Program;
use App\Models\Region;
use App\Models\DetilPenarikan;
// use App\Http\Requests\Penerima\StoreRequest as PenerimaStoreRequest;
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

    public function rencana_realisasi(){
        $carts = [];

        Cart::session('penarikan-'.Auth::user()->id)->getContent()->each(function($item) use (&$carts)
        {
            $carts[] = $item;
        });

        return response()->json([
            'jumlah' => count($carts),
            'data' => $carts,
        ]);
    }

    public function create_rencana_realisasi($kategori){
        if($kategori == 'penerima'){
            return view('penarikan.modals.add_penerima', [
                'kategori' => $kategori,
            ]);
        } else if ($kategori == 'barang'){
            return view('penarikan.modals.add_barang', [
                'kategori' => $kategori,
            ]);
        }
    }

    public function store_rencana_realisasi(Request $request, $kategori){
        if($kategori == 'penerima'){
            $validated = $request->validate([
                'nik' => 'required',
                'nama_penerima' => 'required',
                'alamat_ktp' => 'nullable',
                'alamat_penerima' => 'required',
                'kecamatan_ktp' => 'nullable',
                'kecamatan_penerima' => 'required',
                'kelurahan_ktp' => 'nullable',
                'kelurahan_penerima' => 'required',
                'kategori.*' => 'required',
                'keterangan.*' => 'required',
                'jumlah.*' => 'required',
                'nominal.*' => 'required',
            ]);

            Cart::session('penarikan-'.Auth::user()->id)->add([
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
                    'jenis' => $kategori,
                ]
            ]);
        } else if($kategori == 'barang'){
            $validated = $request->validate([
                'barang' => 'required',
                'jumlah' => 'required',
                'harga_satuan' => 'required',
            ]);

            Cart::session('penarikan-'.Auth::user()->id)->add([
                'id' => substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 3),
                'name' => $request->barang,
                'quantity' => $request->jumlah,
                'price' => $request->total_harga,
                'attributes' => [
                    'nominal' => $request->harga_satuan,
                    'jenis' => $kategori,
                ]
            ]);
        }
        
        return response()->json([
            'success' => true,
            'cart' => Cart::session('penarikan-'.Auth::user()->id)->getContent(),
        ], 200);
    }

    public function edit_rencana_realisasi($kategori, $id){
        $cart = Cart::session('penarikan-'.Auth::user()->id)->get($id);

        return view('distribusi.modal.edit_penerima', [
            'cart' => $cart,
            'id' => $id
        ]);
    }

    public function update_rencana_realisasi_penerima(Request $request, $kategori, $id){
        Cart::session('penarikan-'.Auth::user()->id)->update($id, [
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
            ],
            'price' => $request->totalNominalPerBulan,
        ]);

        return response()->json([
            'success' => true,
            'cart' => Cart::get($id),
        ], 200);
    }

    public function destroy_rencana_realisasi($id){
        Cart::session('penarikan-'.Auth::user()->id)->remove($id);

        return response()->json([
            'message' => 'success',
            'card' => Cart::session('penarikan-'.Auth::user()->id)->getContent(),
        ], 200);
    }
}
