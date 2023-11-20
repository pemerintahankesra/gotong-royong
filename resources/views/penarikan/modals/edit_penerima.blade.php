<form data-action="/penarikan/rencana-realisasi/{{$kategori}}/{{$id}}" method="post" id="formAddRealisasi">
  @csrf
  @method('PUT')
  <div class="col-md-12">
    <div class="row">
      <table class="table table-borderless">
        <tbody>
          <tr>
            <th class="align-middle">NIK</th>
            <td class="align-middle">:</td>
            <td class="align-middle">
              <input type="text" class="form-control" name="nik" id="nik" readonly value="{{$cart->id}}">
            </td>
            <th class="align-middle">Nama</th>
            <td class="align-middle">:</td>
            <td class="align-middle">
              <input type="text" name="nama_penerima" id="nama_penerima" class="form-control @error('nama_penerima') is-invalid @enderror" value="{{$cart->name}}" readonly>
              @error('nama_penerima')
              <div class="invalid-feedback">{{$message}}</div>
              @enderror
            </td>
          </tr>
          <tr>
            <th class="align-middle">Alamat KTP</th>
            <td class="align-middle">:</td>
            <td class="align-middle">
              <input type="text" name="alamat_ktp" id="alamat_ktp" class="form-control @error('alamat_penerima') is-invalid @enderror" value="{{$cart->attributes->alamat_ktp}}" readonly>
              @error('alamat_penerima')
              <div class="invalid-feedback">{{$message}}</div>
              @enderror
            </td>
            <th class="align-middle">Alamat Domisili</th>
            <td class="align-middle">:</td>
            <td class="align-middle">
              <input type="text" name="alamat_penerima" id="alamat_domisili" class="form-control @error('alamat_penerima') is-invalid @enderror" value="{{$cart->attributes->alamat_domisili}}" readonly>
              @error('alamat_penerima')
              <div class="invalid-feedback">{{$message}}</div>
              @enderror
            </td>
          </tr>
          <tr>
            <th class="align-middle">Kecamatan KTP</th>
            <td class="align-middle">:</td>
            <td class="align-middle">
              <input type="text" name="kecamatan_ktp" id="kecamatan_ktp" class="form-control @error('kecamatan_penerima') is-invalid @enderror" value="{{$cart->attributes->kecamatan_ktp}}" readonly>
              @error('kecamatan_penerima')
              <div class="invalid-feedback">{{$message}}</div>
              @enderror
            </td>
            <th class="align-middle">Kecamatan Domisili</th>
            <td class="align-middle">:</td>
            <td class="align-middle">
              <input type="text" name="kecamatan_penerima" id="kecamatan_domisili" class="form-control @error('kecamatan_penerima') is-invalid @enderror" value="{{$cart->attributes->kecamatan_domisili}}" readonly>
              @error('kecamatan_penerima')
              <div class="invalid-feedback">{{$message}}</div>
              @enderror
            </td>
          </tr>
          <tr>
            <th class="align-middle">Kelurahan KTP</th>
            <td class="align-middle">:</td>
            <td class="align-middle">
              <input type="text" name="kelurahan_ktp" id="kelurahan_ktp" class="form-control @error('kelurahan_penerima') is-invalid @enderror" value="{{$cart->attributes->kelurahan_ktp}}" readonly>
              @error('kelurahan_penerima')
              <div class="invalid-feedback">{{$message}}</div>
              @enderror
              <input type="hidden" name="flag_surabaya" id="flag_surabaya">
            </td>
            <th class="align-middle">Kelurahan Domisili</th>
            <td class="align-middle">:</td>
            <td class="align-middle">
              <input type="text" name="kelurahan_penerima" id="kelurahan_domisili" class="form-control @error('kelurahan_penerima') is-invalid @enderror" value="{{$cart->attributes->kelurahan_domisili}}" readonly>
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
        @foreach($cart->attributes->kategori as $i=>$kategori)
        <tr id="row{{$i}}">
          <td><select name="kategori[]" class="form-select kategori-bantuan">
            <option></option>
            <option value="Susu Balita Stunting" {{$kategori == 'Susu Balita Stunting' ? 'selected' : ''}}>Susu Balita Stunting</option>
            <option value="Vitamin" {{$kategori == 'Vitamin' ? 'selected' : ''}}>Vitamin</option>
            <option value="Permakanan / Kudapan Protein Hewani" {{$kategori == 'Permakanan / Kudapan Protein Hewani' ? 'selected' : ''}}>Permakanan / Kudapan</option>
            <option value="Lain-lain" {{$kategori == 'Lain-lain' ? 'selected' : ''}}>Lain-lain</option>
          </select></td>
          <td><input type="text" name="item[]" class="form-control keterangan-bantuan" value="{{$cart->attributes->item[$i]}}"></td>
          <td><input type="text" name="jumlah[]" class="form-control jumlah-bantuan" min="1" value="{{$cart->attributes->jumlah[$i]}}"></td>
          <td><input type="text" name="nominal[]" class="form-control nominal-bantuan" value="{{number_format($cart->attributes->nominal[$i])}}"></td>
          <td class="align-middle text-end"><span class="total-bantuan">{{number_format($cart->attributes->total_nominal[$i])}}</span><input type="hidden" name="total_nominal[]" class="text-total-bantuan" value="{{$cart->attributes->total_nominal[$i]}}"></td>
          <td class="align-middle"><button class="btn btn-danger btn-sm" onclick="deleteRow(this);">X</button></td>
        </tr>
        @endforeach
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
            <span id="textTotalNominalPerBulan">{{number_format($cart->price)}}</span>
            <input type="hidden" name="totalNominalPerBulan" id="totalNominalPerBulan" value="{{$cart->price}}">
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