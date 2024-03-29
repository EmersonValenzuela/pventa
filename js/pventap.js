$( document ).ready(function(){
  //campos numericos
  $("#codigo").numeric({ decimal: false, negative: false });
  $("#cantidad").numeric({ decimalPlaces: 3, negative: false });
  $(".select2").select2();



  $(document).on('change', '#cliente', function () {
    var idVenta=$("#idVentax").html();
    var idCliente = $(this).val()

    if(idCliente=="x"){
      $.ajax({
        url: base_url + 'pventa/addClienteHtml',
        data: {},
        type: 'POST',
        success: function (html) {
          $(".modal-header").html('<span class="text-right" style="font-size:17px"><i class="fa fa-user-plus" aria-hidden="true"></i> Agregar Cliente</span><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><font color="#FF0000"><i class="fa fa-times" aria-hidden="true"></i></font></span></button><br>');
          $(".modal-body").html(html);
          $(".modal-footer").html("");
          $("#Modal").modal("show");
          return false;
        },
        error: function (data) {
          alerta('Error interno', data['responseText'], '');
        }
      });
      return false;
    }

    $.ajax({
      url: base_url + 'pventa/actualizarClienteVenta',
      data: {idVenta: idVenta,idCliente: idCliente},
      type: 'POST'
    });
  });


  $('body').on('submit', '#formAddCliente', function () {
    $("#Modal").modal("hide");
    $.ajax({
      url: base_url + 'pventa/addCliente',
      data: $(this).serialize(),
      type: 'POST',
      dataType: 'json',
      success: function (resultado) {
        $('#cliente').append(new Option(resultado.nombre, resultado.id, true, true));
        return false;
      },
      error: function (data) {
        alerta('Error interno', data['responseText'], '');
      }
    });
    return false;
  });
  // *******************************************************************************************
  // *******************************************************************************************
  // *******************************************************************************************
  // *******************************************************************************************
  // *******************************************************************************************
  // *******************************************************************************************
  $(document).on('submit', '#formnewcredito', function () {
    var data = $(this).serialize();
    $.ajax({
      url: base_url + 'pventa/newCredito',
      data: data,
      type: 'POST',
      success: function (resp) {
        //
      },
      error: function (data) {
        alerta('Error interno', data['responseText'], '');
      }
    });
    concretarVenta(data);
    return false;
  });


  $(document).on('submit', '#formrecibido', function () {
    var data = $(this).serialize();
    concretarVenta(data);
    return false;
  });


  function concretarVenta(data){
    $.ajax({
      url: base_url + 'pventa/concretarVenta',
      data: data,
      type: 'POST',
      dataType: "json",
      success: function (resp) {
        if(resp['cambio']<0)
        {
          $("#cobrar").click();
          return false;
        }
        else {
          $("#libuscar").addClass("hidden");
          $("#general").html('<center><button type="button" class="btn btn-default" onclick="location.reload();">Venta finalizada</button><center>');
          $(".modal-header-mini").html('<span class="text-right" style="font-size:17px"><i class="fa fa-money" aria-hidden="true"></i> Cambio</span><button type="button" class="close" data-dismiss="modal" onclick="location.reload();" aria-label="Close"><span aria-hidden="true"><font color="#FF0000"><i class="fa fa-times" aria-hidden="true"></i></font></span></button><br>');

          if(impuesto)
          {
            $(".modal-body-mini").html("<center>"+
            "<h3 class='sin-margen'>Gracias por su compra</h3>"+
            "<label class='text-success sin-margen'>Total: $"+formatoMonedaChile(resp['total'])+"</label>"+
            "<br>"+
            "<label class='text-warning sin-margen'>Recibido: $"+formatoMonedaChile(resp['recibido'])+"</label>"+
            "<br>"+
            "<label class='text-primary sin-margen'>Cambio: $"+formatoMonedaChile(resp['cambio'])+"</label>"+
            "<br><button type=\"button\" class=\"btn btn-negro\ impVenta\" data-recibido=\""+resp['recibido']+"\"><i class=\"fa fa-print\" aria-hidden=\"true\"></i> Imprimir</button>"+
            "</center>");
          }
          else {
            $(".modal-body-mini").html("<center><h2>Gracias por su compra</h2><label class='text-success'>Total: $"+new oNumero(resp['total']).formato(2, true)+"</label><br><label class='text-warning'>Recibido: $"+new oNumero(resp['recibido']).formato(2, true)+"</label><br><label class='text-primary'>Cambio: $"+new oNumero(resp['cambio']).formato(2, true)+"</label></center>");
          }

          $(".modal-footer-mini").html("");
          $(".modal-footer").html('<button type="button" class="btn btn-default" autofocus onclick="location.reload();">Finalizar</button>');
          $('#ModalMini').modal({
            backdrop: 'static',
            keyboard: false
          });
          $("#ModalMini").modal("show");
          return false;
        }
      },
      error: function (data) {
        alerta('Error interno', data['responseText'], '');
      }
    });
  }



  // $("body").on('click', '.impVenta', function () {
  //   var idVenta=$("#idVentax").html();
  //   $.ajax({
  //     url: base_url + 'pventa/impVenta',
  //     data: {idVenta:idVenta},
  //     type: 'POST',
  //     success: function (html) {
  //       if (window.print) {
  //         // ------------------------------------
  //         var ventimp = window.open(' ', 'popimpr');
  //         ventimp.document.write( html );
  //         ventimp.document.close();
  //         ventimp.print( );
  //         ventimp.close();
  //         // ------------------------------------
  //       } else {
  //         alert("La función de impresion no esta soportada por su navegador.");
  //       }
  //     }
  //   });
  //   // location.reload();
  // });


  $("body").on('click', '.impVenta', function () {
    var recibido = $(this).data("recibido");
    var idVenta=$("#idVentax").html();
    // imprimirTicket(idVenta,idEst);
    // $("#formTicket").submit();
    $.ajax({
      url: base_url + 'pventa/impVenta',
      data: {idVenta:idVenta,recibido:recibido},
      type: 'POST',
      success: function (html) {
        // if (window.print) {
        //   // ------------------------------------
        //   var ventimp = window.open(' ', 'popimpr');
        //   ventimp.document.write( html );
        //   ventimp.document.close();
        //   ventimp.print( );
        //   // ventimp.close();
        //   // ------------------------------------
        // } else {
        //   alert("La función de impresion no esta soportada por su navegador.");
        // }
      },
      error: function (data) {
        alerta('Error interno', data['responseText'], '');
      }
    });
  });





  $("body").on('click', '#cobrar', function () {
    var total = $("#miTotalx").html();
    var idVenta= $("#idVentax").html();
    var descuento = $("#miDescuento").html();

    if(impuesto=="1"){
      total=$("#miTotal2x").html();
    }
    total = parseFloat(total).toFixed(2);

    if(total<=0)
    {
      $(".modal-header-mini").html('<span class="text-right" style="font-size:17px"><i class="fa fa-money" aria-hidden="true"></i> Cobrar</span><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><font color="#FF0000"><i class="fa fa-times" aria-hidden="true"></i></font></span></button><br>');
      $(".modal-body-mini").html("<center><h2>Lista Vacia</h2></center>");
      $(".modal-footer-mini").html("");
      $(".modal-footer").html('<button type="button" class="btn btn-default" autofocus data-dismiss="modal">Aceptar</button>');
      $("#ModalMini").modal("show");
      return false;
    }
    $.ajax({
      url: base_url + 'pventa/v_recibido',
      data: {total:total,idVenta:idVenta,impuesto:impuesto,descuento:descuento},
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
        $("#recibido").numeric({ decimal: false, negative: false });
        $("#recibido").focus();
      }
    });
  });

  $("body").on('click', '#btnNV', function () {
    $.ajax({
      url: base_url + 'pventa/NuevaVenta',
      data: {},
      type: 'POST',
      success: function (idVenta) {
        $("#miVenta").removeClass("hidden");
        $("#idVenta").removeClass("hidden");
        $("#libuscar").removeClass("hidden");
        $("#idVentax").html(idVenta);
        $("#divbtnnv").html("");
      },
      error: function (data) {
        alerta('Error interno x3', data['responseText'], '');
      }
    });
  });

  $("body").on('click', '#buscarpr', function () {
    buscar();
    return false;
  });



  $("body").on('click', '.addCar', function () {
    var code=$(this).data("code");
    $("#codigo").val(code);
    $("#Modal").modal("hide");
    addProducto();
    return false;
  });


  /* ********************************************************
  detecta nuvo registro ya existente
  ********************************************************* */
  $("#codigo").delayPasteKeyUp(function(){
    addProducto();
  }, 200);


  $('body').on('submit', '#formventaCodigo', function () {
    $("#cantidad").blur();
    $("#codigo").focus();
    return false;
  });




  $(document).on('click', '.delFila', function (event) {
    event.preventDefault();
    var codigo = $(this).closest('tr').find("td")[0].innerHTML;
    var cantidad = $(this).closest('tr').find("td")[3].innerHTML;
    var idVenta = $("#idVentax").html();
    var miTotal = parseFloat($("#miTotalx").html());
    $.ajax({
      async:false,
      url: base_url + 'pventa/importe',
      data: {codigo:codigo,cantidad:cantidad},
      type: 'POST',
      success: function (resultado) {
        var nuevoTotal = miTotal-resultado;
        $("#miTotalx").html(   nuevoTotal   );
        $("#miTotal").html(   formatoMonedaChile(nuevoTotal)   );
        if(impuesto=="1"){
          var totalImpuesto =nuevoTotal*impuestoPorciento/100;
          var miTotal2 = nuevoTotal+totalImpuesto;
          $("#miImpuestox").html(   totalImpuesto   );
          $("#miImpuesto").html(   formatoMonedaChile(totalImpuesto)   );
          $("#miTotal2x").html(   miTotal2   );
          debugger; // pp
          $("#miTotal2").html(   formatoMonedaChile(miTotal2)   );
        }
      },
      error: function (data) {
        alerta('Error interno', data['responseText'], '');
      }
    });
    $.ajax({
      async:false,
      url: base_url + 'pventa/delItemToVenta',
      data: {codigo:codigo,idVenta:idVenta},
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
    // $(this).closest('tr').remove();
    location.reload();
  });



  $("body").on('click', '.movcaja', function () {
    var tipo = $(this).data("id");
    movcaja(tipo);
    return false;
  });


  $(document).on('submit', '#formMoviCaja', function () {
    $.ajax({
      url: base_url + 'pventa/newMoviCaja',
      data: $(this).serialize(),
      type: 'POST',
      success: function (resp) {
        if(resp=="x"){
          $(".modal-body-mini").html("<span class=\"bg-danger\">La contraseña de Administrador no es correcta.</span>");
        }
        else{
          $(".modal-body-mini").html("<span class=\"bg-success\">El movimiento fue registrado con exito.<span>");
        }
      },
      error: function (data) {
        alerta('Error interno', data['responseText'], '');
      }
    });
    return false;
  });


  $(document).on('change', '#sldImpresora', function () {
    var idImpresora = $(this).val();
    $.ajax({
      url: base_url + 'pventa/relacionarImpresora',
      data: {idImpresora:idImpresora},
      type: 'POST',
      success: function (resp) {
        //
      },
      error: function (data) {
        alerta('Error interno', data['responseText'], '');
      }
    });
    return false;
  });

  $(document).keydown(function(event) {
    if(event.which == 112) { //F1 - [Focus] Código de barras
      $("#codigo").focus();
      return false;
    }
    if(event.which == 113) { //F2 - [Focus] cantidad
      $("#cantidad").val("");
      $("#cantidad").focus();
      return false;
    }
    else if(event.which == 114) { //F3 - [click] buscar
      buscar();
      return false;
    }
    else if(event.which == 115) { //F4 - [click] Entrada a caja
      movcaja(1);
      return false;
    }
    // else if(event.which == 116) { //F5 - [click] Salida de caja
    //   movcaja(2);
    //   return false;
    // }
    else if(event.which == 117) { //F6 - [click] cobrar Efectivo
      cobrar(0);
      return false;
    }
    else if(event.which == 118) { //F7 - [click] cobrar Tarjeta
      cobrar(1);
      return false;
    }
    else if(event.which == 119) { //F8 - [click] cobrar a crédito
      cobrar(2);
      return false;
    }
    else if(event.which == 120) { //F9 - nada
      // nada por aqui
      return false;
    }
    else if(event.which == 121) { //F10 - nada
      // nada por aqui
      return false;
    }
    else if(event.which == 122) { //F11 - nada
      // nada por aqui
      return false;
    }
    else if(event.which == 123) { //F12 - nada
      // nada por aqui
      return false;
    }
  });

}); // fin ready



// ****************************************************************
// funciones
// ****************************************************************
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

function movcaja(tipo) {
  if(tipo==1){
    $(".modal-header-mini").html('<span class="text-right" style="font-size:17px"><i class="fa fa-sign-in" aria-hidden="true"></i> Registrar Entrada a Caja</span><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><font color="#FF0000"><i class="fa fa-times" aria-hidden="true"></i></font></span></button><br>');
  }
  else if(tipo==2){
    $(".modal-header-mini").html('<span class="text-right" style="font-size:17px"><i class="fa fa-sign-out" aria-hidden="true"></i> Registrar Salida de Caja</span><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><font color="#FF0000"><i class="fa fa-times" aria-hidden="true"></i></font></span></button><br>');
  }
  $(".modal-body-mini").html("<center><i class=\"fa fa-spinner fa-spin fa-5x fa-fw\"></i></center>");
  $(".modal-footer-mini").html("");
  $('#ModalMini').modal({
    backdrop: 'static',
    keyboard: false
  });
  $("#ModalMini").modal("show");
  $.ajax({
    url: base_url + 'pventa/formMoviCaja',
    data: {tipo:tipo},
    type: 'POST',
    success: function (html) {
      $(".modal-body-mini").html(html);
      $("#monto").numeric({ decimalPlaces: 2, negative: false });
    }
  });
}

function cobrar(tipo) {
  var total = $("#miTotalx").html();
  var idVenta=$("#idVentax").html();
  var descuento = $("#miDescuento").html();

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

  $(".modal-header-mini").html('<span class="text-right" style="font-size:17px"><i class="fa fa-money" aria-hidden="true"></i> Cobrar</span><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><font color="#FF0000"><i class="fa fa-times" aria-hidden="true"></i></font></span></button><br>');
  $(".modal-body-mini").html("<center><i class=\"fa fa-spinner fa-spin fa-5x fa-fw\"></i></center>");
  $(".modal-footer-mini").html("");
  $('#ModalMini').modal({
    backdrop: 'static',
    keyboard: false
  });

  if(tipo==2)
  {
    $(".modal-header-mini").html('<span class="text-right" style="font-size:17px">Nuevo Crédito</span><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><font color="#FF0000"><i class="fa fa-times" aria-hidden="true"></i></font></span></button><br>');
  }
  $("#ModalMini").modal("show");

  $.ajax({
    url: base_url + 'pventa/v_recibido',
    data: {total:total,idVenta:idVenta,impuesto:impuesto,tipo:tipo},
    type: 'POST',
    success: function (html) {
      $(".modal-body-mini").html(html);
      $("#recibido").numeric({ decimalPlaces: 2, negative: false });
      $("#numeroplazo").focus();
    }
  });
}

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
      async:false,
      url: base_url + 'pventa/verificarProducto',
      data: {codigo: codigo},
      type: 'POST',
      success: function (resultado) {
        if(resultado)
        {
          // OOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
          $.ajax({
            async:false,
            url: base_url + 'pventa/getItem',
            data: {codigo: codigo},
            type: 'POST',
            dataType: "json",
            success: function (resultadoItem) {
              cantidad = parseFloat(cantida);
              var precio=resultadoItem['precio'];
              var precioReal = precio;
              if(cantidad<resultadoItem['cantidadMayoreo'])
              {
                var importe = cantidad*parseFloat(resultadoItem['precio']);
              }
              else{
                var importe = cantidad*parseFloat(resultadoItem['precioMayoreo']);
                precio = resultadoItem['precioMayoreo'];
              }
              var precioImprimible = Math.round(precio);
              var importeImprimible = precioImprimible;
              // if(cantidad>1)
              // {
              importeImprimible=Math.round(importe);
              // }
              $('#tbventa tr:last').after("<tr><td>"+resultadoItem['codigo']+"</td><td>"+resultadoItem['descripcion']+"</td><td>$ "+formatoMonedaChile(precioImprimible)+"</td><td>"+cantidad+"</td><td>$ "+formatoMonedaChile(importeImprimible)+"</td><td class='text-center'><button class='delFila btn btn-danger btn-xs'><i class='fa fa-times-circle' aria-hidden='true'></i></button></td><td class='hidden'>"+importe+"</td></tr>");
              $("#cantidad").val(1);
              $("#codigo").val("");
              // WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
              var idVenta=$("#idVentax").html();
              var idItem = resultadoItem['id'];
              var codigo = resultadoItem['codigo'];
              $.ajax({
                async:false,
                url: base_url + 'pventa/addItemToVenta',
                data: {idVenta: idVenta,idItem:idItem, cantidad:cantidad},
                type: 'POST',
                dataType: "json",
                success: function (r) {
                  var numproductos = 0;
                  $("#tbventa tr").find('td:eq(0)').each(function () {
                    numproductos++;
                  });
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
                    // if(r['ncantidad']<resultadoItem['cantidadMayoreo'])
                    // {
                    //   var importe =Math.round( r['ncantidad']* parseFloat(resultadoItem['precio']));
                    // }
                    // else{
                    //   var importe = Math.round( r['ncantidad']* parseFloat(resultadoItem['precioMayoreo']));
                    //   precio = resultadoItem['precioMayoreo'];
                    // }
                    importe = precioReal*r.ncantidad;
                    importeImprimible=Math.round(importe);
                    // agregamos una nueva fila con el codigo
                    $('#tbventa tr:last').after("<tr><td>"+resultadoItem['codigo']+"</td><td>"+resultadoItem['descripcion']+"</td><td>$ "+formatoMonedaChile(precioImprimible)+"</td><td>"+r['ncantidad']+"</td><td>$ "+formatoMonedaChile(importeImprimible)+"</td><td class='text-center'><button class='delFila btn btn-danger btn-xs'><i class='fa fa-times-circle' aria-hidden='true'></i></button></td><td class='hidden'>"+importe+"</td></tr>");
                  }
                  // recorremos la tabla para calcular el importe
                  var nuevoTotal=0;
                  $("#tbventa tr").find('td:eq(6)').each(function () {
                    nuevoTotal += parseFloat($(this).html());
                  });
                  nuevoTotal = nuevoTotal;
                  var totalImprimible=nuevoTotal;
                  totalImprimible=Math.round(totalImprimible);
                  //Actualizamos el total
                  $("#miTotalx").html(  totalImprimible  );
                  $("#miTotal").html(   formatoMonedaChile(totalImprimible)   );
                  if(impuesto=="1"){
                    var totalImpuesto = Math.round(nuevoTotal*impuestoPorciento/100);
                    var miTotal2 = Math.round(nuevoTotal+totalImpuesto);
                    $("#miImpuestox").html(   totalImpuesto   );
                    $("#miImpuesto").html(   formatoMonedaChile(totalImpuesto)  );
                    $("#miTotal2x").html(   miTotal2   );
                    debugger; //pp2
                    $("#miTotal2").html(   formatoMonedaChile(miTotal2)  );
                  }
                },
                error: function (data) {
                  alerta('Error interno x2', data['responseText'], '');
                }
              });
              location.reload();
              // WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
            },
            error: function (data) {
              alerta('Error interno x2', data['responseText'], '');
            }
          });
          // OOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
        }
        else{
          alerta("Mensaje","No hay registros que coincidan con el código ingresado.","");
          $("#codigo").val('');
        }
      },
      error: function (data) {
        alerta('Error interno x1', data['responseText'], '');
      }
    });
  }
}

function getMontoOferta(idVenta){
  $.ajax({
    url: base_url + 'pventa/verificarOferta',
    data: {idVenta: idVenta},
    type: 'POST',
    success: function (resultado) {
      alert(resultado);
    },
    error: function (data) {
      alerta('Error interno x1', data['responseText'], '');
    }
  });
}


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

$("body").on('click', '.cobrar', function () {
  var tipo = $(this).data("tipo");
  cobrar(tipo);
});

/* ********************************************************
DataTable
********************************************************* */
function callDatatableBuscar() {


  var table = $('#tbBuscar').DataTable( {

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
        first: "<i class='fa fa-fast-backward' aria-hidden='true'></i>",
        previous: "<i class='fa fa-backward' aria-hidden='true'></i>",
        next: "<i class='fa fa-forward' aria-hidden='true'></i>",
        last: "<i class='fa fa-fast-forward' aria-hidden='true'></i>"
      }
      //,
      //aria: {
      //    sortAscending: ": activate to sort column ascending",
      //    sortDescending: ": activate to sort column descending"
      //}
    },
    lengthMenu: [
      [ 5 ],
      [ '5' ]
    ],
    columnDefs:[
      { orderable: false, targets: [0] },{ orderable: false, targets: [3] }
    ],
    order:[
      [ 1, 'asc' ]
    ]
  } );

}






function formatoMonedaChile(numero){
  var numeroFormateado;
  $.ajax({
    async:false,
    url: base_url + 'principal/formatoMonedaChile',
    data: {numero:numero},
    type: 'POST',
    success: function (result) {
      numeroFormateado=result;
    }
  });
  return numeroFormateado;
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
