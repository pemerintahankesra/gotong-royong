<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;

class DataController extends Controller
{
  public function get_kelurahan(Request $request){
    $kelurahan = Region::where('sub_id', $request->kecamatan_id)->get();
    return json_encode([
      'data' => $kelurahan,
    ], 200);
  }
}
