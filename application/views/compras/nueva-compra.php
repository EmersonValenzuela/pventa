<!-- ******************************************************************************************************************* -->
<!-- ******************************************************************************************************************* -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Compras
      <small>Nueva Compra</small>
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
          <h3 class="box-title">Agregar Nueva Compra</h3>
        </div>
        <div class="box-body">
          <form id="formNuevaCompra" autocomplete="off">

            <div class="clearfix"></div><br>

            <div class="col-md-3 col-lg-2 col-xs-12">
              <label for="codigo">Folio de la factura:</label>
            </div>
            <div class="col-md-4 col-lg-5 col-xs-12">
              <input type="text" class="form-control" placeholder="Folio de la Captura" maxlength="100" name="folio" id="folio" required="required">
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
              <label for="codigo">Proveedor:</label>
            </div>
            <div class="col-md-5 col-lg-5 col-xs-12">
              <select class="form-control" name="proveedor">
                <?php
                foreach ($proveedores as $proveedor) {
                  ?>
                  <option value="<?=$proveedor['id']?>"><?=strtoupper($proveedor['nombre'])?></option>
                  <?php
                }
                ?>
              </select>
            </div>
            <div class="clearfix"></div><br>
            <div class="col-md-3 col-lg-2 col-xs-12">
              <label for="codigo">Productos a comprar:</label>
            </div>
            <div class="col-md-2 col-lg-2 col-xs-12">
              <select multiple="multiple" id="productos" name="productos[]">
                <?php
                  foreach ($inventario as $item) {
                    echo "<option value='".$item['id']."'>".$item['descripcion']."</option>";
                  }
                ?>
              </select>
            </div>
            <div class="clearfix"></div><br>

            <div id="dinamic-form"></div>

            <div class="clearfix"></div><br>
            <div class="col-md-7 col-lg-7 col-xs-12">
              <button type="submit" class="btn btn-block btn-primary btn-lg">Agregar Nueva Compra</button>
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
