<form data-action="{{route('penarikan.pelaporan.update', $detil->id)}}" method="post" id="formUploadLaporan" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="barang" class="form-label">Upload Foto Laporan</label>
          <input type="file" name="upload_laporan" id="upload_laporan" class="form-control mb-2">
        </div>
      </div>
    </div>
  </div>
  <div class="d-grid">
    <button type="submit" class="btn btn-primary">Simpan</button>
  </div>
</form>

<script src="{{asset('assets/js/form_penerima.js')}}"></script>