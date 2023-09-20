@extends('template')

@section('title', 'Data Donatur')

@section('css_plugins')
    <link href="assets/plugins/DataTables-1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
      <div class="pagetitle">
        <h1>Data Bantuan</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('dashboards.index')}}">Home</a></li>
            <li class="breadcrumb-item">Pages</li>
            <li class="breadcrumb-item active">Bantuan</li>
          </ol>
        </nav>
      </div>
      <section class="section">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body pt-2">
                <table class="table table-stripped table-hover" id="datatable">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Kecamatan</th>
                      <th>Kelurahan</th>
                      <th>Donatur</th>
                      <th>#</th>
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
      $('#datatable').DataTable({
        "ajax" : {
          "url" : "{{route('bantuan.data')}}",
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
          {render : function(data, type, row){
            var html = '<span class="fw-bold">Donatur</span> : ';
          }},
          {className : 'justify-content-center d-flex', render : function(data, type, row){
            var html = '<a href="/bantuan/'+row.id+'/edit" class="btn btn-warning btn-sm mx-1">Edit</a>';
            html += '<form method="post" action="/bantuan/'+row.id+'">@csrf @method("DELETE")<button class="btn btn-danger btn-sm mx-1">Hapus</button></form>';
            return html;
          }}
        ]
      })
    </script>
@endsection