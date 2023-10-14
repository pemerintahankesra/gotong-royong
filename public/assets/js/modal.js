// Modal Donatur
function modal_donatur(){
  $('#modalDonatur').modal('toggle');
}

function get_kelurahan_donatur(kecamatan){
  $.ajax({
    url : '/data/get-kelurahan',
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
    url : '/donatur/store',
    type : 'POST',
    data : formData,
    success : function(response){
      $(this).trigger('reset');
      $('#modalDonatur').modal('toggle');
      get_donatur(kelurahan);
    }
  })
})