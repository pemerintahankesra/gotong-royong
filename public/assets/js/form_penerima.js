$('#formAddPenerima').on('submit', function(event){
  event.preventDefault();

  var url = $(this).attr('data-action');
  $.ajax({
    url: url,
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success:function(response)
    {
      $('#modalTambahPenerima').modal('toggle');
      get_cart_distribusi();
    },
    error: function(response) {
      console.log(response)
    }
  });
})

$('#formAddRealisasi').on('submit', function(event){
  event.preventDefault();

  var url = $(this).attr('data-action');
  $.ajax({
    url: url,
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success:function(response)
    {
      $('#modalTambahPenerima').modal('toggle');
      get_cart_penarikan();
    },
    error: function(response) {
      console.log(response)
    }
  });
})

$('#formUploadLaporan').on('submit', function(event){
  event.preventDefault();
  var url = $(this).attr('data-action');
  console.log(url);
  $.ajax({
    url : url,
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function(response){
      $('#modalUploadLaporan').modal('toggle');
      $('#columnLaporan'+response.detil.id).html('<img src="/storage/'+response.detil.foto_laporan+'" alt="Laporan Realisasi Pencairan Dana" height="150px" width="auto">');
    }
  })
})

$('.keterangan-bantuan, .jumlah-bantuan, .nominal-bantuan').on('input', function() {
  var rowId = $(this).closest('tr').attr('id');
  calculateTotal(rowId);
});

function checkBantuan(data){
  var value = data.val();
  var rowId = data.closest("tr").attr("id");
  if(value == 'Uang'){
    $('#'+rowId+' .jumlah-bantuan').val(1).attr('readonly', true)
  } else {
    $('#'+rowId+' .jumlah-bantuan').val('').attr('readonly', false)
  }
  calculateTotal(rowId);
}

function addRow(){
  event.preventDefault();

  var rowCount = $('#tableBantuan tr').length;

  let table = $("#tableBantuan");
  if(rowCount > 3){
    var lastRow = table.find("tbody  tr:last");
    var lastId = lastRow.attr("id");
    var newId = generateUniqueId(lastId);
    var newRow = lastRow.clone();
    newRow.attr("id", newId);

    newRow.find("input").val("");
    newRow.find(".total-bantuan").text("0");
  } else {
    var newRow = '<tr id="row0">'+
      '<td><select name="kategori[]" class="form-select kategori-bantuan">'+
        '<option></option>'+
        '<option value="Susu Balita Stunting">Susu Balita Stunting</option>'+
        '<option value="Vitamin">Vitamin</option>'+
        '<option value="Permakanan / Kudapan Protein Hewani">Permakanan / Kudapan</option>'+
        '<option value="Lain-lain">Lain-lain</option>'+
      '</select></td>'+
      '<td><input type="text" name="item[]" class="form-control keterangan-bantuan"></td>'+
      '<td><input type="text" name="jumlah[]" class="form-control jumlah-bantuan" min="1"></td>'+
      '<td><input type="text" name="nominal[]" class="form-control nominal-bantuan"></td>'+
      '<td class="align-middle text-end"><span class="total-bantuan">0</span><input type="hidden" name="total_nominal[]" class="text-total-bantuan"></td>'+
      '<td class="align-middle"><button class="btn btn-danger btn-sm" onclick="deleteRow(this);">X</button></td>'+
    '</tr>';

    var newId = 'row0';
  }

  table.find("tbody").append(newRow);

  $('#' + newId + ' .keterangan-bantuan, #' + newId + ' .jumlah-bantuan, #' + newId + ' .nominal-bantuan').on('input', function() {
    $('.jumlah-bantuan').mask('0,000', { reverse: true });
    $('.nominal-bantuan').mask('0,000,000,000', { reverse: true });
    calculateTotal(newId);
  });
}

function deleteRow(button){
  button.closest("tr").remove();
  calculateGrandTotal();
}

function setUniqueIds() {
  var rows = $("#tablebantuan tbody tr");
  var lastId = rows.last().attr("id");
  var newId = generateUniqueId(lastId);
  rows.last().attr("id", newId);
}

function generateUniqueId(lastId) {
  if (!lastId) {
    return "row0";
  } else {
    var lastNum = parseInt(lastId.match(/\d+/)[0]);
    var newNum = lastNum + 1;
    return "row"+newNum;
  }
}

function calculateTotal(rowId) {
  var jumlah = parseInt(Number($('#' + rowId + ' .jumlah-bantuan').val().replace(/\D/g, '')));
  var harga = parseInt(Number($('#' + rowId + ' .nominal-bantuan').val().replace(/\D/g, '')));
  var total = jumlah * harga;

  $('#' + rowId + ' .total-bantuan').text(total.toLocaleString('en-US'));
  $('#' + rowId + ' .text-total-bantuan').val(total);

  calculateGrandTotal();
}

function calculateGrandTotal(){
  var total_nominal = $("input[name='total_nominal[]']").map(function(){return $(this).val();}).get();
  
  var grandTotal = 0;
  total_nominal.forEach(function(total){
    total = parseInt(total);
    grandTotal += total;
  });

  $('#textTotalNominalPerBulan').text(grandTotal.toLocaleString('en-US'));
  $('#totalNominalPerBulan').val(grandTotal);

}

function get_cart_distribusi(){
  let user_id = $('#user_id').val();

  $.get('/distribusi/penerima', function(result){
    if(result.jumlah > 0){
      $('#content_detil_penerima > tr').remove();
      let html = '';
      result.data.forEach((data, index) => {
        html += '<tr>';
        html += '<td class="text-center">'+(index+1)+'</td>';
        html += '<td>'+data.name+'<br><small>Alamat Domisili : '+data.attributes.alamat_domisili+', '+data.attributes.kelurahan_domisili+', '+data.attributes.kecamatan_domisili+'</small><br><small>Alamat KTP : '+data.attributes.alamat_ktp+', '+data.attributes.kelurahan_ktp+', '+data.attributes.kecamatan_ktp+'</small></td>';
        html += '<td><ul class="mb-0">'
        data.attributes.kategori.forEach((kategori, a) => {
          html += '<li>'+kategori+' ('+data.attributes.item[a]+') sejumlah '+data.attributes.jumlah[a]+'</li>';
        });
        html += '</ul></td>'
        html += '<td>'+data.price+'</td>';
        html += '<td><div class="d-grid"><button class="btn btn-warning mb-1" onclick="btnEditPenerima('+data.id+')">Edit</button><form data-action="/distribusi/penerima/'+data.id+'" method="POST" id="formDeletePenerima'+(index+1)+'" class="d-grid"><input type="hidden" name="_method" value="DELETE"><button onclick="deletePenerima(\'formDeletePenerima'+(index+1)+'\')" class="btn btn-danger">Hapus</button></form></div></td>';
        html += '</tr>';  
      })
      $('#content_detil_penerima').append(html);
    } else {
      $('#content_detil_penerima > tr').remove();
      $('#content_detil_penerima').append('<tr><td colspan="5" class="text-center"><small class="fst-italic">Belum Ada Penerima yang Ditambahkan</small></td></tr>');
    };
  })
}

function get_cart_penarikan(){
  let user_id = $('#user_id').val();

  $.get('/penarikan/rencana-realisasi', function(result){
    if(result.jumlah > 0){
      $('#content_detil_penerima > tr').remove();
      let html = '';
      var total = 0;
      result.data.forEach((data, index) => {
        var price = parseInt(data.price);
        if(data.attributes.jenis == 'penerima'){
          html += '<tr>';
          html += '<td class="text-center">'+(index+1)+'</td>';
          html += '<td>'+data.name+'<br><small>Alamat Domisili : '+data.attributes.alamat_domisili+', '+data.attributes.kelurahan_domisili+', '+data.attributes.kecamatan_domisili+'</small><br><small>Alamat KTP : '+data.attributes.alamat_ktp+', '+data.attributes.kelurahan_ktp+', '+data.attributes.kecamatan_ktp+'</small></td>';
          html += '<td><ul class="mb-0">'
          data.attributes.kategori.forEach((kategori, a) => {
            var nominal = parseInt(data.attributes.nominal[a]);
            html += '<li>'+kategori+' ('+data.attributes.item[a]+') sejumlah '+data.attributes.jumlah[a]+' dengan harga satuan Rp. '+nominal.toLocaleString('en-US')+'</li>';
          });
          html += '</ul></td>'
          html += '<td class="text-end">'+price.toLocaleString('en-US')+'</td>';
          html += '<td><div class="d-grid"><button class="btn btn-warning mb-1" onclick="btnEditRencanaRealisasi(\''+data.id+'\', \''+data.attributes.jenis+'\')">Edit</button><form data-action="/penarikan/rencana-realisasi/'+data.id+'" method="POST" id="formDeletePenerima'+(index+1)+'" class="d-grid"><input type="hidden" name="_method" value="DELETE"><button onclick="deleteRencanaRealisasi(\'formDeletePenerima'+(index+1)+'\')" class="btn btn-danger">Hapus</button></form></div></td>';
          html += '</tr>';
        } else if(data.attributes.jenis == 'barang'){
          var nominal = parseInt(data.attributes.nominal);
          html += '<tr>';
          html += '<td class="text-center">'+(index+1)+'</td>';
          html += '<td> - </td>';
          html += '<td>'+data.name+' sejumlah '+data.quantity+' dengan harga satuan Rp. '+nominal.toLocaleString('en-US')+'</td>';
          html += '<td class="text-end">'+price.toLocaleString('en-US')+'</td>';
          html += '<td><div class="d-grid"><button class="btn btn-warning mb-1" onclick="btnEditRencanaRealisasi(\''+data.id+'\', \''+data.attributes.jenis+'\')">Edit</button><form data-action="/penarikan/rencana-realisasi/'+data.id+'" method="POST" id="formDeletePenerima'+(index+1)+'" class="d-grid"><input type="hidden" name="_method" value="DELETE"><button onclick="deleteRencanaRealisasi(\'formDeletePenerima'+(index+1)+'\')" class="btn btn-danger">Hapus</button></form></div></td>';
          html += '</tr>';  
        }
        total += price;
      })
      html += '<tr>';
      html += '<td class="fw-bold" colspan="3">Total Dana yang akan dicairkan</td>';
      html += '<td class="text-end fw-bold">'+total.toLocaleString('en-US')+'</td>';
      html += '<td></td>';
      html += '</tr>';  
      $('#content_detil_penerima').append(html);
    } else {
      $('#content_detil_penerima > tr').remove();
      $('#content_detil_penerima').append('<tr><td colspan="5" class="text-center"><small class="fst-italic">Belum Ada Penerima yang Ditambahkan</small></td></tr>');
    };
  })
}