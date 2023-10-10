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