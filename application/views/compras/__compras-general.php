<!-- ******************************************************************************************************************* -->
<!-- ******************************************************************************************************************* -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Compras
      <small>Vista General</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="box box-success">
        <div class="box-header">
          <h3 class="box-title">Lista de Compras</h3>
        </div>
        <div class="box-body">
          <div class="col-lg-12 col-xs-12">
            <div class="table-responsive">
              <table id="tbCompras" class="table table-striped table-bordered dt-responsive table-hover table-condensed" cellspacing="0" width="100%" style="background: white!important">
                <thead>
                  <tr>
                    <th class="bg-primary"><span>id</span></th>
                    <th class="bg-primary"><span>Folio Factura</span></th>
                    <th class="bg-primary"><span>Costo</span></th>
                    <th class="bg-primary"><span>Proveedor</span></th>
                    <th class="bg-primary"><span>Teléfono</span></th>
                    <th class="bg-primary"><span>RFC</span></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($compras as $item) {
                    ?>
                    <tr data-id="<?=$item['id']?>">
                      <td><?=$item['id']?></td>
                      <td><?=$item['folio_factura']?></td>
                      <td><?=$item['costo']?></td>
                      <td><?=$item['nombre']?></td>
                      <td><?=$item['telefono']?></td>
                      <td><?=$item['rfc']?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- Your Page Content Here -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- ******************************************************************************************************************* -->
    <!-- ******************************************************************************************************************* -->