let base_url = 'https://pemerintahan.surabaya.go.id/gotong-royong';

// Modal Donatur
function modal_donatur(){
  $('#modalDonatur').modal('toggle');
}

function get_kelurahan_donatur(kecamatan){
  $.ajax({
    url : base_url+'/data/get-kelurahan',
    type : 'GET',
    data : {
      kecamatan_id : kecamatan,
    },
    success : function(response){
      response = JSON.parse(response);
      var data = response.data;
      var select = $('#kelurahanDonatur');
      select.find('option').remove();
      select.append('<option></option>')
      data.forEach(function(kelurahan){
        var option = $('<option>');
        option.val(kelurahan.id);
        option.text('Kel. '+kelurahan.name);

        select.append(option);
      })
    }
  })
}

$('#formModalTambahDonatur').submit(function(){
  event.preventDefault();
  formData = $(this).serialize();
  var kelurahan = $('#kelurahan').val();

  $.ajax({
    url : base_url+'/donatur/store',
    type : 'POST',
    data : formData,
    success : function(response){
      $(this).trigger('reset');
      $('#modalDonatur').modal('toggle');
      get_donatur(kelurahan);
    }
  })
})

// Modal Realisasi
function modal_realisasi(kategori){
  let kecamatan = $('#kecamatan_id').val();
  let kelurahan = $('#kelurahan_id').val();
  let program = $('#program').val();

  if(kecamatan == '' || kelurahan == '' || program == 'program'){
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Data Kecamatan / Kelurahan / Program belum Dipilih!',
    })

    return false;
  }
  $('#modalTambahPenerima').modal('toggle');
  if(kategori == 'penerima'){
    $('#contentModalPenerima').load('/penarikan/rencana-realisasi/penerima/create');
    get_daftar_penerima();
  } else if(kategori == 'barang') {
    $('#contentModalPenerima').load('/penarikan/rencana-realisasi/barang/create');
  }
}

// Modal Rincian Pengajuan
function modal_detil_pengajuan(id){
  $('#modalDetilPengajuan').modal('toggle');
  $('#contentModalDetilPengajuan').load('/penarikan/'+id);
}

// Modal Upload Laporan
function modal_upload_laporan(id){
  $('#modalUploadLaporan').modal('toggle');
  $('#contentModalUploadLaporan').load('/penarikan/pelaporan/'+id+'/laporan');
}