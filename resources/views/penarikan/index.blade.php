@extends('template')

@section('title', 'Data Distribusi Bantuan')

@section('css_plugins')
    <link href="assets/plugins/DataTables-1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
      <div class="pagetitle">
        <h1>Data Distribusi Bantuan</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('dashboards.index')}}">Home</a></li>
            <li class="breadcrumb-item">Pages</li>
            <li class="breadcrumb-item active">Pengajuan Penarikan Uang ke BSP</li>
          </ol>
        </nav>
      </div>
      <section class="section">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body pt-3">
                <div class="row">
                  <div class="col-md-6 d-grid mb-3"><a href="/distribusi/uang" class="btn btn-primary fs-5 py-4"><i class="bx bx-money"></i> Berdasarkan Dana Gotong Royong di BSP</a></div>
                  <div class="col-md-6 d-grid mb-3"><a href="/distribusi/barang" class="btn btn-primary fs-5 py-4"><i class="bx bxs-package"></i> Sumber dari Stok Barang</a></div>
                </div>
                <table class="table table-stripped table-hover" id="datatable">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th class="text-center">Kecamatan</th>
                      <th class="text-center">Kelurahan</th>
                      <th class="text-center">Donatur</th>
                      <th class="text-center">Tanggal Pemberian</th>
                      <th class="text-center">Peruntukan</th>
                      <th class="text-center">Approval BSP</th>
                      <th class="text-center">Aksi</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>
@endsection

@section('js_plugins')
    <script src="assets/plugins/DataTables-1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/DataTables-1.13.6/js/dataTables.bootstrap5.min.js"></script>
@endsection

@section('scripts')
    <script>
      var currency = Intl.NumberFormat('en-US');
    </script>
@endsection