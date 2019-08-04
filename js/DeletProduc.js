$(document).ready(function () {


  $('body').on('click','.btnDeletP',function () {
    var  idp=$(this).data("idp");
    $(".modal-header").html('<span class="text-right" style="font-size:17px"><i class="fa fa-cubes" aria-hidden="true"></i> Eliminar Producto</span><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><font color="#FF0000"><i class="fa fa-times" aria-hidden="true"></i></font></span></button><br>');
    // $(".modal-body").html("Estas Seguro");
    // $(".modal-footer").html("");
    // $("#ModalMini").modal("show");
    alertaMini("Confirmación","¿Estás seguro de eliminar este producto?","<button type='button' class='btn btn-default' data-dismiss='modal'>Cancelar</button> <a href='#' id='confirmarBorrar' data-id='"+idp+"' class='btn btn-danger'>Si, aceptar</a>")
    return false;
  });

  $('body').on('click', '#confirmarBorrar', function () {
    var idp=$(this).data("id");
    $.ajax({
      url: base_url + 'inventario/delProduct',
      data: {idp:idp},
      type: 'POST',
      success: function (resultado) {
        location.reload();
      },
      error: function (data) {
        alerta('Error interno', data['responseText'], '');
      }
    });
    return false;
  });

});//fin function ready
