@extends('template')

@section('css_plugins')
    <link href="{{asset('assets/plugins/DataTables-1.13.6/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet">
@endsection

@section('content')
<div class="pagetitle">
  <h1>Data Pelaporan Penarikan Uang ke BSP</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('dashboards.index')}}">Home</a></li>
      <li class="breadcrumb-item">Pages</li>
      <li class="breadcrumb-item active">Pelaporan Penarikan Uang ke BSP</li>
    </ol>
  </nav>
</div>
<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body pt-3">
          <table class="table table-stripped table-hover" id="datatable">
            <thead>
              <tr>
                <th class="text-center">No</th>
                <th class="text-center">Kecamatan</th>
                <th class="text-center">Kelurahan</th>
                <th class="text-center">Tanggal Pengajuan</th>
                <th class="text-center">Peruntukan</th>
                <th class="text-center">Jumlah Uang (Rp)</th>
                <th class="text-center">Status Laporan</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modalDetilPengajuan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Detil Pengajuan</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="contentModalDetilPengajuan">
        
      </div>
    </div>
  </div>
</div>
@endsection

@section('js_plugins')
    <script src="{{asset('assets/js/modal.js')}}"></script>
@endsection

@section('scripts')
    <script>
      var currency = Intl.NumberFormat('en-US');

      $('#datatable').DataTable({
        "ajax" : {
          "url" : "{{route('penarikan.pelaporan.data')}}",
          "type" : "GET",
          "dataType" : "JSON",
          "data" : function(d){
            d.search = $('#search').val();
          }
        },
        "columns" : [
          {data: 'DT_RowIndex', className: 'align-middle', name: 'DT_RowIndex'},
          {data : 'kecamatan'},
          {data : 'kelurahan'},
          {data : 'tanggal_pengajuan', className: 'text-center'},
          {data : 'program', className: 'text-center'},
          {className: 'text-end', render: function(data, type, row){
            return row.total.toLocaleString('en-US');
          }},
          {
            className: 'text-center', render : function(data, type, row){
              var span, status, html;
              if(row.status_laporan == 0){
                span = 'bg-secondary';
                status = 'Belum dilaporkan';
              } else if(row.status_laporan == 1){
                span = 'bg-success';
                status = 'Sudah dilaporkan';
              }
              html = '<span class="badge '+span+'">'+status+'</span>';
              return html;
            }},
          {className : 'justify-content-center d-flex', render : function(data, type, row){
            var html = '<button class="btn btn-sm btn-light mx-1" onclick="modal_detil_pengajuan('+row.id+')">Rincian</button>';
            if(row.status_laporan == 0){
              html += '<a href="/penarikan/pelaporan/'+row.id+'/edit" class="btn btn-success btn-sm mx-1">Buat Laporan</a>';
            }
            return html;
          }}
        ]
      })
    </script>
@endsection