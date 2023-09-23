<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Region;

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

            return view('distribusi.create', [
                'jenis' => $jenis,
                'program' => $program,
                'kecamatan' => $kecamatan,
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
}
