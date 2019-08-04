$(document).ready(function(){

  //$('#upImagen').on('click', function(){
    //$('#getImagen').click();
    //return false
  //});
/*
    $('#getImagen').on('change', function(){
    $('#upImagen').removeClass("btn-light");
    $('#upImagen').addClass("btn-primary");
    $('#ico-btn-file').removeClass("fa-upload");
    $('#ico-btn-file').addClass("fa-check");
    return false
  });
*/
  $('body').on('submit','#formNewItem' function(){
    var formData = new formData($("#formNewItem").get(0));
    contentType = false,
    processData = false,
    $.ajax({
      url: base_url + 'nuevoProducto/addNewItem',
      data: formData,
      type: 'POST',
      contentType = false,
      processData = false,
      success: function(resultado){
        location.reload();
      }
    });
    return false;
  });

});
