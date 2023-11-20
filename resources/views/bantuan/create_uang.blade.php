@extends('template')

@section('content')
<div class="pagetitle">
  <h1>Tambah Bantuan Berupa Uang</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('dashboards.index')}}">Home</a></li>
      <li class="breadcrumb-item">Pages</li>
      <li class="breadcrumb-item"><a href="{{route('bantuan.index')}}">Bantuan</a></li>
      <li class="breadcrumb-item active">Tambah Bantuan Uang</li>
    </ol>
  </nav>
</div>
<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body pt-2">
          <form action="{{route('bantuan.store')}}" class="row g-2" method="post" enctype="multipart/form-data">
            @csrf
            <div class="col-md-12">
              <div class="row">
                <input type="hidden" name="jenis" value="Uang Tunai">
                <div class="col-md-3 col-lg-2">
                  <label for="tanggal" class="form-label">Tanggal Bantuan</label>
                  <input type="date" name="tanggal" id="tanggal" class="form-control" max="{{date('Y-m-d')}}">
                </div>
                <div class="col-md-4 col-lg-3">
                  <label for="kecamatan" class="form-label">Kecamatan</label>
                  <select name="kecamatan" id="kecamatan" class="form-select @error('kecamatan')is-invalid @enderror" onchange="get_kelurahan(this.value)">
                    <option></option>
                    @foreach($kecamatan as $kec)
                    <option value="{{$kec->id}}" {{old('kecamatan') ? 'selected' : ''}}>Kec. {{$kec->name}}</option>
                    @endforeach
                  </select>
                  @error('kecamatan')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-md-4 col-lg-3">
                  <label for="kelurahan" class="form-label">Kelurahan</label>
                  <select name="kelurahan" id="kelurahan" class="form-select @error('kelurahan')is-invalid @enderror" onchange="get_donatur(this.value)">
                    <option></option>
                    @if(Auth::user()->role == 'Kecamatan' || Auth::user()->role == 'Kelurahan')
                      @foreach($kelurahan as $kel)
                      <option value="{{$kel->id}}">Kel. {{$kel->name}}</option>
                      @endforeach
                    @endif
                  </select>
                  @error('kelurahan')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-8 col-lg-5">
                  <label for="donatur" class="form-label">Donatur</label>
                  <div class="input-group mb-3">
                    <select name="donatur" id="donatur" class="form-select @error('donatur')is-invalid @enderror">
                      <option></option>
                    </select>
                    <button class="btn btn-outline-secondary" type="button" id="button-addon2" onclick="modal_donatur()">Tambah Donatur</button>
                  </div>
                  @error('donatur')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-md-4 col-lg-4">
                  <label for="program" class="form-label">Program</label>
                  <select name="program" id="program" class="form-select @error('program')is-invalid @enderror">
                    <option></option>
                    @foreach($program as $prog)
                    <option value="{{$prog->id}}">{{$prog->name}}</option>
                    @endforeach
                  </select>
                  @error('program')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-6 col-lg-3">
                  <label for="nominal" class="form-label">Jumlah Uang</label>
                  <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control @error('nominal')is-invalid @enderror" name="nominal[]" id="nominal">
                  </div>
                </div>
                <div class="col-md-6 col-lg-3">
                  <label for="bukti" class="form-label">Bukti Transfer ke Rekening BSP</label>
                  <input type="file" name="bukti" id="bukti" class="form-control @error('bukti')is-invalid @enderror" accept="image/*">
                  <small class="text-danger fst-italic">Maksimal ukuran file 2MB</small>
                </div>
              </div>
            </div>
            <div class="col-md-12 col-lg-8">
              <label for="keterangan" class="form-label">Keterangan Tambahan <span class="fst-italic text-danger">(Opsional)</span></label>
              <textarea name="keterangan" id="keterangan" rows="3" class="form-control @error('keterangan')is-invalid @enderror"></textarea>
            </div>
            <div class="col-md-6 d-grid">
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            <div class="col-md-6 d-grid">
              <a href="{{route('bantuan.index')}}" class="btn btn-light border">Kembali</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modalDonatur" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Donatur</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="contentModalPenerima">
        <form action="/donatur" method="POST" class="row g-3" id="formModalTambahDonatur">
          @csrf
          <div class="col-md-6">
            <label for="kecamatan" class="form-label">Kecamatan</label>
            <select name="kecamatan" id="kecamatanDonatur" class="form-select mb-2 @error('kecamatan')is-invalid @enderror" onchange="get_kelurahan_donatur(this.value)">
              <option></option>
              @foreach($kecamatan as $kec)
              <option value="{{$kec->id}}" {{old('kecamatan') ? 'selected' : ''}}>Kec. {{$kec->name}}</option>
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
            <select name="kelurahan" id="kelurahanDonatur" class="form-select mb-2 @error('kelurahan')is-invalid @enderror">
              <option></option>
              @if(Auth::user()->role == 'Kecamatan' || Auth::user()->role == 'Kelurahan')
                @foreach($kelurahan as $kel)
                <option value="{{$kel->id}}">Kel. {{$kel->name}}</option>
                @endforeach
              @endif
            </select>
            @error('kelurahan')
            <div class="invalid-feedback">
              {{$message}}
            </div>
            @enderror
          </div>
          <div class="col-md-12">
            <label for="donatur" class="form-label">Donatur</label>
            <input type="text" name="donatur" id="donatur" class="form-control mb-2 @error('donatur') is-invalid @enderror" placeholder="Nama Pribadi, Kelompok atau Perusahaan" value="{{old('donatur')}}">
            @error('donatur')
            <div class="invalid-feedback">
              {{$message}}
            </div>
            @enderror
          </div>
          <div class="col-md-12">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" id="alamat" rows="3" class="form-control mb-2 @error('alamat') is-invalid @enderror">{{old('alamat')}}</textarea>
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
            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js_plugins')
<script src="{{asset('assets/js/modal.js')}}"></script>
<script src="{{asset('assets/js/cmb.js')}}"></script>
@endsection

@section('scripts')
<script>
  $('#nominal').mask('0,000,000,000,000,000', { reverse: true });
</script>
@endsection