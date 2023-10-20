@extends('template')

@section('title', 'Distribusi Bantuan Baru')

@section('content')
<div class="pagetitle">
  <h1>Distribusi Bantuan Barang Baru</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('dashboards.index')}}">Home</a></li>
      <li class="breadcrumb-item">Pages</li>
      <li class="breadcrumb-item"><a href="{{route('distribusi.index')}}">Distribusi</a></li>
      <li class="breadcrumb-item active">Distribusi Bantuan Barang Baru</li>
    </ol>
  </nav>
</div>
<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body pt-2">
          <form action="{{route('distribusi.store')}}" class="row g-2" method="POST" id="formDistribusi">
            @csrf
            <div class="col-md-12">
              <input type="hidden" id="user_id" value="{{Auth::user()->id}}">
              <div class="row">
                <div class="col-md-2">
                  <label for="tanggal" class="form-label">Tanggal Distribusi Bantuan</label>
                  <input type="date" name="tanggal" id="tanggal" class="form-control" max="{{date('Y-m-d')}}">
                </div>
                <div class="col-md-3">
                  <label for="kecamatan" class="form-label">Kecamatan</label>
                  <select name="kecamatan" id="kecamatan" class="form-select @error('kecamatan')is-invalid @enderror" onchange="get_asw_id('kecamatan', this.value); get_kelurahan(this.value)">
                    <option></option>
                    @foreach($kecamatan as $kec)
                    <option value="{{$kec->id}}" {{old('kecamatan') ? 'selected' : ''}}>Kec. {{$kec->name}}</option>
                    @endforeach
                  </select>
                  <input type="hidden" name="kecamatan_id" id="kecamatan_id">
                  @error('kecamatan')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-md-3">
                  <label for="kelurahan" class="form-label">Kelurahan</label>
                  <select name="kelurahan" id="kelurahan" class="form-select @error('kelurahan')is-invalid @enderror" onchange="get_asw_id('kelurahan', this.value); get_donatur(this.value)">
                    <option></option>
                  </select>
                  <input type="hidden" name="kelurahan_id" id="kelurahan_id">
                  @error('kelurahan')
                  <div class="invalid-feedback">
                    {{$message}}
                  </div>
                  @enderror
                </div>
                <div class="col-md-4">
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
            <div class="col-md-12 pt-2">
              <table class="table table-bordered mb-0" id="detil_penerima">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Penerima</th>
                    <th>Bantuan yang diterima</th>
                    <th>Total Nominal Bantuan</th>
                    <th>#</th>
                  </tr>
                </thead>
                <tbody id="content_detil_penerima">
                  {{-- <tr>
                    <td colspan="5" class="text-center"><small class="fst-italic">Belum Ada Penerima yang Ditambahkan</small></td>
                  </tr> --}}
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="5">
                      <div class="d-grid">
                        <button class="btn btn-light border" type="button" onclick="btnPenerima()"><i class="bx bxs-user-plus"></i> Tambah Penerima</button>
                      </div>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
            <div class="col-md-12">
              <label for="keterangan" class="form-label">Keterangan Tambahan <span class="fst-italic text-danger">(Opsional)</span></label>
              <textarea name="keterangan" id="keterangan" rows="3" class="form-control @error('keterangan')is-invalid @enderror"></textarea>
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
<script src="{{asset('assets/js/form_penerima.js')}}"></script>
<script src="{{asset('assets/js/cmb.js')}}"></script>
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

  get_cart_distribusi();

  function btnPenerima(){
    let kecamatan = $('#kecamatan_id').val();
    let kelurahan = $('#kelurahan_id').val();
    let program = $('#program').val();

    if(kecamatan == '' || kelurahan == '' || program == 'program'){
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Data Kecamatan / Kelurahan / Program belum Dipilih!',
      })

      return false;
    }
    $('#modalTambahPenerima').modal('toggle');
    $('#contentModalPenerima').load('/distribusi/penerima/create');
    get_daftar_penerima();
  }
  
  function btnEditPenerima(id){
    event.preventDefault();

    $('#modalTambahPenerima').modal('toggle');
    $('#contentModalPenerima').load('/distribusi/penerima/'+id+'/edit');
  }

  function deletePenerima(form_id){
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
        get_cart_distribusi();
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