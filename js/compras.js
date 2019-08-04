
$( document ).ready(function(){
  //campos numericos
  $("#codigo").numeric({ decimal: false, negative: false });
  $("#cantidad").numeric({ decimalPlaces: 3, negative: false });

  $(document).keydown(function(event) {
    if(event.which == 114) { //F3 - [click] buscar
      buscar();
      return false;
    }
  });

  $("body").on('click', '#buscarpr', function () {
    $.ajax({
      url: base_url + 'pventa/buscarpr',
      data: {},
      type: 'POST',
      success: function (msg) {
        $(".modal-header").html('<span class="text-right" style="font-size:17px"><i class="fa fa-binoculars" aria-hidden="true"></i> Buscar Producto</span><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><font color="#FF0000"><i class="fa fa-times" aria-hidden="true"></i></font></span></button><br>');
        $(".modal-body").html(msg);
        $(".modal-footer").html("");
        callDatatableBuscar();
        $("#Modal").modal("show");
      },
      error: function (data) {
        alerta('Error interno x4', data['responseText'], '');
      }
    });
    return false;
  });



  $("body").on('click', '.addCar', function () {
    var code=$(this).data("code");
    $("#codigo").val(code);
    $("#Modal").modal("hide");
    addProducto();
    return false;
  });

  $("body").on('click', '#buscarpr', function () {
    buscar();
    return false;
  });

  $("body").on('click', '.addCar', function () {
    var code=$(this).data("code");
    $("#codigo").val(code);
    $("#Modal").modal("hide");
    return false;
  });



}); // fin ready


function buscar(){
  $(".modal-header").html('<span class="text-right" style="font-size:17px"><i class="fa fa-binoculars" aria-hidden="true"></i> Buscar Producto</span><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><font color="#FF0000"><i class="fa fa-times" aria-hidden="true"></i></font></span></button><br>');
  $(".modal-body").html("<center><i class=\"fa fa-spinner fa-spin fa-5x fa-fw\"></i></center>");
  $(".modal-footer").html("");
  $("#Modal").modal("show");
  $.ajax({
    url: base_url + 'pventa/buscarpr',
    data: {},
    type: 'POST',
    success: function (msg) {
      $(".modal-body").html(msg);
      callDatatableBuscar();
    },
    error: function (data) {
      alerta('Error interno x5', data['responseText'], '');
    }
  });
  return false;
}

/* ********************************************************
detecta nuvo registro ya existente
********************************************************* */
$.fn.delayPasteKeyUp = function(fn, ms)
{
  var timer = 0;
  $(this).on("propertychange input", function()
  {
    clearTimeout(timer);
    timer = setTimeout(fn, ms);
  });
};

$("#codigo").delayPasteKeyUp(function(){
  addProducto();
}, 100);


// $('body').on('submit', '#formcompraCodigo', function () {
//   $("#cantidad").blur();
//   $("#codigo").focus();
//   return false;
// });




$(document).on('click', '.delFila', function (event) {
  event.preventDefault();
  var codigo = $(this).closest('tr').find("td")[0].innerHTML;
  var cantidad = $(this).closest('tr').find("td")[3].innerHTML;
  var idVenta = $("#idVentax").html();
  var miTotal = parseFloat($("#miTotalx").html());
  $.ajax({
    url: base_url + 'pventa/importe',
    data: {codigo:codigo,cantidad:cantidad},
    type: 'POST',
    success: function (resultado) {

    },
    error: function (data) {
      alerta('Error interno', data['responseText'], '');
    }
  });

  $.ajax({
    url: base_url + 'compras/delItemToCompra',
    data: {codigo:codigo},
    type: 'POST',
    success: function (resultadox2) {
      if(resultadox2!="1")
      {
        alerta("Error al eliminar fila",resultadox2,"");
      }
    },
    error: function (data) {
      alerta('Error interno', data['responseText'], '');
    }
  });

  $(this).closest('tr').remove();
  actualizarTotal();
});









// ****************************************************************
// funciones
// ****************************************************************
function addProducto() {
  var codigo = $("#codigo").val();
  var cantida = $("#cantidad").val();
  if(cantida<=0){
    $("#cantidad").val(1);
    $("#codigo").val("");
    return false;
  }
  if(codigo.length>12){
    $.ajax({
      // async:false,
      url: base_url + 'compras/verificarProducto',
      data: {codigo: codigo},
      type: 'POST',
      success: function (resultado) {
        if(resultado==1)
        {
          // OOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
          $.ajax({
            url: base_url + 'pventa/getItem',
            data: {codigo: codigo},
            type: 'POST',
            dataType: "json",
            success: function (resultadoItem) {
              cantidad = parseFloat(cantida);
              var costo=resultadoItem['costo'];
              var importe = cantidad*parseFloat(resultadoItem['costo']);
              $('#tbventa tr:last').after("<tr>"+
              "<td>"+resultadoItem['codigo']+"</td>"+
              "<td>"+resultadoItem['descripcion']+"</td>"+
              "<td>"+
              "<div class='col-md-4'><button class=\"btn btn-primary btn-xs tdcosto\" data-id=\""+resultadoItem['id']+"\"  data-value=\""+resultadoItem['costo']+"\">"+monedaString+" "+miFormato(costo)+"</button></div>"+
              "</td>"+
              "<td id=\"cantidadx"+resultadoItem['id']+"\">"
              +cantidad+
              "</td>"+
              "<td id=\"importex"+resultadoItem['id']+"\">"
              +monedaString+" "+miFormato(importe)+
              "</td>"+
              "<td class='text-center'>"+
              "<button class='delFila btn btn-danger btn-xs'><i class='fa fa-times-circle' aria-hidden='true'></i></button>"+
              "</td>"+
              "<td class='hidden' id=\"importey"+resultadoItem['id']+"\">"+
              importe
              +"</td>"+
              "</tr>");
              $("#cantidad").val(1);
              $("#codigo").val("");
              // WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
              var idItem = resultadoItem['id'];
              var codigo = resultadoItem['codigo'];
              $.ajax({
                url: base_url + 'compras/addItemToCompra',
                data: {idItem:idItem, cantidad:cantidad},
                type: 'POST',
                dataType: "json",
                success: function (r) {
                  debugger;
                  if(r['validante']=="r")
                  {
                    $("#tbventa tr").find('td:eq(0)').each(function () {
                      //obtenemos el codigo de la celda
                      icodigo = $(this).html();
                      if(codigo==icodigo){
                        $(this).closest('tr').remove();
                      }
                    });
                    //actualizamos el importe
                    if(r['ncantidad']<resultadoItem['cantidadMayoreo'])
                    {
                      var importe = r['ncantidad']*parseFloat(resultadoItem['precio']);
                    }
                    else{
                      var importe = r['ncantidad']*parseFloat(resultadoItem['precioMayoreo']);
                      precio = resultadoItem['precioMayoreo'];
                    }
                    // agregamos una nueva fila con el codigo
                    // $('#tbventa tr:last').after("<tr><td>"+resultadoItem['codigo']+"</td><td>"+resultadoItem['descripcion']+"</td><td>"+monedaString+" "+precio+"</td><td>"+r['ncantidad']+"</td><td>"+monedaString+" "+new oNumero(importe).formato(2, true)+"</td><td class='text-center'><button class='delFila btn btn-danger btn-xs'><i class='fa fa-times-circle' aria-hidden='true'></i></button></td><td class='hidden'>"+importe+"</td></tr>");
                  }
                  // recorremos la tabla para calcular el importe

                  // Actualizamos el total
                  actualizarTotal();
                },
                error: function (data) {
                  alerta('Error interno x2', data['responseText'], '');
                }
              });
              // WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
            },
            error: function (data) {
              alerta('Error interno x2', data['responseText'], '');
            }
          });
          // OOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
        }
        else if(resultado==0){
          alerta("Mensaje","No hay registros que coincidan con el código ingresado.","");
        }
        else if(resultado==2){
          alertaMini("Mensaje","El articulo ya se ecuentra en la lista ","");
        }
        $("#codigo").val('');
        return false;
      },
      error: function (data) {
        alerta('Error interno x1', data['responseText'], '');
      }
    });
    funcionesCosto();
  }

}




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

$("body").on('click', '.cobrar', function () {
  var total = $("#miTotalx").html();
  var idVenta=$("#idVentax").html();

  var tipo = $(this).data("tipo");

  if(impuesto=="1"){
    total=$("#miTotal2x").html();
  }
  total = parseFloat(total).toFixed(2);

  if(total<=0)
  {
    $(".modal-header-mini").html('<span class="text-right" style="font-size:17px">Cobrar</span><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><font color="#FF0000"><i class="fa fa-times" aria-hidden="true"></i></font></span></button><br>');
    $(".modal-body-mini").html("<center><h2>Lista Vacia</h2></center>");
    $(".modal-footer-mini").html("");
    $(".modal-footer").html('<button type="button" class="btn btn-default" autofocus data-dismiss="modal">Aceptar</button>');
    $("#ModalMini").modal("show");
    return false;
  }

  if(tipo==1){
    debugger;
    cobrart(total,idVenta,impuesto);
    return false;
  }

  $.ajax({
    url: base_url + 'compras/v_recibido',
    data: {total:total,idVenta:idVenta,impuesto:impuesto,tipo:tipo},
    type: 'POST',
    success: function (html) {
      $(".modal-header-mini").html('<span class="text-right" style="font-size:17px"><i class="fa fa-money" aria-hidden="true"></i> Cobrar</span><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><font color="#FF0000"><i class="fa fa-times" aria-hidden="true"></i></font></span></button><br>');
      $(".modal-body-mini").html(html);
      $(".modal-footer-mini").html("");
      $('#ModalMini').modal({
        backdrop: 'static',
        keyboard: false
      });
      $("#ModalMini").modal("show");
      $("#recibido").numeric({ decimalPlaces: 2, negative: false });
      $("#recibido").focus();
    }
  });
});

function guardarCompra(){
  debugger;
  var total = $("#miTotalx").html();
  var folio = $('#folio').val();
  var proveedor = $('#proveedor').val();
  if(total==0 || total==""){
    alertaMini('Mensaje', "Lista de compras vacia", '');
    return false;
  }
  if(folio==""){
    alertaMini('Mensaje', "Es necesario ingresar un folio de compra", '');
    return false;
  }
  $("#general").addClass("hide");
  $.ajax({
    async:false,
    url: base_url + 'compras/concretarCompra',
    data: {total:total,folio:folio,proveedor:proveedor},
    type: 'POST',
    // dataType: "json",
    success: function (resp) {
      debugger;
      if(resp=="x1"){
        alertaMini('Mensaje', "Es necesario ingresar un folio de compra", '');
        return false;
      }
      if(resp=="x2"){
        alertaMini('Mensaje', "Lista de compras vacia", '');
        return false;
      }
      location.reload();
    },
    error: function (data) {
      alerta('Error interno', data['responseText'], '');
    }
  });
  return false;
}



function funcionesCosto(){

  $("body").on('click', '.tdcosto', function () {
    var id = $(this).data("id");
    var value = $(this).data("value");
    var control = "<div class=\"input-group\">"+
    "<input class=\"form-control inputnewcosto\" id=\"inputnewcostox"+id+"\" data-id=\""+id+"\" value=\""+value+"\" maxlength=\"5\">"+
    "<span class=\"input-group-btn\">"+
    "<button class=\"btn btn-fill btn-warning btnnewcosto\" type=\"button\" id=\"btnRastrear1\"  data-id=\""+id+"\">Actualizar</button>"+
    "</span>"+
    "</div>";
    $(this).closest("td").html(control);
    return false;
  });

  $('body').on('click', '.btnnewcosto', function () {
    var idProducto = parseInt(   $(this).data("id")   );
    var newCosto = $("#inputnewcostox"+idProducto).val();
    if(newCosto!=undefined){
      $.ajax({
        url: base_url + 'compras/saveNewCostoTemporal',
        data: {idProducto:idProducto,newCosto:newCosto},
        type: 'POST'
      });
      $(this).closest("td").html(monedaString+" "+newCosto);
      var cantidad = parseInt($("#cantidadx"+idProducto).html());
      var newImporte=newCosto*cantidad;
      $("#importex"+idProducto).html(monedaString+" "+newImporte);
      $("#importey"+idProducto).html(newImporte);

      actualizarTotal();
    }
    return false;
  });


  // $.ajax({
  //   url: base_url + 'pventa/NuevaVenta',
  //   data: {},
  //   type: 'POST',
  //   success: function (idVenta) {
  //     $("#miVenta").removeClass("hidden");
  //     $("#idVenta").removeClass("hidden");
  //     $("#libuscar").removeClass("hidden");
  //     $("#idVentax").html(idVenta);
  //     $("#divbtnnv").html("");
  //   },
  //   error: function (data) {
  //     alerta('Error interno x3', data['responseText'], '');
  //   }
  // });
}

function actualizarTotal() {
  var nuevoTotal=0;
  $("#tbventa tr").find('td:eq(6)').each(function () {
    nuevoTotal += parseFloat($(this).html());
  });
  var totalImprimible=nuevoTotal;
  totalImprimible=Math.round(totalImprimible);
  //Actualizamos el total
  $("#miTotalx").html(  totalImprimible  );
  $("#miTotal").html(   miFormato(totalImprimible)   );
  if(impuesto=="1"){
    var totalImpuesto = Math.round(nuevoTotal*impuestoPorciento/100);
    var miTotal2 = Math.round(nuevoTotal+totalImpuesto);
    $("#miImpuestox").html(   totalImpuesto   );
    $("#miImpuesto").html(   miFormato(totalImpuesto)  );
    $("#miTotal2x").html(   miTotal2   );
    $("#miTotal2").html(   miFormato(miTotal2)  );
  }
}




function miFormato(nStr) {
  nStr += '';
  x = nStr.split('.');
  x1 = x[0];
  x2 = x.length > 1 ? ',' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, '$1' + '.' + '$2');
  }
  return x1;
  // return x1 + x2;
}
