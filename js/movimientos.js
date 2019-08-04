$( document ).ready(function(){






  /* ********************************************************
  DataTable
  ********************************************************* */
  var table = $('#tb_movimientos').DataTable( {

    language: {
      processing: "<i class='fa fa-spinner fa-5x fa-spin fa-fw' aria-hidden='true'></i>",
      search: "<i class='fa fa-search' aria-hidden='true'></i>",
      lengthMenu:     "Mostrando _MENU_ productos",
      info:           "Mostrando del _START_ al _END_ de _TOTAL_ productos",
      infoEmpty:      "Mostrando 0 al 0 de 0 coincidencias",
      infoFiltered: "(filtrado de un total de _MAX_ elementos)",
      infoPostFix: "",
      loadingRecords: "<i class='fa fa-spinner fa-5x fa-spin fa-fw' aria-hidden='true'></i>",
      zeroRecords: "No se encontraron coincidencias",
      emptyTable: "No hay datos para mostrar",
      paginate: {
        first: "<i class='fa fa-fast-backward fa-lg' aria-hidden='true'></i>",
        previous: "<i class='fa fa-backward fa-lg' aria-hidden='true'></i>",
        next: "<i class='fa fa-forward fa-lg' aria-hidden='true'></i>",
        last: "<i class='fa fa-fast-forward fa-lg' aria-hidden='true'></i>"
      }
      //,
      //aria: {
      //    sortAscending: ": activate to sort column ascending",
      //    sortDescending: ": activate to sort column descending"
      //}
    },
    lengthMenu: [
      [ 10, 25, 50, -1 ],
      [ '10', '25', '50', 'Todo' ]
    ],
    buttons: [
      { extend: 'colvis', text: '<i class="fa fa-eye" aria-hidden="true"></i>' },{ extend: 'copy', text: '<i class="fa fa-clipboard" aria-hidden="true"></i>' }, { extend: 'excel', text: '<i class="fa fa-file-excel-o text-success" aria-hidden="true"></i>',title: 'Mi Inventario' }, { extend: 'pdf', text: '<i class="fa fa-file-pdf-o text-danger" aria-hidden="true"></i>',title: 'Mi Inventario' },{ extend: 'print', text: '<i class="fa fa-print" aria-hidden="true"></i>' }

    ],
    columnDefs:[
      { orderable: false, targets: [3] }
    ],
    order:[
      [ 5, 'desc' ]
    ],
    // stateSave: true
  } );
  table.buttons().container()
  .appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );






  $('body').on('click', '.detalle', function () {
    var idVenta=$(this).data("id");
    $.ajax({
      url: base_url + 'ventas/verDetalle',
      data: {idVenta:idVenta},
      type: 'POST',
      success: function (html) {
        // xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
        $(".modal-header-lg").html('<span class="text-right" style="font-size:17px"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Detalles de Venta '+idVenta+'</span><button type="button" class="close" data-dismiss="modal" onclick="location.reload();" aria-label="Close"><span aria-hidden="true"><font color="#FF0000"><i class="fa fa-times" aria-hidden="true"></i></font></span></button><br>');
        $(".modal-body-lg").html(html);
        $(".modal-footer-lg").html('<button type="button" class="btn btn-default" data-dismiss="modal" >Cerrar</button>');
        $('#ModalLG').modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#ModalLG").modal("show");
        return false;
        // xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
      },
      error: function (data) {
        alerta('Error interno x7', data['responseText'], '');
      }
    });

  });





});



// ****************************************************************
// funciones
// ****************************************************************
$.fn.delayPasteKeyUp = function(fn, ms)
{
  var timer = 0;
  $(this).on("propertychange input", function()
  {
    clearTimeout(timer);
    timer = setTimeout(fn, ms);
  });
};


function miAlerta(tipo,titulo,mensaje) {
  var msj='<div class="alert alert-'+tipo+' alert-dismissible" role="alert">'+
  '<button type="button" class="close pull-right" data-dismiss="alert" aria-label="Close"><i class="fa fa-times fa-lg" aria-hidden="true"></i></button>'+
  '<strong>'+titulo+' </strong>'+mensaje+'.'+
  '</div>';
  return msj;
}



function esconderAlerta(){
  setTimeout(function() {
    $("#msj").fadeOut(1500);
  },1500);
  setTimeout(function() {
    $("#msj").html("");
    $("#msj").fadeIn(1);
  },3000);
}
