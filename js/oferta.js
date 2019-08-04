/* Form Nueva Oferta*/

$('body').on('submit', '#formNewOffer', function () {
    $.ajax({
      url: base_url + 'nuevaoferta/addNewItem',
      data: $(this).serialize(),
      type: 'POST',

      success: function (resultado) {
        if(resultado == "1"){
          var msj = miAlerta("success","Oferta Agregado", "La Oferta ha sido agregado correctamente");
          $("#msj").html(msj);
          $("#descripcion").val("");
          $("#precio").val("");
          $('.multi-select-buscador').multiSelect('deselect_all');
          esconderAlerta();
        }
        return false;
      },
      error: function (data) {
        alerta('Error interno', data['responseText'], '');
      }
    });
    return false;
  });

$('#productos').multiSelect({
  selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Buscar para agregar...'>",
  selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Buscar agregado...'>",
  afterInit: function(ms){
    var that = this,
        $selectableSearch = that.$selectableUl.prev(),
        $selectionSearch = that.$selectionUl.prev(),
        selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
        selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
    .on('keydown', function(e){
      if (e.which === 40){
        that.$selectableUl.focus();
        return false;
      }
    });

    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
    .on('keydown', function(e){
      if (e.which == 40){
        that.$selectionUl.focus();
        return false;
      }
    });
  },
  afterSelect: function(value){
    this.qs1.cache();
    this.qs2.cache();
  },
  afterDeselect: function(value){
    this.qs1.cache();
    this.qs2.cache();

  }
});
