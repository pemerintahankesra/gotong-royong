@extends('template')

@section('title', 'Data Distribusi Bantuan')

@section('content')
      <div class="pagetitle">
        <h1>Data Distribusi Bantuan (Barang)</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('dashboards.index')}}">Home</a></li>
            <li class="breadcrumb-item">Pages</li>
            <li class="breadcrumb-item active">Distribusi</li>
          </ol>
        </nav>
      </div>
      <section class="section">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body pt-3">
                <div class="row">
                  <div class="col-md-12 d-grid mb-3"><a href="/distribusi/create" class="btn btn-primary fs-5 py-4"><i class="bx bxs-package"></i> Buat Laporan Distribusi Bantuan Barang</a></div>
                </div>
                <table class="table table-stripped table-hover" id="datatable">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th class="text-center">Tanggal Pemberian</th>
                      <th class="text-center">Kecamatan</th>
                      <th class="text-center">Kelurahan</th>
                      <th class="text-center">Bantuan</th>
                      <th class="text-center">Program</th>
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

@section('scripts')
    <script>
      var currency = Intl.NumberFormat('en-US');
      
      $('#datatable').DataTable({
        "ajax" : {
          "url" : "{{route('distribusi.data')}}",
          "type" : "GET",
          "dataType" : "JSON",
          "data" : function(d){
            d.search = $('#search').val();
          }
        },
        "columns" : [
          {data: 'DT_RowIndex', className: 'text-center', name: 'DT_RowIndex'},
          {data : 'tanggal', className: 'text-center'},
          {data : 'kecamatan'},
          {data : 'kelurahan'},
          {render: function(data, type, row){
            let detil = row.detil_distribusi;
            let html = '<ul class="mb-0">';
            detil.forEach(function(data){
              html += '<li>'+data.kategori+' ('+data.item+') sejumlah '+data.jumlah+' <br>Penerima : '+data.namalengkap+'</li>';
            })
            html += '</ul>'
            return html;
          }},
          {data : 'program', className: 'text-center'},
          {render : function(data, type, row){
            var html = '<div class="d-flex justify-content-center">';
            html += '<a href="/distribusi/'+row.id+'/edit" class="btn btn-warning btn-sm mx-1">Edit</a>';
            html += '<form method="post" action="/distribusi/'+row.id+'">@csrf @method("DELETE")<button class="btn btn-danger btn-sm mx-1">Hapus</button></form>';
            html += '</div>';
            return html;
          }}
        ]
      })
    </script>
@endsection