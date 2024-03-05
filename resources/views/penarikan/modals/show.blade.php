<div class="col-md-12">
  <input type="hidden" id="user_id" value="{{Auth::user()->id}}">
  <div class="row">
    <div class="col-md-2">
      <label for="tanggal" class="form-label">Tanggal Pengajuan</label>
      <input type="text" id="tanggal" class="form-control" value="{{date('d-m-Y', strtotime($penarikan->tanggal_pengajuan))}}" readonly>
    </div>
    <div class="col-md-3">
      <label for="kecamatan" class="form-label">Kecamatan</label>
      <input type="text" id="kecamatan" class="form-control" value="{{$penarikan->region->kecamatan->name}}" readonly>
    </div>
    <div class="col-md-3">
      <label for="kelurahan" class="form-label">Kelurahan</label>
      <input type="text" id="kelurahan" class="form-control" value="{{$penarikan->region->name}}" readonly>
    </div>
    <div class="col-md-4">
      <label for="program" class="form-label">Program</label>
      <input type="text" id="program" class="form-control" value="{{$penarikan->program->name}}" readonly>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4 col-lg-4">
      <label for="bank_tujuan" class="form-label">Bank Tujuan Pencairan</label>
      <input type="text" id="bank_tujuan" class="form-control" value="{{$penarikan->bank_tujuan_pencairan}}" readonly>
    </div>
    <div class="col-md-4 col-lg-4">
      <label for="rekening_tujuan" class="form-label">Rekening Tujuan Pencairan</label>
      <input type="text" id="rekening_tujuan" class="form-control mb-2" value="{{$penarikan->rekening_tujuan_pencairan}}" readonly>
    </div>
    <div class="col-md-4 col-lg-4">
      <label for="surat_pengajuan" class="form-label">Surat Pengajuan Penarikan Dana</label>
      <div>
        <a href="{{asset('/storage/'.$penarikan->surat_pengajuan)}}" target="_blank" rel="noopener noreferrer">File yang diupload</a>
      </div>
    </div>
  </div>
</div>
<div class="col-md-12 pt-2">
  <table class="table table-bordered mb-0" id="detil_penerima">
    <thead>
      <tr>
        <th class="text-center">No</th>
        <th class="text-center">Nama Penerima</th>
        <th class="text-center">Bantuan yang diterima</th>
        <th class="text-center">Total Nominal Bantuan (Rp)</th>
      </tr>
    </thead>
    <tbody>
      @foreach($penarikan->detil_penarikan as $i => $detil)
      <tr>
        <td class="text-center">{{$i+1}}</td>
        <td>{{$detil->penerima_id != null ? $detil->penerima->namalengkap : '-'}}</td>
        <td>{{$detil->kategori.'('.$detil->item.')'}}</td>
        <td class="text-end">{{number_format($detil->total_nominal)}}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="3"></td>
        <td class="text-end">{{number_format($penarikan->detil_penarikan->sum('total_nominal'))}}</td>
      </tr>
    </tbody>
  </table>
</div>
<div class="col-md-12 pt-2">
  <div class="row">
    <div class="col-12">
      <label for="bukti_tf" class="form-label">Bukti TF dari BSP</label>
      <div>
        @if($penarikan->bukti_pencairan != null)
        <img src="{{asset('/storage/'.$penarikan->bukti_pencairan)}}" alt="" class="img-fluid">
        @else
        <i class="text-danger">Belum ada bukti TF Pencairan dari BSP</i>
        @endif
      </div>
    </div>
  </div>
</div>
<div class="col-md-12">
  <label for="keterangan" class="form-label">Keterangan Tambahan <span class="fst-italic text-danger">(Opsional)</span></label>
  <textarea id="keterangan" rows="3" class="form-control mb-2" readonly>{{$penarikan->keterangan}}</textarea>
</div>