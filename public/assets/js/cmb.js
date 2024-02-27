let array_penerima;

function get_kelurahan(kecamatan){
  $.ajax({
    url : base_url+'/data/get-kelurahan',
    type : 'GET',
    data : {
      kecamatan_id : kecamatan,
    },
    success : function(response){
      response = JSON.parse(response);
      var data = response.data;
      var select = $('#kelurahan');
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

function get_donatur(kelurahan){
  $.ajax({
    url : base_url+'/data/get-donatur',
    type : 'GET',
    data : {
      kelurahan_id : kelurahan,
    },
    success : function(response){
      var data = response.data;
      var select = $('#donatur');
      select.find('option').remove();
      select.append('<option></option>')
      data.forEach(function(donatur){
        var option = $('<option>');
        option.val(donatur.id);
        option.text(donatur.nama);

        select.append(option);
      })
    }
  })
}

function get_asw_id(wilayah, value){
  $.get(base_url+'/data/get-asw-id', {
    id : value,
  }, function(response){
    response = JSON.parse(response);
    let data = response.data;
    if(wilayah == 'kecamatan'){
      $('#kecamatan_id').val(data.asw_id);
    } else if(wilayah == 'kelurahan'){
      $('#kelurahan_id').val(data.asw_id);
    }
  })
}

function get_daftar_penerima(){
  let kecamatan = $('#kecamatan_id').val();
  let kelurahan = $('#kelurahan_id').val();
  let program = $('#program').val();

  if(program == '1'){
    $.get(base_url+'/data/get-balita-stunting', {
      kecamatan : kecamatan,
      kelurahan : kelurahan,
    }, function(response){
      let data = response.data;
      array_penerima = response.data;
      $('#penerima').empty();
      $('#penerima').append(new Option("", ""));
      data.forEach(function(result, index){
        $('#penerima').append(new Option(result.namalengkap, index));
      })
      $('#penerima').append(new Option("NAMA PENERIMA TIDAK DITEMUKAN PADA OPSI PILIHAN", "Luar ASW"));
    })
  } else if(program == 2){
    
  } else if(program == 3){
    
  } else if(program == 4){

  } else if(program == 5){
    $.get(base_url+'/data/get-bumil-resiko-tinggi', {
      kecamatan : kecamatan,
      kelurahan : kelurahan,
    }, function(response){
      let data = response.data;
      array_penerima = response.data;
      $('#penerima').empty();
      $('#penerima').append(new Option("", ""));
      data.forEach(function(result, index){
        $('#penerima').append(new Option(result.namalengkap, index));
      })
      $('#penerima').append(new Option("NAMA PENERIMA TIDAK DITEMUKAN PADA OPSI PILIHAN", "Luar ASW"));
    })
  }
}

function checkPenerima(value){
  if(value == 'Luar ASW'){
    $('#nik').attr('readonly', false);
    $('#nik').val('');
    $('#nama_penerima').val('');
    $('#alamat_ktp').val('');
    $('#kecamatan_ktp').val('');
    $('#kelurahan_ktp').val('');
    $('#alamat_domisili').val('');
    $('#kecamatan_domisili').val('');
    $('#kelurahan_domisili').val('');
    $('#flag_surabaya').val('');
  } else {
    $('#nik').attr('readonly', true);
    $('#nik').val(array_penerima[value].nik)
    getDataByNIK(array_penerima[value].nik);
  }
}

function getDataByNIK(value){
  if(value == ''){
    return false;
  }

  $('#loading-data').removeClass('d-none');

  $.get(base_url+'/data/get-cekin', {
    'nik' : value,
    'program_id' : $('#program').val(),
  }, function(response){
    if(response.message == 'success'){
      let data = response.data;
      // Cek Warga Surabaya
      if(data.flag_surabaya == 0){
        Swal.fire({
          title: 'Anda yakin?',
          text: "Berdasarkan data Aplikasi Cek In Warga, warga tersebut kemungkinan tidak termasuk Warga Surabaya!",
          icon: 'warning',
          showDenyButton: true,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Ya, saya yakin!',
          denyButtonText: 'Tidak',
        }).then((result) => {
          if(result.isDenied){
            return false;
          }
        })
      }
      
      // Cek Apakah sudah pernah mendapatkan bantuan intervensi lain
      if(data.cek_program > 0){
        Swal.fire({
          title: 'Anda yakin?',
          text: "Data yang anda masukkan juga menjadi penerima manfaat Gotong Royong untuk bidang lainnya.",
          icon: 'warning',
          showDenyButton: true,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Ya, saya yakin!',
          denyButtonText: 'Tidak',
        }).then((result) => {
          if (result.isDenied) {
            $('#nik_penerima').val('');
            return false;
          }
        })
      }

      $('#nama_penerima').val(data.nama);
      $('#alamat_ktp').val(data.alamat);
      $('#kecamatan_ktp').val(data.kecamatan);
      $('#kelurahan_ktp').val(data.kelurahan);
      $('#alamat_domisili').val(data.alamat_domisili);
      $('#kecamatan_domisili').val(data.kecamatan_domisili);
      $('#kelurahan_domisili').val(data.kelurahan_domisili);
      $('#flag_surabaya').val(data.flag_surabaya);
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Data berdasarkan NIK yang diinputkan tidak ditemukan pada Aplikasi CekIn Warga. Mohon tambah / verifikasi terlebih dahulu pada Aplikasi CekIn Warga.',
      })
    }

    $('#loading-data').addClass('d-none');
  })
}

$('#kecamatan').select2({
  theme : 'bootstrap-5',
  placeholder : 'Pilih Kecamatan'
});

$('#kecamatanDonatur').select2({
  theme : 'bootstrap-5',
  placeholder : 'Pilih Kecamatan'
});

$('#kelurahan').select2({
  theme : 'bootstrap-5',
  placeholder : 'Pilih Kelurahan'
});

$('#kelurahanDonatur').select2({
  theme : 'bootstrap-5',
  placeholder : 'Pilih Kelurahan'
});

$('#donatur').select2({
  placeholder : 'Pilih Donatur',
  theme : 'bootstrap-5',
})

$('#program').select2({
  placeholder : 'Pilih Program',
  theme : 'bootstrap-5',
})

$('#penerima').select2({
  placeholder : 'Pilih Penerima',
  theme : 'bootstrap-5',
})