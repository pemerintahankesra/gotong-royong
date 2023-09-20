function get_kelurahan(kecamatan){
  $.ajax({
    url : '/data/get_kelurahan',
    type : 'GET',
    data : {
      kecamatan_id : kecamatan,
    },
    success : function(response){
      response = JSON.parse(response);
      var data = response.data;
      var select = $('#kelurahan');
      select.find('option').remove();
      data.forEach(function(kelurahan){
        var option = $('<option>');
        option.val(kelurahan.id);
        option.text('Kel. '+kelurahan.name);

        select.append(option);
      })
    }
  })
}