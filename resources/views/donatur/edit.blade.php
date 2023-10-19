@extends('template')

@section('title', 'Edit Donatur')

@section('css_plugins')
<link rel="stylesheet" href="assets/plugins/select2-4.0.13/dist/css/select2.min.css">
    <link rel="stylesheet" href="assets/plugins/select2-bootstrap-5-theme-1.3.0/dist/select2-bootstrap-5-theme.min.css">
@endsection

@section('content')
<div class="pagetitle">
  <h1>Edit Donatur</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('dashboards.index')}}">Home</a></li>
      <li class="breadcrumb-item">Pages</li>
      <li class="breadcrumb-item"><a href="{{route('donatur.index')}}">Donatur</a></li>
      <li class="breadcrumb-item active">Edit Donatur</li>
    </ol>
  </nav>
</div>
<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body pt-2">
          <form action="{{route('donatur.update', $donatur->id)}}" class="row g-3" method="post">
            @csrf
            @method('PUT')
            <div class="col-md-6">
              <label for="kecamatan" class="form-label">Kecamatan</label>
              <select name="kecamatan" id="kecamatan" class="form-select mb-2 @error('kecamatan')is-invalid @enderror" onchange="get_kelurahan(this.value)">
                <option></option>
                @foreach($kecamatan as $kec)
                <option value="{{$kec->id}}" {{old('kecamatan') ? 'selected' : ($donatur->kelurahan->sub_id == $kec->id ? 'selected' : '')}}>Kec. {{$kec->name}}</option>
                @endforeach
              </select>
              @error('kecamatan')
              <div class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="col-md-6">
              <label for="kelurahan" class="form-label">Kelurahan</label>
              <select name="kelurahan" id="kelurahan" class="form-select mb-2 @error('kelurahan')is-invalid @enderror">
                <option></option>
                @foreach($kelurahan as $kel)
                <option value="{{$kel->id}}" {{old('kelurahan') ? 'selected' : ($donatur->region_id ? 'selected' : '')}}>Kel. {{$kel->name}}</option>
                @endforeach
              </select>
              @error('kelurahan')
              <div class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="col-md-12">
              <label for="donatur" class="form-label">Donatur</label>
              <input type="text" name="donatur" id="donatur" class="form-control mb-2 @error('donatur') is-invalid @enderror" placeholder="Nama Pribadi atau Perusahaan" value="{{old('donatur') ? old('donatur') : $donatur->nama}}">
              @error('donatur')
              <div class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="col-md-12">
              <label for="alamat" class="form-label">Alamat</label>
              <textarea name="alamat" id="alamat" rows="3" class="form-control mb-2 @error('alamat') is-invalid @enderror">{{old('alamat') ? old('alamat') : $donatur->alamat}}</textarea>
              @error('alamat')
              <div class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
            </div>
            <div class="col-md-6 d-grid">
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            <div class="col-md-6 d-grid">
              <a href="{{route('donatur.index')}}" class="btn btn-light border">Kembali</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('js_plugins')
<script src="assets/plugins/select2-4.0.13/dist/js/select2.min.js"></script>
@endsection

@section('scripts')
<script src="assets/js/cmb.js"></script>
<script>
  $('#kecamatan').select2({
    theme : 'bootstrap-5',
    placeholder : 'Pilih Kecamatan'
  });
  $('#kelurahan').select2({
    theme : 'bootstrap-5',
    placeholder : 'Pilih Kelurahan'
  });
</script>
@endsection