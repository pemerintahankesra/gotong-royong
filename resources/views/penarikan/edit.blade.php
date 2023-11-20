@extends('template')

@section('css_plugins')
    <link rel="stylesheet" href="{{asset('assets/plugins/select2-4.0.13/dist/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap-5-theme-1.3.0/dist/select2-bootstrap-5-theme.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert2.min.css')}}">
@endsection

@section('content')
<div class="pagetitle">
  <h1>Edit Pengajuan Penarikan Uang ke BSP</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('dashboards.index')}}">Home</a></li>
      <li class="breadcrumb-item">Pages</li>
      <li class="breadcrumb-item"><a href="{{route('distribusi.index')}}">Penarikan Uang</a></li>
      <li class="breadcrumb-item active">Edit Pengajuan Penarikan Uang ke BSP</li>
    </ol>
  </nav>
</div>
<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body pt-2">
          <form action="{{route('penarikan.update', $penarikan->id)}}" class="row g-2" method="POST" id="formDistribusi" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-md-12">
              <input type="hidden" id="user_id" value="{{Auth::user()->id}}">
              <div class="row">
                <div class="col-md-2">
                  <label for="tanggal" class="form-label">Tanggal Pengajuan</label>
                  <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal')is-invalid @enderror" max="{{date('Y-m-d')}}" value="{{old('tanggal') ? date('Y-m-d', strtotime(old('tanggal'))) : date('Y-m-d', strtotime($penarikan->tanggal_pengajuan))}}">
                  @error('tanggal')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-md-3">
                  <label for="kecamatan" class="form-label">Kecamatan</label>
                  <select name="kecamatan" id="kecamatan" class="form-select mb-2 @error('kecamatan')is-invalid @enderror" onchange="get_asw_id('kecamatan', this.value); get_kelurahan(this.value)">
                    <option></option>
                    @foreach($kecamatan as $kec)
                    <option value="{{$kec->id}}" {{$kec->id == $penarikan->region->sub_id ? 'selected' : ''}}>Kec. {{$kec->name}}</option>
                    @endforeach
                  </select>
                  <input type="hidden" name="kecamatan_id" id="kecamatan_id" value="{{old('kecamatan_id') ? old('kecamatan_id') : $penarikan->region->kecamatan->asw_id}}">
                  @error('kecamatan')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-md-3">
                  <label for="kelurahan" class="form-label">Kelurahan</label>
                  <select name="kelurahan" id="kelurahan" class="form-select mb-2 @error('kelurahan')is-invalid @enderror" onchange="get_asw_id('kelurahan', this.value); get_donatur(this.value)">
                    <option></option>
                    @foreach($kelurahan as $kel)
                    <option value="{{$kel->id}}" {{$kel->id == $penarikan->region->id ? 'selected' : ''}}>Kel. {{$kel->name}}</option>
                    @endforeach
                  </select>
                  <input type="hidden" name="kelurahan_id" id="kelurahan_id" value="{{old('kelurahan_id') ? old('kelurahan_id') : $penarikan->region->asw_id}}">
                  @error('kelurahan')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-md-4">
                  <label for="program" class="form-label">Program</label>
                  <select name="program" id="program" class="form-select mb-2 @error('program')is-invalid @enderror">
                    <option></option>
                    @foreach($program as $prog)
                    <option value="{{$prog->id}}" {{old('program') ? (old('program') == $prog->id ? 'selected' : '') : ($penarikan->program_id == $prog->id ? 'selected' : '')}}>{{$prog->name}}</option>
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
                <div class="col-md-4 col-lg-2">
                  <label for="bank_tujuan" class="form-label">Bank Tujuan Pencairan</label>
                  <input type="text" name="bank_tujuan" id="bank_tujuan" class="form-control mb-2 @error('bank_tujuan')is-invalid @enderror" value="{{old('bank_tujuan') ? old('bank_tujuan') : $penarikan->bank_tujuan_pencairan}}">
                  @error('bank_tujuan')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-md-4 col-lg-2">
                  <label for="rekening_tujuan" class="form-label">Rekening Tujuan Pencairan</label>
                  <input type="text" name="rekening_tujuan" id="rekening_tujuan" class="form-control mb-2 @error('rekening_tujuan')is-invalid @enderror" value="{{old('rekening_tujuan') ? old('rekening_tujuan') : $penarikan->rekening_tujuan_pencairan}}">
                  @error('rekening_tujuan')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-md-4 col-lg-3">
                  <label for="surat_pengajuan" class="form-label">Surat Pengajuan Penarikan Dana</label>
                  <input type="file" name="surat_pengajuan" id="surat_pengajuan" class="form-control mb-2 @error('surat_pengajuan')is-invalid @enderror" accept="application/pdf">
                  @error('surat_pengajuan')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                  @if($penarikan->surat_pengajuan)
                  <a href="#">File yang diupload</a>
                  @endif
                </div>
              </div>
            </div>
            <div class="col-md-12 pt-2">
              <table class="table table-bordered mb-0" id="detil_penerima">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Penerima</th>
                    <th>Bantuan yang diterima</th>
                    <th>Total Nominal Bantuan (Rp)</th>
                    <th>#</th>
                  </tr>
                </thead>
                <tbody id="content_detil_penerima">

                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="5">
                      <div class="row">
                        <div class="col-md-6 d-grid">
                          <button class="btn btn-light border" type="button" onclick="modal_realisasi('penerima')"><i class="bx bxs-user-plus"></i> Tambah Kebutuhan Dana berdasarkan Penerima</button>
                        </div>
                        <div class="col-md-6 d-grid">
                          <button class="btn btn-light border" type="button" onclick="modal_realisasi('barang')"><i class="bx bxs-box"></i> Tambah Kebutuhan Dana berdasarkan Barang</button>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tfoot>
              </table>
              @error('cart')
              <div class="invalid-feedback d-block">{{$message}}</div>
              @enderror
            </div>
            <div class="col-md-12">
              <label for="keterangan" class="form-label">Keterangan Tambahan <span class="fst-italic text-danger">(Opsional)</span></label>
              <textarea name="keterangan" id="keterangan" rows="3" class="form-control mb-2 @error('keterangan')is-invalid @enderror">{{old('keterangan') ? old('keterangan') : $penarikan->keterangan}}</textarea>
              @error('keterangan')
              <div class="invalid-feedback">
                {{$message}}
              </div>
              @enderror
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

<div class="modal fade" id="modalTambahPenerima" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="titleModalPenerima">Tambah Penerima</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="contentModalPenerima">
        
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalSuratPengajuan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="titleModalSuratPengajuan">Surat Pengajuan Penarikan Dana</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="contentModalSuratPengajuan">
        
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