<link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

<form data-action="{{route('penarikan.rencana-realisasi.store', $kategori)}}" method="post" id="formAddRealisasi">
  @csrf
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="barang" class="form-label">Nama Barang</label>
          <input type="text" name="barang" id="barang" class="form-control mb-2">
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="jumlah" class="form-label">Jumlah</label>
          <input type="number" name="jumlah" id="jumlah" class="form-control mb-2" oninput="hitungTotal()">
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="harga_satuan" class="form-label">Harga Satuan</label>
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Rp</span>
            <input type="text" class="form-control" name="harga_satuan" id="harga_satuan" oninput="hitungTotal()">
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="total_harga" class="form-label">Total Harga</label>
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Rp</span>
            <input type="text" class="form-control" id="text_total_harga" readonly>
            <input type="hidden" name="total_harga" id="total_harga">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="d-grid">
    <button type="submit" class="btn btn-primary">Simpan</button>
  </div>
</form>

<script src="{{asset('assets/js/form_penerima.js')}}"></script>
<script>
  $('#jumlah, #harga_satuan').on('input', function(){
    $('#jumlah').mask('000', { reverse : true });
    $('#harga_satuan').mask('0,000,000,000', { reverse : true });
  })

  function hitungTotal(){
    var jumlah = parseInt(Number($('#jumlah').val().replace(/\D/g, '')));
    var harga = parseInt(Number($('#harga_satuan').val().replace(/\D/g, '')));
    var total = jumlah * harga;

    $('#text_total_harga').val(total.toLocaleString('en-US'));
    $('#total_harga').val(total);
  }
</script>