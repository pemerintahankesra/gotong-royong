@extends('template')

@section('title', 'Distribusi Bantuan Baru')

@section('css_plugins')
<link rel="stylesheet" href="/assets/plugins/select2-4.0.13/dist/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap-5-theme-1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="/assets/plugins/sweetalert/sweetalert2.min.css">
@endsection

@section('content')
<div class="pagetitle">
  <h1>Distribusi Bantuan Baru</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('dashboards.index')}}">Home</a></li>
      <li class="breadcrumb-item">Pages</li>
      <li class="breadcrumb-item"><a href="{{route('distribusi.index')}}">Distribusi</a></li>
      <li class="breadcrumb-item active">Distribusi Bantuan Baru</li>
    </ol>
  </nav>
</div>
<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body pt-2">
          <form action="{{route('distribusi.store')}}" class="row g-2" method="post">
            @csrf
            <div class="col-md-12">
              <div class="row">
                <input type="hidden" name="jenis" value="{{$jenis}}">
                <div class="col-md-2">
                  <label for="tanggal" class="form-label">Tanggal Distribusi Bantuan</label>
                  <input type="date" name="tanggal" id="tanggal" class="form-control" max="{{date('Y-m-d')}}">
                </div>
                <div class="col-md-3">
                  <label for="kecamatan" class="form-label">Kecamatan</label>
                  <select name="kecamatan" id="kecamatan" class="form-select @error('kecamatan')is-invalid @enderror" onchange="get_asw_id('kecamatan', this.value); get_kelurahan(this.value)" onchange="get_daftar_penerima()">
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
                  <select name="kelurahan" id="kelurahan" class="form-select @error('kelurahan')is-invalid @enderror" onchange="get_asw_id('kelurahan', this.value); get_donatur(this.value)" onchange="get_daftar_penerima()">
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
                  <select name="program" id="program" class="form-select @error('program')is-invalid @enderror" onchange="get_daftar_penerima()">
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
                <div class="col-md-4">
                  <label for="penerima" class="form-label">Penerima</label>
                  <select name="penerima" id="penerima" class="form-select" onchange="checkPenerima(this.value)">
                    <option></option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan)</label>
                  <input type="text" name="nik" id="nik" class="form-control" readonly onchange="getDataByNIK(this.value)">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="row">
                <table class="table table-borderless">
                  <tbody>
                    <tr>
                      <th class="align-middle">Nama</th>
                      <td class="align-middle">:</td>
                      <td class="align-middle">
                        <input type="text" name="nama_penerima" id="nama_penerima" class="form-control @error('nama_penerima') is-invalid @enderror" value="{{old('nama_penerima')}}" readonly>
                        @error('nama_penerima')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                      </td>
                    </tr>
                    <tr>
                      <th class="align-middle">Alamat KTP</th>
                      <td class="align-middle">:</td>
                      <td class="align-middle">
                        <input type="text" name="alamat_ktp" id="alamat_ktp" class="form-control @error('alamat_penerima') is-invalid @enderror" value="{{old('alamat_penerima')}}" readonly>
                        @error('alamat_penerima')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                      </td>
                      <th class="align-middle">Alamat Domisili</th>
                      <td class="align-middle">:</td>
                      <td class="align-middle">
                        <input type="text" name="alamat_penerima" id="alamat_domisili" class="form-control @error('alamat_penerima') is-invalid @enderror" value="{{old('alamat_penerima')}}" readonly>
                        @error('alamat_penerima')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                      </td>
                    </tr>
                    <tr>
                      <th class="align-middle">Kecamatan KTP</th>
                      <td class="align-middle">:</td>
                      <td class="align-middle">
                        <input type="text" name="kecamatan_ktp" id="kecamatan_ktp" class="form-control @error('kecamatan_penerima') is-invalid @enderror" value="{{old('kecamatan_penerima')}}" readonly>
                        @error('kecamatan_penerima')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                      </td>
                      <th class="align-middle">Kecamatan Domisili</th>
                      <td class="align-middle">:</td>
                      <td class="align-middle">
                        <input type="text" name="kecamatan_penerima" id="kecamatan_domisili" class="form-control @error('kecamatan_penerima') is-invalid @enderror" value="{{old('kecamatan_penerima')}}" readonly>
                        @error('kecamatan_penerima')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                      </td>
                    </tr>
                    <tr>
                      <th class="align-middle">Kelurahan KTP</th>
                      <td class="align-middle">:</td>
                      <td class="align-middle">
                        <input type="text" name="kelurahan_ktp" id="kelurahan_ktp" class="form-control @error('kelurahan_penerima') is-invalid @enderror" value="{{old('kelurahan_penerima')}}" readonly>
                        @error('kelurahan_penerima')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                        <input type="hidden" name="flag_surabaya" id="flag_surabaya">
                      </td>
                      <th class="align-middle">Kelurahan Domisili</th>
                      <td class="align-middle">:</td>
                      <td class="align-middle">
                        <input type="text" name="kelurahan_penerima" id="kelurahan_domisili" class="form-control @error('kelurahan_penerima') is-invalid @enderror" value="{{old('kelurahan_penerima')}}" readonly>
                        @error('kelurahan_penerima')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                        <input type="hidden" name="flag_surabaya" id="flag_surabaya">
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-md-12">
              <table class="table table-bordered" id="tableBantuan">
                <thead>
                  <tr>
                    <th class="align-middle">Kategori</th>
                    <th class="align-middle">Nama Barang</th>
                    <th class="align-middle">Jumlah</th>
                    <th class="align-middle">Harga Satuan (Rp)</th>
                    <th class="align-middle">Total Nominal (Rp)</th>
                    <th class="align-middle">#</th>
                  </tr>
                </thead>
                <tbody>
                  @if(old('kategori_bantuan'))
                    @foreach(old('kategori_bantuan') as $i => $kategori)
                    <tr id="row{{$i}}">
                      <td>
                        <select name="kategori[]" class="form-select kategori-bantuan @error('kategori.'.$i) is-invalid @enderror" onchange="checkBantuan($(this))">
                          <option></option>
                          <option value="Susu Balita Stunting" {{old('kategori.'.$i) == 'Susu Balita Stunting' ? 'selected' : ''}}>Susu Balita Stunting</option>
                          <option value="Vitamin Balita Stunting" {{old('kategori.'.$i) == 'Vitamin Balita Stunting' ? 'selected' : ''}}>Vitamin Balita Stunting</option>
                          <option value="Permakanan / Kudapan Protein Hewani" {{old('kategori.'.$i) == 'Permakanan / Kudapan Protein Hewani' ? 'selected' : ''}}>Permakanan / Kudapan</option>
                          <option value="Lain-lain" {{old('kategori.'.$i) == 'Lain-lain' ? 'selected' : ''}}>Lain-lain</option>
                        </select>
                        @error('kategori.'.$i) <div class="invalid-feedback">{{$message}}</div> @enderror
                      </td>
                      <td>
                        <input type="text" name="item[]" class="form-control keterangan-bantuan @error('item.'.$i) is-invalid @enderror" value="{{old('item.'.$i)}}">
                        @error('item.'.$i) <div class="invalid-feedback">{{$message}}</div> @enderror
                      </td>
                      <td>
                        <input type="text" name="jumlah[]" class="form-control jumlah-bantuan @error('jumlah.'.$i) is-invalid @enderror" value="{{old('jumlah.'.$i)}}">
                        @error('jumlah.'.$i) <div class="invalid-feedback">{{$message}}</div> @enderror
                      </td>
                      <td>
                        <input type="text" name="nominal[]" class="form-control nominal-bantuan @error('nominal.'.$i) is-invalid @enderror" value="{{old('nominal.'.$i)}}">
                        @error('nominal.'.$i) <div class="invalid-feedback">{{$message}}</div> @enderror
                      </td>
                      <td class="align-middle text-end">
                        <span class="total-bantuan">{{number_format(old('total_nominal.'.$i))}}</span>
                        <input type="hidden" name="total_nominal[]" class="text-total-bantuan" value="{{old('total_nominal.'.$i)}}">
                      </td>
                      <td class="align-middle"><button class="btn btn-danger btn-sm" onclick="deleteRow(this);">X</button></td>
                    </tr>
                    @endforeach
                  @else
                  <tr id="row0">
                    <td>
                      <select name="kategori[]" class="form-select kategori-bantuan" onchange="checkBantuan($(this))">
                        <option></option>
                        <option value="Susu Balita Stunting">Susu Balita Stunting</option>
                        <option value="Vitamin Balita Stunting">Vitamin Balita Stunting</option>
                        <option value="Permakanan / Kudapan Protein Hewani">Permakanan / Kudapan</option>
                        <option value="Lain-lain">Lain-lain</option>
                      </select>
                    </td>
                    <td>
                      <input type="text" name="item[]" class="form-control keterangan-bantuan">
                    </td>
                    <td>
                      <input type="text" name="jumlah[]" class="form-control jumlah-bantuan" min="1">
                    </td>
                    <td>
                      <input type="text" name="nominal[]" class="form-control nominal-bantuan">
                    </td>
                    <td class="align-middle text-end">
                      <span class="total-bantuan">0</span>
                      <input type="hidden" name="total_nominal[]" class="text-total-bantuan">
                    </td>
                    <td class="align-middle"><button class="btn btn-danger btn-sm" onclick="deleteRow(this);">X</button></td>
                  </tr>
                  @endif
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="6">
                      <div class="d-grid">
                        <button class="btn btn-secondary btn-sm" onclick="addRow()">Tambah Baris</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="4">Total Nominal Bantuan</td>
                    <td class="text-end">
                      <span id="textTotalNominalPerBulan">{{old('totalNominalPerBulan') ? number_format(old('totalNominalPerBulan')) : '0'}}</span>
                      <input type="hidden" name="totalNominalPerBulan" id="totalNominalPerBulan" value="{{old('totalNominalPerBulan')}}">
                    </td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>
            <div class="col-md-7">
              <label for="keterangan" class="form-label">Keterangan Tambahan <span class="fst-italic text-danger">(Opsional)</span></label>
              <textarea name="keterangan" id="keterangan" rows="3" class="form-control @error('keterangan')is-invalid @enderror"></textarea>
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
<script src="/assets/plugins/select2-4.0.13/dist/js/select2.min.js"></script>
<script src="/assets/plugins/jquery.mask.min.js"></script>
<script src="/assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
<script src="/assets/js/cmb.js"></script>
@endsection

@section('scripts')
<script>
  $('.keterangan-bantuan, .jumlah-bantuan, .nominal-bantuan').on('input', function() {
    var rowId = $(this).closest('tr').attr('id');
    calculateTotal(rowId);
  });

  function checkBantuan(data){
    var value = data.val();
    var rowId = data.closest("tr").attr("id");
    if(value == 'Uang'){
      $('#'+rowId+' .jumlah-bantuan').val(1).attr('readonly', true)
    } else {
      $('#'+rowId+' .jumlah-bantuan').val('').attr('readonly', false)
    }
    calculateTotal(rowId);
  }
  
  function addRow(){
    event.preventDefault();

    var rowCount = $('#tableBantuan tr').length;

    let table = $("#tableBantuan");
    if(rowCount > 4){
      var lastRow = table.find("tbody  tr:last");
      var lastId = lastRow.attr("id");
      var newId = generateUniqueId(lastId);
      var newRow = lastRow.clone();
      newRow.attr("id", newId);

      newRow.find("input").val("");
      newRow.find(".total-bantuan").text("0");
    } else {
      var newRow = '<tr id="row0">'+
        '<td><select name="kategori[]" class="form-select kategori-bantuan">'+
          '<option></option>'+
          '<option value="Susu Balita Stunting">Susu Balita Stunting</option>'+
          '<option value="Vitamin">Vitamin</option>'+
          '<option value="Permakanan / Kudapan Protein Hewani">Permakanan / Kudapan</option>'+
          '<option value="Lain-lain">Lain-lain</option>'+
        '</select></td>'+
        '<td><input type="text" name="item[]" class="form-control keterangan-bantuan"></td>'+
        '<td><input type="text" name="jumlah[]" class="form-control jumlah-bantuan" min="1"></td>'+
        '<td><input type="text" name="nominal[]" class="form-control nominal-bantuan"></td>'+
        '<td class="align-middle text-end"><span class="total-bantuan">0</span><input type="hidden" name="total_nominal[]" class="text-total-bantuan"></td>'+
        '<td class="align-middle"><button class="btn btn-danger btn-sm" onclick="deleteRow(this);"><i class="fas fa-times"></i></button></td>'+
      '</tr>';

      var newId = 'row0';
    }

    table.find("tbody").append(newRow);

    $('#' + newId + ' .keterangan-bantuan, #' + newId + ' .jumlah-bantuan, #' + newId + ' .nominal-bantuan').on('input', function() {
      $('.jumlah-bantuan').mask('0,000', { reverse: true });
      $('.nominal-bantuan').mask('0,000,000,000', { reverse: true });
      calculateTotal(newId);
    });
  }

  function deleteRow(button){
    button.closest("tr").remove();
  }

  function setUniqueIds() {
    var rows = $("#tablebantuan tbody tr");
    var lastId = rows.last().attr("id");
    var newId = generateUniqueId(lastId);
    rows.last().attr("id", newId);
  }

  function generateUniqueId(lastId) {
    if (!lastId) {
      return "row0";
    } else {
      var lastNum = parseInt(lastId.match(/\d+/)[0]);
      var newNum = lastNum + 1;
      return "row"+newNum;
    }
  }

  function calculateTotal(rowId) {
    var jumlah = parseInt(Number($('#' + rowId + ' .jumlah-bantuan').val().replace(/\D/g, '')));
    var harga = parseInt(Number($('#' + rowId + ' .nominal-bantuan').val().replace(/\D/g, '')));
    var total = jumlah * harga;

    $('#' + rowId + ' .total-bantuan').text(total.toLocaleString('en-US'));
    $('#' + rowId + ' .text-total-bantuan').val(total);

    var totalPerBulan = 0;
    $('input[name="total_nominal[]"]').each(function(){
      totalPerBulan += parseInt($(this).val());
    })
    $('#textTotalNominalPerBulan').text(totalPerBulan.toLocaleString('en-US'));
    $('#totalNominalPerBulan').val(totalPerBulan);
  }
</script>
@endsection