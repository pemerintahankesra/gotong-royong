@extends('template')

@section('content')
      <div class="pagetitle">
        <h1>Verifikasi Bantuan Uang</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('dashboards.index')}}">Home</a></li>
            <li class="breadcrumb-item">Pages</li>
            <li class="breadcrumb-item">Bantuan</li>
            <li class="breadcrumb-item active">Verifikasi Bantuan Uang</li>
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

@section('scripts')
    <script>
      var currency = Intl.NumberFormat('en-US');
      
      $('#datatable').DataTable({
        "ajax" : {
          "url" : "{{route('bantuan.verifikasi.data')}}",
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
              if(row.approval_bsp == 0){
                span = 'bg-secondary';
                status = 'Sedang Verifikasi';
              } else if(row.approval_bsp == 11){
                span = 'bg-success';
                status = 'Telah Diterima';
              } else if(row.approval_bsp == 20){
                span = 'bg-secondary';
                status = 'Sedang Verifikasi<br>(Telah dilakukan perbaikan)';
              } else if(row.approval_bsp == 21){
                span = 'bg-warning';
                status = 'Dikembalikan / Perlu Perbaikan';
              } else if(row.approval_bsp == 41){
                span = 'bg-danger';
                status = 'Ditolak';
              }
              html = '<span class="badge '+span+'">'+status+'</span>';
              return html;
            }
          },
          {render : function(data, type, row){
            var html = '<div class="d-flex justify-content-center">';
            if(row.approval_bsp == 0 || row.approval_bsp == 20){
              html += '<a href="'+window.location.origin+'/gotong-royong/bantuan/verifikasi/uang/'+row.id+'" class="btn btn-success btn-sm mx-1">Verifikasi</a>';
            } else {
              html += '-'
            }
            html += '</div>';
            return html;
          }}
        ]
      })
    </script>
@endsection