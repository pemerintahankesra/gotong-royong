@extends('template')

@section('content')
<div class="pagetitle">
  <h1>Verifikasi Bantuan berupa Uang</h1>
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
          <form action="{{route('bantuan.verifikasi.update', $bantuan->id)}}" class="row g-2" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-md-12">
              <div class="row">
                <input type="hidden" name="jenis" value="Uang Tunai">
                <div class="col-md-2">
                  <label for="tanggal" class="form-label">Tanggal Bantuan</label>
                  <input type="date" name="tanggal" id="tanggal" class="form-control" max="{{date('Y-m-d')}}" value="{{$bantuan->tanggal}}" readonly>
                </div>
                <div class="col-md-3">
                  <label for="kecamatan" class="form-label">Kecamatan</label>
                  <input type="text" name="kecamatan" id="kecamatan" class="form-control" value="{{$bantuan->kelurahan->kecamatan->name}}" readonly>
                </div>
                <div class="col-md-3">
                  <label for="kelurahan" class="form-label">Kelurahan</label>
                  <input type="text" name="kelurahan" id="kelurahan" class="form-control" value="{{$bantuan->kelurahan->name}}" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-5">
                  <label for="donatur" class="form-label">Donatur</label>
                  <input type="text" name="donatur" id="donatur" class="form-control" value="{{$bantuan->donatur->nama}}" readonly>
                </div>
                <div class="col-md-4">
                  <label for="program" class="form-label">Program</label>
                  <input type="text" name="program" id="program" class="form-control" value="{{$bantuan->program->name}}" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                  <label for="nominal" class="form-label">Jumlah Uang</label>
                  <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control" name="nominal[]" id="nominal" value="{{number_format($bantuan->detil_bantuan[0]->nominal)}}" readonly>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="bukti" class="form-label">Bukti Transfer ke Rekening BSP</label><br>
                  <img src="{{asset('/storage/'.$bantuan->bukti)}}" alt="Bukti TF" height="150px;" style="cursor:pointer" class="border" onclick="$('#modalBuktiTF').modal('toggle')">
                </div>
              </div>
            </div>
            <div class="col-md-7">
              <label for="keterangan" class="form-label">Keterangan Tambahan <span class="fst-italic text-danger">(Opsional)</span></label>
              <textarea name="keterangan" id="keterangan" rows="3" class="form-control" readonly>{{$bantuan->keterangan}}</textarea>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-6">
                  <label for="hasil_verifikasi" class="form-label">Verifikasi BSP</label>
                  <div class="form-check">
                    <input type="radio" name="hasil_verifikasi" id="verif_proses_pencairan" class="form-check-input" value="11">
                    <label for="verif_proses_pencairan" class="form-check-label">Sesuai</label>
                  </div>
                  <div class="form-check">
                    <input type="radio" name="hasil_verifikasi" id="verif_dikembalikan" class="form-check-input" value="21">
                    <label for="verif_dikembalikan" class="form-check-label">Dikembalikan / Revisi</label>
                  </div>
                  <div class="form-check">
                    <input type="radio" name="hasil_verifikasi" id="verif_ditolak" class="form-check-input" value="41">
                    <label for="verif_ditolak" class="form-check-label">Ditolak</label>
                  </div>
                  @error('hasil_verifikasi')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-md-6">
                  <label for="keterangan_bsp" class="form-label">Keterangan hasil verifikasi</label>
                  <textarea name="keterangan_bsp" id="keterangan_bsp" rows="3" class="form-control"></textarea>
                  @error('keterangan_bsp')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
              </div>
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

<div class="modal fade" id="modalBuktiTF" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Foto Bukti TF</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img src="{{asset('/storage/'.$bantuan->bukti)}}" alt="Bukti TF" width="720px">
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('assets/js/cmb.js')}}"></script>
<script>
  $('#nominal').mask('0,000,000,000,000,000', { reverse: true });
</script>
@endsection