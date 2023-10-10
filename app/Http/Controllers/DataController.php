<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Donatur;
use App\Models\Penerima;
use App\Models\GR\Stunting;

class DataController extends Controller
{
  public function get_kelurahan(Request $request){
    $kelurahan = Region::where('sub_id', $request->kecamatan_id)->get();
    return json_encode([
      'data' => $kelurahan,
    ], 200);
  }

  public function get_donatur(Request $request){
    $donatur = Donatur::where('region_id', $request->kelurahan_id)->get();
    return json_encode([
      'data' => $donatur,
    ], 200);
  }

  public function get_asw_id(Request $request){
    $region = Region::findOrFail($request->id);
    return json_encode([
      'data' => $region,
    ]);
  }

  public function get_cekin(Request $request){
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.surabaya.go.id/integrasi/api/data-warga',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('nik' => $request->nik),
      CURLOPT_HTTPHEADER => array(
        'user: cekin_bapemkesra',
        'password: uJWnt638'
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $data = json_decode($response);

    if($data->data){
      $penerima = $data->data[0];
      $penerima->flag_surabaya = 0;
      $penerima->cek_program = Penerima::join('detil_distribusi', 'penerima.id', '=', 'detil_distribusi.penerima_id')
      ->join('distribusi', 'distribusi.id', '=', 'detil_distribusi.distribusi_id')
      ->where('distribusi.program_id', '<>', $request->program_id)
      ->where('nik', $request->nik)->count();

      return response()->json([
        'message' => 'success',
        'data' => $penerima,
      ]);
    } else {
      $stunting = Stunting::where('nik', $request->nik)->orderBy('tanggal_penarikan', 'DESC')->first();
      if($stunting){
        $penerima = new \stdClass;
        $penerima->no_kk = $stunting->no_kk;
        $penerima->nik = $stunting->nik;
        $penerima->nama = $stunting->namalengkap;
        $penerima->alamat_dom = $stunting->alamatdomisili;
        $penerima->kecamatan_dom = $stunting->kecamatandomisili;
        $penerima->kelurahan_dom = $stunting->kelurahandomisili;
        $penerima->flag_surabaya = 1;
        $penerima->cek_program = Penerima::join('detil_distribusi', 'penerima.id', '=', 'detil_distribusi.penerima_id')
        ->join('distribusi', 'distribusi.id', '=', 'detil_distribusi.distribusi_id')
        ->where('distribusi.program_id', '<>', $request->program_id)
        ->where('nik', $request->nik)->count();

        return response()->json([
          'message' => 'success',
          'data' => $penerima,
        ]);
      } else {
        return response()->json([
          'message' => 'error',
          'data' => 'Not Found',
        ]);
      }
    }
  }

  public function get_balita_stunting(Request $request){
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.surabaya.go.id/integrasi/api/sayang-warga/balita-stunting',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('id_kecamatan' => $request->kecamatan, 'id_kelurahan' => $request->kelurahan),
      CURLOPT_HTTPHEADER => array(
        'user: asw_bapem',
        'password: WDKL564z'
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($response);
    $balita = $data->data;

    return response()->json([
      'message' => 'success',
      'data' => $balita,
    ]);
  }

  public function get_permakanan(Request $request){
    $penerima = Penerima::select('penerima.region_id','penerima.nik','penerima.name','penerima.address','penerima.kelurahan','penerima.kecamatan')
    ->join('tagging', 'tagging.penerima_id', '=', 'penerima.id')
    ->join('regions', 'regions.id', '=', 'tagging.tagged_by')
    ->where(['program_id' => 2]);
    if($request->kelurahan_id){
      $penerima = $penerima->where('regions.asw_id', $request->kelurahan_id);
    }
    $penerima = $penerima->distinct()->get();

    return response()->json([
      'message' => 'success',
      'data' => $penerima,
    ]);
  }

  public function get_beasiswa(Request $request){
    $penerima = Penerima::select('penerima.region_id','penerima.nik','penerima.name','penerima.address','penerima.kelurahan','penerima.kecamatan')
    ->join('tagging', 'tagging.penerima_id', '=', 'penerima.id')
    ->join('regions', 'regions.id', '=', 'tagging.tagged_by')
    ->where(['program_id' => 3]);
    if($request->kelurahan_id){
      $penerima = $penerima->where('regions.asw_id', $request->kelurahan_id);
    }
    $penerima = $penerima->distinct()->get();

    return response()->json([
      'message' => 'success',
      'data' => $penerima,
    ]);
  }

  public function get_rutilahu(Request $request){
    $penerima = Penerima::select('penerima.region_id','penerima.nik','penerima.name','penerima.address','penerima.kelurahan','penerima.kecamatan')
    ->join('tagging', 'tagging.penerima_id', '=', 'penerima.id')
    ->join('regions', 'regions.id', '=', 'tagging.tagged_by')
    ->where(['program_id' => 4]);
    if($request->kelurahan_id){
      $penerima = $penerima->where('regions.asw_id', $request->kelurahan_id);
    }
    $penerima = $penerima->distinct()->get();

    return response()->json([
      'message' => 'success',
      'data' => $penerima,
    ]);
  }

  public function get_jamban(Request $request){
    $penerima = Penerima::select('penerima.region_id','penerima.nik','penerima.name','penerima.address','penerima.kelurahan','penerima.kecamatan')
    ->join('tagging', 'tagging.penerima_id', '=', 'penerima.id')
    ->join('regions', 'regions.id', '=', 'tagging.tagged_by')
    ->where(['program_id' => 5]);
    if($request->kelurahan_id){
      $penerima = $penerima->where('regions.asw_id', $request->kelurahan_id);
    }
    $penerima = $penerima->distinct()->get();

    return response()->json([
      'message' => 'success',
      'data' => $penerima,
    ]);
  }

  public function get_bumil_resti(Request $request){
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.surabaya.go.id/integrasi/api/sayang-warga/bumil-resiko-tinggi',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('id_kecamatan' => $request->kecamatan_id, 'id_kelurahan' => $request->kelurahan_id),
      CURLOPT_HTTPHEADER => array(
        'user: asw_bapem',
        'password: WDKL564z'
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($response);
    $balita = $data->data;

    return response()->json([
      'message' => 'success',
      'data' => $balita,
    ]);
  }
}
