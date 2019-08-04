<!-- ******************************************************************************************************************* -->
<!-- ******************************************************************************************************************* -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Ofertas
      <small>Modificar Oferta</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
  <div id="msj"></div>
  <!-- ========================================================================================================================= -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">Modificar Oferta</h3>
        </div>
        <div class="box-body">
          <form id="formUpdateOffer" autocomplete="off">

            <input type="hidden" name="id" value="<?=$oferta->id?>">

            <div class="clearfix"></div><br>

            <div class="col-md-3 col-lg-2 col-xs-12">
              <label for="codigo">Descripción de la oferta:</label>
            </div>
            <div class="col-md-9 col-lg-9 col-xs-12">
              <input type="text" class="form-control" placeholder="Descripción de la oferta" value="<?=$oferta->descripcion?>" maxlength="100" name="descripcion" id="descripcion" required="required">
            </div>

            <div class="clearfix"></div><br>

            <div class="col-md-3 col-lg-2 col-xs-12">
              <label for="codigo">Precio:</label>
            </div>
            <div class="col-md-2 col-lg-2 col-xs-12">
              <div class="input-group">
                <span class="input-group-addon"><b><?=$monedaString?></b></span>
                <input type="text" class="form-control" placeholder="0.00" maxlength="13" value="<?=$oferta->precio?>" name="precio" id="precio" required="required">
              </div>
            </div>
            <div class="clearfix"></div><br>
            <div class="col-md-3 col-lg-2 col-xs-12">
              <label for="codigo">Estatus:</label>
            </div>
            <label class="radio-inline"><input type="radio" name="estatus" value="1" <?=$oferta->estatus?'checked':''?> >Activo</label>
            <label class="radio-inline"><input type="radio" name="estatus" value="0" <?=$oferta->estatus?'':'checked'?> >Inactivo</label>
            <div class="clearfix"></div><br>


            <div class="col-md-3 col-lg-2 col-xs-12">
              <label for="codigo">Productos de la oferta:</label>
            </div>
            <div class="col-md-2 col-lg-2 col-xs-12">
              <select multiple="multiple" class="multi-select-buscador" id="productos" name="productos[]">
                <?php
                  foreach ($inventario as $item) {

                    $selected = '';

                    foreach ($inventario_oferta as $io ) {
                       if($io['id_inventario'] == $item['id']){
                          $selected = 'selected';
                          break;
                       }
                    }
                      echo "<option value='".$item['id']."' ".$selected.">".$item['descripcion']."</option>";
                  }
                ?>
              </select>
            </div>
            <div class="clearfix"></div><br>
            <div class="col-md-7 col-lg-7 col-xs-12">
              <button type="submit" class="btn btn-block btn-warning btn-lg">Modificar Oferta</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- ========================================================================================================================= -->

  <!-- Your Page Content Here -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- ******************************************************************************************************************* -->
<!-- ******************************************************************************************************************* -->
