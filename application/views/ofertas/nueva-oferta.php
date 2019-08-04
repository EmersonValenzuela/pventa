<!-- ******************************************************************************************************************* -->
<!-- ******************************************************************************************************************* -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Ofertas
      <small>Nueva Oferta</small>
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
          <h3 class="box-title">Agregar Nueva Oferta</h3>
        </div>
        <div class="box-body">
          <form id="formNewOffer" autocomplete="off">

            <div class="clearfix"></div><br>

            <div class="col-md-3 col-lg-2 col-xs-12">
              <label for="codigo">Descripción de la oferta:</label>
            </div>
            <div class="col-md-9 col-lg-9 col-xs-12">
              <input type="text" class="form-control" placeholder="Descripción de la producto" maxlength="100" name="descripcion" id="descripcion" required="required">
            </div>

            <div class="clearfix"></div><br>

            <div class="col-md-3 col-lg-2 col-xs-12">
              <label for="codigo">Precio:</label>
            </div>
            <div class="col-md-2 col-lg-2 col-xs-12">
              <div class="input-group">
                <span class="input-group-addon"><b><?=$monedaString?></b></span>
                <input type="text" class="form-control" placeholder="0.00" maxlength="13" name="precio" id="precio" required="required">
              </div>
            </div>
            <div class="clearfix"></div><br>

            <div class="col-md-3 col-lg-2 col-xs-12">
              <label for="codigo">Productos de la oferta:</label>
            </div>
            <div class="col-md-2 col-lg-2 col-xs-12">
              <select multiple="multiple" class="multi-select-buscador" id="productos" name="productos[]">
                <?php
                  foreach ($inventario as $item) {
                    echo "<option value='".$item['id']."'>".$item['descripcion']."</option>";
                  }
                ?>
              </select>
            </div>
            <div class="clearfix"></div><br>
            <div class="col-md-7 col-lg-7 col-xs-12">
              <button type="submit" class="btn btn-block btn-primary btn-lg">Agregar Nueva Oferta</button>
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
