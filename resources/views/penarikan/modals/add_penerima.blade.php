<link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

<form data-action="/penarikan/rencana-realisasi/{{$kategori}}" method="post" id="formAddRealisasi">
  @csrf
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
      <div class="col-md-4 d-flex align-items-center d-none" id="loading-data">
        <svg class="spinner" viewBox="0 0 50 50">
          <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="8"></circle>
        </svg>
        &nbsp; Sedang mengambil data...
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
  <div class="d-grid">
    <button type="submit" class="btn btn-primary">Simpan</button>
  </div>
</form>

<script src="{{asset('assets/js/form_penerima.js')}}"></script>