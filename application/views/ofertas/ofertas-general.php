<!-- ******************************************************************************************************************* -->
<!-- ******************************************************************************************************************* -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Ofertas
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
          <h3 class="box-title">Lista de Ofertas</h3>
        </div>
        <div class="box-body">
          <div class="col-lg-12 col-xs-12">
            <div class="table-responsive">
              <table id="tbOfertas" class="table table-striped table-bordered dt-responsive table-hover table-condensed" cellspacing="0" width="100%" style="background: white!important">
                <thead>
                  <tr>
                    <th class="bg-primary"><span>id</span></th>
                    <th class="bg-primary"><span>Descripción</span></th>
                    <th class="bg-primary"><span>Precio</span></th>
                    <th class="bg-primary"><span>Estatus</span></th>
                    <th class="bg-primary"><span>Acciones</span></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($ofertas as $item) {
                    ?>
                    <tr data-id="<?=$item['id']?>">
                      <td><?=$item['id']?></td>
                      <td><?=$item['descripcion']?></td>
                      <td class="text-center"><nobr><?=$monedaString?> <?=number_format($item['precio'],2)?></td>
                      <td class="text-center"><span class="label label-<?=$item['estatus']?'success':'danger' ?>"><?=$item['estatus']?'Activo':'Inactivo' ?></span></td>
                      <td><a class="btn btn-warning btn-xs" href="<?=base_url('ofertas/editar/'.$item['id'])?>">Editar</a></td>
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
