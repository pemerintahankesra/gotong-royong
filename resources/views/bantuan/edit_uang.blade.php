@extends('template')

@section('title', 'Tambah Bantuan Uang')

@section('css_plugins')
<link rel="stylesheet" href="/assets/plugins/select2-4.0.13/dist/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap-5-theme-1.3.0/dist/select2-bootstrap-5-theme.min.css">
@endsection

@section('content')
<div class="pagetitle">
  <h1>Tambah Bantuan berupa Uang</h1>
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
          <form action="{{route('bantuan.update', $bantuan->id)}}" class="row g-2" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-md-12">
              <div class="row">
                <input type="hidden" name="jenis" value="Uang Tunai">
                <div class="col-md-2">
                  <label for="tanggal" class="form-label">Tanggal Bantuan</label>
                  <input type="date" name="tanggal" id="tanggal" class="form-control" max="{{date('Y-m-d')}}" value="{{$bantuan->tanggal}}">
                </div>
                <div class="col-md-3">
                  <label for="kecamatan" class="form-label">Kecamatan</label>
                  <select name="kecamatan" id="kecamatan" class="form-select @error('kecamatan')is-invalid @enderror" onchange="get_kelurahan(this.value)">
                    <option></option>
                    @foreach($kecamatan as $kec)
                    <option value="{{$kec->id}}" {{old('kecamatan') ? (old('kecamatan') == $kec->id ? 'selected' : '') : ($bantuan->donatur->kelurahan->sub_id == $kec->id ? 'selected' : '')}}>Kec. {{$kec->name}}</option>
                    @endforeach
                  </select>
                  @error('kecamatan')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-md-3">
                  <label for="kelurahan" class="form-label">Kelurahan</label>
                  <select name="kelurahan" id="kelurahan" class="form-select @error('kelurahan')is-invalid @enderror" onchange="get_donatur(this.value)">
                    <option></option>
                    @foreach($kelurahan as $kel)
                    <option value="{{$kel->id}}" {{old('kelurahan') ? (old('kelurahan') == $kel->id ? 'selected' : '') : ($bantuan->donatur->region_id == $kel->id ? 'selected' : '')}}>{{$kel->name}}</option>
                    @endforeach
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
                <div class="col-md-5">
                  <label for="donatur" class="form-label">Donatur</label>
                  <select name="donatur" id="donatur" class="form-select @error('donatur')is-invalid @enderror">
                    <option></option>
                    @foreach($donatur as $don)
                    <option value="{{$don->id}}" {{$bantuan->donatur->id == $don->id ? 'selected' : ''}}>{{$don->nama}}</option>
                    @endforeach
                  </select>
                  @error('donatur')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-md-4">
                  <label for="program" class="form-label">Program</label>
                  <select name="program" id="program" class="form-select @error('program')is-invalid @enderror">
                    <option></option>
                    @foreach($program->where('barang', 1) as $prog)
                    <option value="{{$prog->id}}" {{$prog->id == $bantuan->program_id ? 'selected' : ''}}>{{$prog->name}}</option>
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
                <div class="col-md-4">
                  <label for="nominal" class="form-label">Jumlah Uang</label>
                  <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control @error('nominal')is-invalid @enderror" name="nominal[]" id="nominal" value="{{number_format($bantuan->detil_bantuan[0]->nominal)}}">
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="bukti" class="form-label">Bukti Transfer ke Rekening BSP</label>
                  <input type="file" name="bukti" id="bukti" class="form-control @error('bukti')is-invalid @enderror" accept="application/pdf, image/jpg, image/png">
                  <small class="text-danger fst-italic">Maksimal ukuran file 2MB</small>
                  @if($bantuan->bukti)
                  <a href="/storage/{{$bantuan->bukti}}">File yang diupload</a>
                  @endif
                </div>
              </div>
            </div>
            <div class="col-md-7">
              <label for="keterangan" class="form-label">Keterangan Tambahan <span class="fst-italic text-danger">(Opsional)</span></label>
              <textarea name="keterangan" id="keterangan" rows="3" class="form-control @error('keterangan')is-invalid @enderror">{{$bantuan->keterangan}}</textarea>
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
@endsection

@section('js_plugins')
<script src="/assets/plugins/select2-4.0.13/dist/js/select2.min.js"></script>
<script src="/assets/plugins/jquery.mask.min.js"></script>
@endsection

@section('scripts')
<script src="/assets/js/cmb.js"></script>
<script>
  $('#nominal').mask('0,000,000,000,000,000', { reverse: true });
</script>
@endsection