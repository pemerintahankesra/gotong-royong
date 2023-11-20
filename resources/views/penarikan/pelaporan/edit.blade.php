@extends('template')

@section('css_plugins')
    <link rel="stylesheet" href="{{asset('assets/plugins/select2-4.0.13/dist/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap-5-theme-1.3.0/dist/select2-bootstrap-5-theme.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert2.min.css')}}">
@endsection

@section('content')
<div class="pagetitle">
  <h1>Upload Bukti Pencairan BSP</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('dashboards.index')}}">Home</a></li>
      <li class="breadcrumb-item">Pages</li>
      <li class="breadcrumb-item"><a href="{{route('penarikan.index')}}">Penarikan Uang</a></li>
      <li class="breadcrumb-item active">Upload Bukti Pencairan BSP</li>
    </ol>
  </nav>
</div>
<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body pt-2">
          <form action="{{route('penarikan.verifikasi.update', $penarikan->id)}}" class="row g-2" method="POST" id="formDistribusi" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="col-md-12">
              <input type="hidden" id="user_id" value="{{Auth::user()->id}}">
              <div class="row">
                <div class="col-md-2">
                  <label for="tanggal" class="form-label">Tanggal Pengajuan</label>
                  <input type="date" class="form-control mb-2" max="{{date('Y-m-d')}}" value="{{$penarikan->tanggal_pengajuan}}" readonly>
                </div>
                <div class="col-md-3">
                  <label for="kecamatan" class="form-label">Kecamatan</label>
                  <input type="text" class="form-control mb-2" value="{{$penarikan->region->kecamatan->name}}" readonly>
                </div>
                <div class="col-md-3">
                  <label for="kelurahan" class="form-label">Kelurahan</label>
                  <input type="text" class="form-control mb-2" value="{{$penarikan->region->name}}" readonly>
                </div>
                <div class="col-md-4">
                  <label for="program" class="form-label">Program</label>
                  <input type="text" class="form-control mb-2" value="{{$penarikan->program->name}}" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-12 pt-2">
              <table class="table table-bordered mb-2">
                <thead>
                  <tr class="text-center">
                    <th>No</th>
                    <th>Nama Penerima</th>
                    <th>Bantuan yang diterima</th>
                    <th>Jumlah</th>
                    <th>Foto Laporan</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($penarikan->detil_penarikan as $index => $dp)
                  <tr>
                    <td class="text-center">{{$index+1}}</td>
                    <td>{{$dp->penerima_id ? $dp->penerima->namalengkap : '-'}}</td>
                    <td>{{$dp->kategori.' ('.$dp->item.')'}}</td>
                    <td class="text-center">{{$dp->jumlah}}</td>
                    <td class="text-center" id="columnLaporan{{$dp->id}}">
                      @if($dp->foto_laporan)
                      <img src="{{asset('/storage/'.$dp->foto_laporan)}}" alt="Laporan Realisasi Pencairan Dana" height="150px" width="auto">
                      @else
                      <span class="fst-italic">Laporan Belum Di Upload</span>
                      @endif
                    </td>
                    <td class="text-center"><button type="button" class="btn btn-primary btn-sm" onclick="modal_upload_laporan({{$dp->id}})">Upload Laporan</button></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="col-md-6 d-grid">
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            <div class="col-md-6 d-grid">
              <a href="{{route('penarikan.index')}}" class="btn btn-light border">Kembali</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modalUploadLaporan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Upload Laporan</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="contentModalUploadLaporan">
        
      </div>
    </div>
  </div>
</div>

@endsection

@section('js_plugins')
<script src="{{asset('assets/plugins/select2-4.0.13/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/plugins/jquery.mask.min.js')}}"></script>
<script src="{{asset('assets/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('assets/js/form_penerima.js')}}"></script>
<script src="{{asset('assets/js/cmb.js')}}"></script>
<script src="{{asset('assets/js/modal.js')}}"></script>
@endsection

@section('scripts')
<script>
  $(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  });
</script>
@endsection