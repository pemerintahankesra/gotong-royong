@extends('template')

@section('css_plugins')
    <link rel="stylesheet" href="{{asset('assets/plugins/select2-4.0.13/dist/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap-5-theme-1.3.0/dist/select2-bootstrap-5-theme.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert2.min.css')}}">
@endsection

@section('content')
<div class="pagetitle">
  <h1>Verifikasi Pengajuan Penarikan Uang ke BSP</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('dashboards.index')}}">Home</a></li>
      <li class="breadcrumb-item">Pages</li>
      <li class="breadcrumb-item"><a href="{{route('penarikan.index')}}">Penarikan Uang</a></li>
      <li class="breadcrumb-item active">Approval Pengajuan Penarikan Uang ke BSP</li>
    </ol>
  </nav>
</div>
<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body pt-2">
          <form action="{{route('penarikan.verifikasi.store', $penarikan->id)}}" class="row g-2" method="POST" id="formDistribusi" enctype="multipart/form-data">
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
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4 col-lg-2">
                  <label for="bank_tujuan" class="form-label">Bank Tujuan Pencairan</label>
                  <input type="text" name="bank_tujuan" id="bank_tujuan" class="form-control" value="{{$penarikan->banj_tujuan_pencairan}}" readonly>
                </div>
                <div class="col-md-4 col-lg-2">
                  <label for="rekening_tujuan" class="form-label">Rekening Tujuan Pencairan</label>
                  <input type="text" name="rekening_tujuan" id="rekening_tujuan" class="form-control mb-2" value="{{$penarikan->rekening_tujuan_pencairan}}" readonly>
                </div>
                <div class="col-md-4 col-lg-3">
                  <label for="surat_pengajuan" class="form-label">Surat Pengajuan Penarikan Dana</label>
                  <div><a href="{{asset('/storage/'.$penarikan->surat_pengajuan)}}" target="_blank" rel="noopener noreferrer">File yang diupload</a></div>
                </div>
              </div>
            </div>
            <div class="col-md-12 pt-2">
              <table class="table table-bordered mb-0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Penerima</th>
                    <th>Bantuan yang diterima</th>
                    <th>Total Nominal Bantuan (Rp)</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1?>
                  @foreach(Cart::session('approval')->getContent() as $cart)
                  <tr>
                    <td>{{($i++)}}</td>
                    @if($cart->attributes->jenis == 'penerima')
                    <td>{{$cart->name}}</td>
                    <td>
                      <ol>
                        @foreach($cart->attributes->kategori as $a => $kat)
                        <li>{{$kat.' ('.$cart->attributes->item[$a].') sejumlah '.$cart->attributes->jumlah[$a].' dengan harga satuan Rp. '.number_format($cart->attributes->nominal[$a])}}</li>
                        @endforeach
                      </ol>
                    </td>
                    <td class="text-end">{{number_format($cart->price)}}</td>
                    @else
                    <td>-</td>
                    <td>{{$cart->name}} sejumlah {{$cart->quantity}} dengan harga satuan Rp. {{number_format($cart->price)}}</td>
                    <td class="text-end">{{number_format($cart->attributes->total_nominal)}}</td>
                    @endif
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="3" class="fw-bold">Total Dana yang akan dicairkan</td>
                    <td class="fw-bold text-end">{{number_format(Cart::session('approval')->getTotal())}}</td>
                  </tr>
                </tfoot>
              </table>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label for="bank_tujuan" class="form-label">Bank Rekening Pencairan</label>
                <input type="text" class="form-control" value="{{$penarikan->bank_tujuan_pencairan}}" readonly>
              </div>
              <div class="col-md-4">
                <label for="rekening_tujuan" class="form-label">Nomor Rekening Pencairan</label>
                <input type="text" class="form-control" value="{{$penarikan->rekening_tujuan_pencairan}}" readonly>
              </div>
            </div>
            <div class="col-md-12">
              <label for="keterangan" class="form-label">Keterangan Tambahan dari Kecamatan / Kelurahan <span class="fst-italic text-danger">(Opsional)</span></label>
              <textarea rows="3" class="form-control mb-2" readonly>{{$penarikan->keterangan}}</textarea>
            </div>
            <div class="row">
              <div class="col-md-6">
                <label for="hasil_verifikasi" class="form-label">Verifikasi Permohonan Pencairan Dana</label>
                <div class="form-check">
                  <input type="radio" name="hasil_verifikasi" id="verif_proses_pencairan" class="form-check-input" value="11">
                  <label for="verif_proses_pencairan" class="form-check-label">Proses Pencairan</label>
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
            <div class="col-md-6 d-grid">
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            <div class="col-md-6 d-grid">
              <a href="{{route('distribusi.index')}}" class="btn btn-light border">Kembali</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modalTambahPenerima" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Penerima</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="contentModalPenerima">
        
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

  get_cart_penarikan();
  
  function btnEditRencanaRealisasi(id, kategori){
    event.preventDefault();

    $('#modalTambahPenerima').modal('toggle');
    $('#contentModalPenerima').load('/penarikan/rencana-realisasi/'+kategori+'/'+id+'/edit');
  }

  function deleteRencanaRealisasi(form_id){
    event.preventDefault();
    let form = $('#'+form_id);
    var url = form.attr('data-action');

    $.ajax({
      url: url,
      type: 'DELETE',
      data: {
        _method : 'DELETE',
      },
      dataType: 'JSON',
      contentType: false,
      cache: false,
      processData: false,
      success:function(response)
      {
        get_cart_penarikan();
      },
      error: function(response) {
        console.log(response)
      }
    });
  }

  function submitDistribusi(form_id){
    event.preventDefault();

    let form = $('#'+form_id);
    let url = form.attr('action');
    console.log(url);
  }
</script>
@endsection