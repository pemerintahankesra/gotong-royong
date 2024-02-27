<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penarikan;
use App\Models\Penerima;
use App\Models\Program;
use App\Models\Region;
use App\Models\DetilPenarikan;
use App\Http\Requests\Penarikan\StoreRequest as PenarikanStoreRequest;
use App\Http\Requests\Penarikan\UpdateRequest as PenarikanUpdateRequest;
use Auth, Cart, Storage, DataTables, DB;

class PenarikanController extends Controller
{
    public function index(){
        return view('penarikan.index');
    }

    public function data(){
        $user = Auth::user();
        $penarikan = Penarikan::select('penarikan.id', 'kec.name as kecamatan', 'kel.name as kelurahan', 'p.name as program', 'tanggal_pengajuan', 'approval_bsp', 'tagged_by', DB::raw('SUM(total_nominal) as total'))
        ->join('program as p', 'p.id', '=', 'penarikan.program_id')
        ->join('regions as kel', 'kel.id', '=', 'penarikan.tagged_by')
        ->join('regions as kec', 'kec.id', '=', 'kel.sub_id')
        ->join('detil_penarikan as dp', 'penarikan.id', '=', 'dp.penarikan_id')
        ->groupBy('penarikan.id', 'kecamatan', 'kelurahan', 'program', 'tanggal_pengajuan')
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
        ->addIndexColumn()
        ->addColumn('action', function($query){
            $html = '<button class="btn btn-sm btn-ligt mx-1 border" onclick="modal_detil_pengajuan(\''.$query->id.'\')">Rincian '.$query->approval_bsp.'</button>';
            if(Auth::user()->role == 'BSP'){
                if($query->approval_bsp == 0 || $query->approval_bsp == 20){
                    $html .= '<a href="'.url('/penarikan/verifikasi/'.$query->id).'" class="btn btn-primary btn-sm mx-1">Verifikasi</a>';
                }
                if($query->approval_bsp == 11){
                    $html .= '<a href="'.url('/penarikan/verifikasi/'.$query->id.'/upload_bukti_tf').'" class="btn btn-primary btn-sm mx-1">Upload Bukti TF BSP</a>';
                }
            }
            if(Auth::user()->id == $query->tagged_by){
                if($query->approval_bsp == 0){
                    if($query->approval_bsp == 0 || $query->approval_bsp == 21){
                        $html .= '<a href="'.route('penarikan.edit', $query->id).'" class="btn btn-warning btn-sm mx-1">Edit</a>';
                    }

                    $html .= '<form method="post" action="'.route('penarikan.destroy', $query->id).'"> <input type="hidden" name="_token" value="'.csrf_token().'"> <input type="hidden" name="_method" value="DELETE"><button class="btn btn-danger btn-sm mx-1">Hapus</button></form>';
                }
            }
            return $html;
        })
        ->rawColumns(['action']);
        return $datatable->toJson();
    }

    public function show($id){
        $penarikan = Penarikan::findOrFail($id);

        return view('penarikan.modals.show', [
            'penarikan' => $penarikan,
        ]);
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
        $cart = Cart::session('penarikan')->getContent();

        return view('penarikan.create', [
            'program' => $program,
            'kecamatan' => $kecamatan,
            'cart' => $cart,
        ]);
    }

    public function store(PenarikanStoreRequest $request){
        $user = Auth::user();

        $penarikan = new Penarikan;
        $penarikan->program_id = $request->program;
        $penarikan->tagged_by = $request->kelurahan;
        $penarikan->tanggal_pengajuan = $request->tanggal;
        $penarikan->keterangan = $request->keterangan;
        $path_surat_pengajuan = $request->surat_pengajuan->store('penarikan/surat', 'public');
        $penarikan->rekening_tujuan_pencairan = $request->rekening_tujuan;
        $penarikan->bank_tujuan_pencairan = $request->bank_tujuan;
        $penarikan->surat_pengajuan = $path_surat_pengajuan;
        $penarikan->save();

        $carts = Cart::session('penarikan')->getContent();
        foreach($carts as $cart){
            if($cart->attributes->jenis == 'penerima'){
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
                foreach($cart->attributes->kategori as $ia => $kategori){
                    $detil_penarikan = new DetilPenarikan;
                    $detil_penarikan->penarikan_id = $penarikan->id;
                    $detil_penarikan->penerima_id = $penerima->id;
                    $detil_penarikan->kategori = $cart->attributes->kategori[$ia];
                    $detil_penarikan->item = $cart->attributes->item[$ia];
                    $detil_penarikan->jumlah = str_replace(',', '', $cart->attributes->jumlah[$ia]);
                    $detil_penarikan->nominal = str_replace(',', '', $cart->attributes->nominal[$ia]);
                    $detil_penarikan->total_nominal = str_replace(',', '', $cart->attributes->total_nominal[$ia]);
                    $detil_penarikan->jenis = $cart->attributes->jenis;
                    $detil_penarikan->save();
                }
            } else if($cart->attributes->jenis == 'barang'){
                $detil_penarikan = new DetilPenarikan;
                $detil_penarikan->penarikan_id = $penarikan->id;
                $detil_penarikan->kategori = 'Lain-lain';
                $detil_penarikan->item = $cart->name;
                $detil_penarikan->jumlah = $cart->quantity;
                $detil_penarikan->nominal = str_replace(',', '', $cart->attributes->nominal);
                $detil_penarikan->total_nominal = str_replace(',', '', $cart->price);
                $detil_penarikan->jenis = $cart->attributes->jenis;
                $detil_penarikan->save();
            }

        }
        
        Cart::session('penarikan')->clear();

        return redirect()->route('penarikan.index');
    }

    public function edit($id){
        $penarikan = Penarikan::findOrFail($id);
        $program = Program::orderBy('id', 'asc')->get();
        $kecamatan = Region::where('level', 1);
        $kelurahan = Region::where('level', 2)->where('sub_id', $penarikan->region->sub_id)->orderBy('name', 'asc')->get();
        if(Auth::user()->role == 'Kecamatan'){
            $kecamatan = $kecamatan->where('id', Auth::user()->region_id);
            $kecamatan = $kecamatan->where('id', Auth::user()->region_id);
        } else if(Auth::user()->role == 'Kelurahan'){
            $kecamatan = $kecamatan->where('id', Auth::user()->wilayah->sub_id);
        }
        $kecamatan = $kecamatan->orderBy('name', 'asc')->get();

        $cart = Cart::session('penarikan')->getContent();
        if(count($cart) == 0){
            $master_dp = DetilPenarikan::select('jenis', 'penerima_id')->where('penarikan_id', $penarikan->id)->distinct()->get();
    
            foreach($master_dp as $mdp){
                if($mdp->jenis == 'penerima'){
                    $penerima = DetilPenarikan::select('penerima.*')
                    ->join('penerima', 'penerima.id', '=', 'detil_penarikan.penerima_id')
                    ->where(['penarikan_id' => $penarikan->id, 'jenis' => 'penerima'])
                    ->distinct()
                    ->get();
    
                    foreach($penerima as $pen){
                        $detil = DetilPenarikan::where([
                            'penarikan_id' => $penarikan->id,
                            'penerima_id' => $pen->id,
                        ])->get();
    
                        $kategori = array(); 
                        $item = array();
                        $jumlah = array();
                        $nominal = array();
                        $total_nominal = array();
    
                        foreach($detil as $i => $det){
                            $kategori[$i] = $det->kategori;
                            $item[$i] = $det->item;    
                            $jumlah[$i] = $det->jumlah;    
                            $nominal[$i] = $det->nominal;    
                            $total_nominal[$i] = $det->total_nominal;    
                        }
    
                        Cart::session('penarikan')->add([
                            'id' => $pen->nik,
                            'name' => $pen->namalengkap,
                            'quantity' => 1,
                            'price' => $detil->sum('total_nominal'),
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
                                'jenis' => 'penerima',
                            ]
                        ]);
                    }
                } else {
                    $detil = DetilPenarikan::where([
                        'jenis' => 'barang',
                        'penarikan_id' => $penarikan->id,
                    ])->get();
        
                    foreach($detil as $det){
                        Cart::session('penarikan')->add([
                           'id' => substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 3),
                           'name' => $det->item,
                           'quantity' => $det->jumlah,
                           'price' => $det->total_nominal,
                           'attributes' => [
                            'nominal' => $det->nominal,
                            'jenis' => $det->jenis,
                            'kategori' => 'Lain-lain'
                           ],
                        ]);
                    }
                }
            }
        }


        return view('penarikan.edit', [
            'program' => $program,
            'kecamatan' => $kecamatan,
            'kelurahan' => $kelurahan,
            'penarikan' => $penarikan,
        ]);
    }

    public function update(PenarikanUpdateRequest $request, $id){
        $penarikan = Penarikan::findOrFail($id);
        $penarikan->program_id = $request->program;
        $penarikan->tagged_by = $request->kelurahan;
        $penarikan->tanggal_pengajuan = $request->tanggal;
        $penarikan->keterangan = $request->keterangan;
        if($request->surat_pengajuan){
            $path_surat_pengajuan = $request->surat_pengajuan->store('penarikan/surat', 'public');
            $penarikan->surat_pengajuan = $path_surat_pengajuan;
        }
        if($penarikan->approval_bsp == '21'){
            $penarikan->approval_bsp = '20';
        }
        $penarikan->rekening_tujuan_pencairan = $request->rekening_tujuan;
        $penarikan->bank_tujuan_pencairan = $request->bank_tujuan;
        $penarikan->save();

        DetilPenarikan::where('penarikan_id', $id)->delete();

        $carts = Cart::session('penarikan')->getContent();
        foreach($carts as $cart){
            if($cart->attributes->jenis == 'penerima'){
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
                foreach($cart->attributes->kategori as $ia => $kategori){
                    $detil_penarikan = new DetilPenarikan;
                    $detil_penarikan->penarikan_id = $penarikan->id;
                    $detil_penarikan->penerima_id = $penerima->id;
                    $detil_penarikan->kategori = $cart->attributes->kategori[$ia];
                    $detil_penarikan->item = $cart->attributes->item[$ia];
                    $detil_penarikan->jumlah = str_replace(',', '', $cart->attributes->jumlah[$ia]);
                    $detil_penarikan->nominal = str_replace(',', '', $cart->attributes->nominal[$ia]);
                    $detil_penarikan->total_nominal = str_replace(',', '', $cart->attributes->total_nominal[$ia]);
                    $detil_penarikan->jenis = $cart->attributes->jenis;
                    $detil_penarikan->save();
                }
            } else if($cart->attributes->jenis == 'barang'){
                $detil_penarikan = new DetilPenarikan;
                $detil_penarikan->penarikan_id = $penarikan->id;
                $detil_penarikan->kategori = 'Lain-lain';
                $detil_penarikan->item = $cart->name;
                $detil_penarikan->jumlah = $cart->quantity;
                $detil_penarikan->nominal = str_replace(',', '', $cart->attributes->nominal);
                $detil_penarikan->total_nominal = str_replace(',', '', $cart->price);
                $detil_penarikan->jenis = $cart->attributes->jenis;
                $detil_penarikan->save();
            }

        }
        
        Cart::session('penarikan')->clear();

        return redirect()->route('penarikan.index');
    }

    public function destroy($id){
        $penarikan = Penarikan::findOrFail($id)->delete();

        return redirect()->route('penarikan.index');
    }

    public function pelaporan(){
        return view('penarikan.pelaporan.index');
    }

    public function data_pelaporan(){
         
    }

    public function rencana_realisasi(){
        $carts = [];

        Cart::session('penarikan')->getContent()->each(function($item) use (&$carts)
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

            Cart::session('penarikan')->add([
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

            Cart::session('penarikan')->add([
                'id' => substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 5),
                'name' => $request->barang,
                'quantity' => $request->jumlah,
                'price' => $request->total_harga,
                'attributes' => [
                    'nominal' => str_replace(',', '', $request->harga_satuan),
                    'jenis' => $kategori,
                ]
            ]);
        }
        
        return response()->json([
            'success' => true,
            'cart' => Cart::session('penarikan')->getContent(),
        ], 200);
    }

    public function edit_rencana_realisasi($kategori, $id){
        $cart = Cart::session('penarikan')->get($id);

        if($kategori == 'penerima'){
            return view('penarikan.modals.edit_penerima', [
                'cart' => $cart,
                'id' => $id, 
                'kategori' => $kategori,
            ]);
        } elseif($kategori == 'barang'){
            return view('penarikan.modals.edit_barang', [
                'cart' => $cart,
                'id' => $id, 
                'kategori' => $kategori,
            ]);
        }

    }

    public function update_rencana_realisasi(Request $request, $kategori, $id){
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

            Cart::session('penarikan')->update($id, [
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

            Cart::session('penarikan')->update($id, [
                'name' => $request->barang,
                'quantity' => [
                    'relative' => false,
                    'value' => $request->jumlah,
                ],
                'price' => str_replace(',','',$request->total_harga),
                'attributes' => [
                    'nominal' => str_replace(',','',$request->harga_satuan),
                    'jenis' => $kategori,
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'cart' => Cart::get($id),
        ], 200);
    }

    public function destroy_rencana_realisasi($id){
        Cart::session('penarikan')->remove($id);

        return response()->json([
            'message' => 'success',
            'card' => Cart::session('penarikan')->getContent(),
        ], 200);
    }
}
