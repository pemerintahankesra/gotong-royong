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

  var totalPerBulan = 0;
  $('input[name="total_nominal[]"]').each(function(){
    totalPerBulan += parseInt($(this).val());
  })
  $('#textTotalNominalPerBulan').text(totalPerBulan.toLocaleString('en-US'));
  $('#totalNominalPerBulan').val(totalPerBulan);
}

function get_cart_distribusi(){
  let user_id = $('#user_id').val();

  $.get('/distribusi/penerima', function(response){
    let result = JSON.parse(response);
    console.log(result.jumlah);
    if(result.jumlah > 0){
      $('#content_detil_penerima > tr').remove();
      let html = '';
      result.data.forEach((data, index) => {
        html += '<tr>';
        html += '<td class="text-center">'+(index+1)+'</td>';
        html += '<td>'+data.name+'</td>';
        html += '<td><ul class="mb-0">'
        data.attributes.kategori.forEach((kategori, a) => {
          html += '<li>'+kategori+' ('+data.attributes.item[a]+') sejumlah '+data.attributes.jumlah[a]+'</li>';
        });
        html += '</ul></td>'
        html += '<td>'+data.price+'</td>';
        html += '<td><div class="d-grid"><button class="btn btn-warning">Edit</button><form data-action="/distribusi/penerima/'+data.id+'" method="POST" id="formDeletePenerima'+(index+1)+'"><input type="hidden" name="_method" value="DELETE"><button onclick="deletePenerima(\'formDeletePenerima'+(index+1)+'\')" class="btn btn-danger">Hapus</button></form></div></td>';
        html += '</tr>';  
      })
      $('#content_detil_penerima').append(html);
    } else {
      $('#content_detil_penerima > tr').remove();
      $('#content_detil_penerima').append('<tr><td colspan="5" class="text-center"><small class="fst-italic">Belum Ada Penerima yang Ditambahkan</small></td></tr>');
    };
  })
}