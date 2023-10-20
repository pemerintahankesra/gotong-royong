<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Region;
use App\Models\Penerima;
use App\Models\Distribusi;
use App\Models\DetilDistribusi;
use App\Http\Requests\Penerima\StoreRequest as PenerimaStoreRequest;
use Cart, Auth, DataTables, DB;

class DistribusiController extends Controller
{
    public function index()
    {
        return view('distribusi.index');
    }

    public function data(){
        $user = Auth::user();
        $distribusi = Distribusi::select('distribusi.id', 'kec.name as kecamatan', 'kel.name as kelurahan', 'p.name as program', 'tanggal')
        // $distribusi = Distribusi::select('distribusi.id')
        ->join('program as p', 'p.id', '=', 'distribusi.program_id')
        ->join('regions as kel', 'kel.id', '=', 'distribusi.tagged_by')
        ->join('regions as kec', 'kec.id', '=', 'kel.sub_id')
        ->with(['detil_distribusi' => function($query){
            $query->join('penerima', 'penerima.id', '=', 'detil_distribusi.penerima_id');
        }]);
        if($user->role == 'Kecamatan'){
        $distribusi = $distribusi->where('kel.sub_id', $user->region_id);
        } else if($user->role == 'Kelurahan'){
        $distribusi = $distribusi->where('u.id', $user->id);
        }
        $distribusi = $distribusi->get();

        $datatable = DataTables::of($distribusi)
        ->addIndexColumn();
        return $datatable->toJson();
    }

    public function create()
    {
        $program = Program::orderBy('id', 'asc')->get();
        $kecamatan = Region::where('level', 1);
        if(Auth::user()->role == 'Kecamatan'){
            $kecamatan = $kecamatan->where('id', Auth::user()->region_id);
        } else if(Auth::user()->role == 'Kelurahan'){
            $kecamatan = $kecamatan->where('id', Auth::user()->wilayah->sub_id);
        }
        $kecamatan = $kecamatan->orderBy('name', 'asc')->get();
        $cart = Cart::session('distribusi-'.Auth::user()->id)->getContent();

        return view('distribusi.create', [
            'program' => $program,
            'kecamatan' => $kecamatan,
            'cart' => $cart,
        ]);
    }

    public function store(Request $request)
    {
        $distribusi = new Distribusi;
        $distribusi->program_id = $request->program;
        $distribusi->tanggal = date('Y-m-d', strtotime($request->tanggal));
        $distribusi->tagged_by = $request->kelurahan;
        $distribusi->save();

        $carts = Cart::session('distribusi-'.Auth::user()->id)->getContent();
        foreach ($carts as $cart){
            foreach($cart->attributes->kategori as $i => $kategori){
                $penerima = Penerima::where('nik', $cart->id)->first();
                if(!$penerima){
                    $penerima = new Penerima;
                    $penerima->region_id = $request->kelurahan;
                    $penerima->nik = $cart->id;
                    $penerima->namalengkap = $cart->name;
                    $penerima->alamatdomisili = $cart->attributes->alamat_domisili;
                    $penerima->kecamatandomisili = $cart->attributes->kecamatan_domisili;
                    $penerima->kelurahandomisili = $cart->attributes->kelurahan_domisili;
                    $penerima->alamatktp = $cart->attributes->alamat_ktp;
                    $penerima->kecamatanktp = $cart->attributes->kecamatan_ktp;
                    $penerima->kelurahanktp = $cart->attributes->kelurahan_ktp;
                    $penerima->flag_surabaya = $cart->attributes->flag_surabaya;
                    $penerima->save();
                }
                
                $detil = new DetilDistribusi;
                $detil->distribusi_id = $distribusi->id;
                $detil->penerima_id = $penerima->id;
                $detil->kategori = $kategori;
                $detil->item = $cart->attributes->item[$i];
                $detil->jumlah = $cart->attributes->jumlah[$i];
                $detil->nominal = str_replace(',', '', $cart->attributes->nominal[$i]);
                $detil->total_nominal = str_replace(',', '', $cart->attributes->total_nominal[$i]);
                $detil->save();
            }
        }

        Cart::session('distribusi-'.Auth::user()->id)->clear();

        return redirect()->route('distribusi.index');
    }

    public function edit(string $id)
    {
        Cart::session('distribusi-'.Auth::user()->id)->clear();

        $program = Program::orderBy('id', 'asc')->get();
        $kecamatan = Region::where('level', 1);
        $kelurahan = Region::where('level', 2);
        if(Auth::user()->role == 'Kecamatan'){
            $kecamatan = $kecamatan->where('id', Auth::user()->region_id);
            $kelurahan = $kelurahan->where('sub_id', Auth::user()->region_id);
        } else if(Auth::user()->role == 'Kelurahan'){
            $kecamatan = $kecamatan->where('id', Auth::user()->wilayah->sub_id);
            $kelurahan = $kelurahan->where('id', Auth::user()->region_id);
        }
        $kecamatan = $kecamatan->orderBy('name', 'asc')->get();
        $kelurahan = $kelurahan->orderBy('name', 'asc')->get();

        $distribusi = Distribusi::findOrFail($id);
        $penerima = DetilDistribusi::select('distribusi_id', 'detil_distribusi.id', 'penerima_id', 'nik', 'namalengkap', 'alamatdomisili', 'kecamatandomisili', 'kelurahandomisili', 'alamatktp', 'kecamatanktp', 'kelurahanktp', 'flag_surabaya', DB::raw('sum(total_nominal) as total'))
        ->join('penerima', 'penerima.id', '=', 'detil_distribusi.penerima_id')
        ->where('distribusi_id', $id)
        ->groupBy('distribusi_id', 'detil_distribusi.id', 'penerima_id', 'nik', 'namalengkap', 'alamatdomisili', 'kecamatandomisili', 'kelurahandomisili', 'alamatktp', 'kecamatanktp', 'kelurahanktp', 'flag_surabaya',)
        ->get();
        foreach($penerima as $pen){
            $detil = DetilDistribusi::select('kategori', 'item', 'jumlah', 'nominal', 'total_nominal')->where(['distribusi_id' => $id, 'penerima_id' => $pen->penerima_id])->get();

            $kategori = [];
            $item = [];
            $jumlah = [];
            $nominal = [];
            $total_nominal = [];

            foreach($detil as $d){
                array_push($kategori, $d->kategori);
                array_push($item, $d->item);
                array_push($jumlah, $d->jumlah);
                array_push($nominal, $d->nominal);
                array_push($total_nominal, $d->total_nominal);
            }

            Cart::session('distribusi-'.Auth::user()->id)->add([
                'id' => $pen->nik,
                'name' => $pen->namalengkap,
                'quantity' => 1,
                'price' => $pen->total,
                'attributes' => [
                    'alamat_domisili' => $pen->alamatdomisili,
                    'alamat_ktp' => $pen->alamatktp,
                    'kecamatan_domisili' => $pen->kecamatandomisili,
                    'kecamatan_ktp' => $pen->kecamatanktp,
                    'kelurahan_domisili' => $pen->kelurahandomisili,
                    'kelurahan_ktp' => $pen->kelurahanktp,
                    'flag_surabaya' => $pen->flag_surabaya,
                    'kategori' => $kategori,
                    'item' => $item,
                    'jumlah' => $jumlah,
                    'nominal' => $nominal,
                    'total_nominal' => $total_nominal,
                ]
            ]);
        }

        return view('distribusi.edit', [
            'program' => $program,
            'kecamatan' => $kecamatan,
            'kelurahan' => $kelurahan,
            'distribusi' => $distribusi,
            'cart' => Cart::session('distribusi-'.Auth::user()->id)->getContent(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $distribusi = Distribusi::findOrFail($id);
        $distribusi->program_id = $request->program;
        $distribusi->tanggal = date('Y-m-d', strtotime($request->tanggal));
        $distribusi->tagged_by = $request->kelurahan;
        $distribusi->save();

        $detil = DetilDistribusi::where('distribusi_id', $id)->delete();

        $carts = Cart::session('distribusi-'.Auth::user()->id)->getContent();
        foreach ($carts as $cart){
            foreach($cart->attributes->kategori as $i => $kategori){
                $penerima = Penerima::where('nik', $cart->id)->first();
                if(!$penerima){
                    $penerima = new Penerima;
                    $penerima->region_id = $request->kelurahan;
                    $penerima->nik = $cart->id;
                    $penerima->namalengkap = $cart->name;
                    $penerima->alamatdomisili = $cart->attributes->alamat_domisili;
                    $penerima->kecamatandomisili = $cart->attributes->kecamatan_domisili;
                    $penerima->kelurahandomisili = $cart->attributes->kelurahan_domisili;
                    $penerima->alamatktp = $cart->attributes->alamat_ktp;
                    $penerima->kecamatanktp = $cart->attributes->kecamatan_ktp;
                    $penerima->kelurahanktp = $cart->attributes->kelurahan_ktp;
                    $penerima->flag_surabaya = $cart->attributes->flag_surabaya;
                    $penerima->save();
                }
                
                $detil = new DetilDistribusi;
                $detil->distribusi_id = $distribusi->id;
                $detil->penerima_id = $penerima->id;
                $detil->kategori = $kategori;
                $detil->item = $cart->attributes->item[$i];
                $detil->jumlah = $cart->attributes->jumlah[$i];
                $detil->nominal = str_replace(',', '', $cart->attributes->nominal[$i]);
                $detil->total_nominal = str_replace(',', '', $cart->attributes->total_nominal[$i]);
                $detil->save();
            }
        }

        Cart::session('distribusi-'.Auth::user()->id)->clear();

        return redirect()->route('distribusi.index');
    }

    public function destroy(string $id)
    {
        $distribusi = Distribusi::findOrFail($id)->delete();

        return redirect()->route('distribusi.index');
    }

    public function create_penerima(){
        return view('distribusi.modal.add_penerima');
    }

    public function penerima(){
        $carts = [];

        Cart::session('distribusi-'.Auth::user()->id)->getContent()->each(function($item) use (&$carts)
        {
            $carts[] = $item;
        });

        return response()->json([
            'jumlah' => count($carts),
            'data' => $carts,
        ]);
    }

    public function store_penerima(PenerimaStoreRequest $request){
        Cart::session('distribusi-'.Auth::user()->id)->add([
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

        return response()->json([
            'success' => true,
            'cart' => Cart::session('distribusi-'.Auth::user()->id)->getContent(),
        ], 200);
    }

    public function edit_penerima($id){
        $cart = Cart::session('distribusi-'.Auth::user()->id)->get($id);

        return view('distribusi.modal.edit_penerima', [
            'cart' => $cart,
            'id' => $id
        ]);
    }

    public function update_penerima(Request $request, $id){
        Cart::session('distribusi-'.Auth::user()->id)->update($id, [
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

    public function destroy_penerima($id){
        Cart::session('distribusi-'.Auth::user()->id)->remove($id);

        return response()->json([
            'message' => 'success',
            'card' => Cart::session('distribusi-'.Auth::user()->id)->getContent(),
        ], 200);
    }
}
