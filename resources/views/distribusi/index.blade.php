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
          {data : 'kecamatan'},
          {data : 'kelurahan'},
          {data : 'donatur'},
          {data : 'tanggal', className: 'text-center'},
          {render : function(data, type, row){
            var detil = row.detil_bantuan;
            var html = '<p>'+row.program+'</p>';
            if(row.jenis == 'Uang Tunai'){
              html += detil[0].item+' : Rp. '+currency.format(detil[0].nominal);
            } else {
              html += '<ol class="mb-0">';
                detil.forEach(function(data){
                  html += '<li style="margin-left:-1rem;">'+data.kategori+' ('+data.item+') : '+data.jumlah+' item</li>'
                })
              html += '</ol>';
            }
            return html;
          }},
          {
            className: 'text-center', render : function(data, type, row){
              var span, status, html;
              if(row.jenis == 'Uang Tunai'){
                if(row.approval_bsp == 0){
                  span = 'bg-secondary';
                  status = 'Sedang Verifikasi';
                } else if(row.approval_bsp == 1){
                  span = 'bg-success';
                  status = 'Telah Diterima';
                } else if(row.approval_bsp == 2){
                  span = 'bg-warning';
                  status = 'Dikembalikan / Perlu Perbaikan';
                } else if(row.approval_bsp == 3){
                  span = 'bg-danger';
                  status = 'Ditolak';
                }
                html = '<span class="badge '+span+'">'+status+'</span>';
              } else {
                html = '-'
              }
              return html;
            }
          },
          {render : function(data, type, row){
            var html = '<div class="d-flex justify-content-center">';
            html += '<a href="/bantuan/'+row.id+'/edit" class="btn btn-warning btn-sm mx-1">Edit</a>';
            html += '<form method="post" action="/bantuan/'+row.id+'">@csrf @method("DELETE")<button class="btn btn-danger btn-sm mx-1">Hapus</button></form>';
            html += '</div>';
            return html;
          }}
        ]
      })
    </script>
@endsection