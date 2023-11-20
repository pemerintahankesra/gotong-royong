<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penarikan;
use App\Models\DetilPenarikan;
use Cart;

class VerifikasiPenarikanController extends Controller
{
    public function create($id){
        $penarikan = Penarikan::findOrFail($id);
        Cart::session('approval')->clear();
        $master_dp = DetilPenarikan::select('jenis', 'penerima_id')->where('penarikan_id', $penarikan->id)->distinct()->get();

        foreach($master_dp as $mdp){
            if($mdp->jenis == 'penerima'){
                $penerima = DetilPenarikan::select('penerima.*')
                ->join('penerima', 'penerima.id', '=', 'detil_penarikan.penerima_id')
                ->where(['penarikan_id' => $penarikan->id, 'jenis' => 'penerima', 'penerima_id' => $mdp->penerima_id])
                ->distinct()
                ->get();

                $i = 0;
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
                    
                    Cart::session('approval')->add([
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
            } 
            else {
                $detil = DetilPenarikan::where([
                    'jenis' => 'barang',
                    'penarikan_id' => $penarikan->id,
                ])->get();
                foreach($detil as $det){
                    Cart::session('approval')->add([
                       'id' => $det->id,
                       'name' => $det->item,
                       'quantity' => $det->jumlah,
                       'price' => $det->nominal,
                       'attributes' => [
                        'total_nominal' => $det->total_nominal,
                        'jenis' => $det->jenis,
                        'kategori' => 'Lain-lain'
                       ],
                    ]);
                }
            }
        }

        return view('penarikan.approvals.create', [
            'penarikan' => $penarikan,
        ]);
    }

    public function store(Request $request, $id){
        $penarikan = Penarikan::findOrFail($id);
        $penarikan->approval_bsp = $request->hasil_verifikasi;
        $penarikan->keterangan_bsp = $request->keterangan_bsp;
        $penarikan->save();

        return redirect()->route('penarikan.index');
    }

    public function upload_bukti_tf($id){
        $penarikan = Penarikan::findOrFail($id);
        Cart::session('approval')->clear();
        $master_dp = DetilPenarikan::select('jenis', 'penerima_id')->where('penarikan_id', $penarikan->id)->distinct()->get();

        foreach($master_dp as $mdp){
            if($mdp->jenis == 'penerima'){
                $penerima = DetilPenarikan::select('penerima.*')
                ->join('penerima', 'penerima.id', '=', 'detil_penarikan.penerima_id')
                ->where(['penarikan_id' => $penarikan->id, 'jenis' => 'penerima', 'penerima_id' => $mdp->penerima_id])
                ->distinct()
                ->get();

                $i = 0;
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
                    
                    Cart::session('approval')->add([
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
            } 
            else {
                $detil = DetilPenarikan::where([
                    'jenis' => 'barang',
                    'penarikan_id' => $penarikan->id,
                ])->get();
                foreach($detil as $det){
                    Cart::session('approval')->add([
                       'id' => $det->id,
                       'name' => $det->item,
                       'quantity' => $det->jumlah,
                       'price' => $det->nominal,
                       'attributes' => [
                        'total_nominal' => $det->total_nominal,
                        'jenis' => $det->jenis,
                        'kategori' => 'Lain-lain'
                       ],
                    ]);
                }
            }
        }

        return view('penarikan.approvals.upload_bukti_setor', [
            'penarikan' => $penarikan,
        ]);
    }

    public function update(Request $request, $id){
        $penarikan = Penarikan::findOrFail($id);
        $path_bukti_pencairan = $request->bukti_pencairan->store('penarikan/bukti_pencairan_bsp', 'public');
        $penarikan->bukti_pencairan = $path_bukti_pencairan;
        $penarikan->approval_bsp = 12;
        $penarikan->save();

        return redirect()->route('penarikan.index');
    }
}
