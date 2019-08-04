<link rel="stylesheet" href="<?php echo base_url() ?>plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
<!-- ******************************************************************************************************************* -->
<!-- ******************************************************************************************************************* -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div id="msj"></div>
    <h1>
      Control de Créditos
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">Lista de Créditos</h3>
            <!-- ****************************** -->
            <!-- <div class="pull-right box-tools">
            <button type="button" class="btn btn-primary btn-sm" id="btnVentasDia" data-toggle="tooltip" title="Ventas por día" data-original-title="Agregar nuevo usuario">
            <i class="fa fa-calendar-o fa-lg" aria-hidden="true"></i> Resumen diario
          </button>
        </div> -->
        <!-- ****************************** -->
      </div>
      <div class="box-body">
        <div class="col-lg-12 col-xs-12">
          <!-- ñññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññ           -->
          <div class="table-responsive">
            <!-- <table cellspacing="0" cellpadding="0" id="tbInventario" class="table table-striped table-bordered table-hover table-condensed"> -->
            <table id="tb_creditos" class="table table-striped table-bordered dt-responsive nowrap table-hover table-condensed" cellspacing="0" width="100%" style="background: white!important">
              <thead>
                <tr>
                  <th class="bg-primary text-center"><span>Id</span></th>
                  <th class="bg-primary"><span>Cliente</span></th>
                  <th class="bg-primary text-center"><span>Fecha</span></th>
                  <th class="bg-primary text-center"><span>Plazo</span></th>
                  <th class="bg-primary text-center"><span>Deuda Total</span></th>
                  <th class="bg-primary text-center"><span>Abonado</span></th>
                  <th class="bg-primary text-center"><span>Diferencia</span></th>
                  <th class="bg-primary text-center"><span>Opciones</span></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $totalEnCreditos = 0;
                $totalAbonado = 0;
                foreach ($creditos as $credito) {
                  $totalEnCreditos += $credito['Total'];
                  $totalAbonado += $credito['abonos'];
                  $unidad = $credito['unidad'];
                  $diferencia = $credito['Total']-$credito['abonos'];
                  if($diferencia<1){ continue; }
                  if($credito['plazoNum']>1){
                    $unidad = $credito['nombre'];
                  }
                  ?>
                  <tr>
                    <td><?=$credito['idCredito']?></td>
                    <td><?=$credito['cliente']?></td>
                    <td class="text-center"><?=date('d/m/Y H:i:s', strtotime($credito['Fecha']))?></td>
                    <td><?=$credito['plazoNum']." ".$unidad?> </td>
                    <td class="text-right"><?=$monedaString?> <?=number_format($credito['Total'],2)?></td>
                    <td class="text-right"><?=$monedaString?> <?=number_format($credito['abonos'],2)?></td>
                    <td class="text-right"><?=$monedaString?> <?=number_format($diferencia,2)?></td>
                    <td class="text-center">
                      <button class="btn btn-xs btn-info detalle" data-id="<?=$credito['idVenta']?>"><i class="fa fa-eye"></i></button>
                      <button class="btn btn-xs btn-success abonar" data-id="<?=$credito['idVenta']?>">Abonar</button>
                    </td>
                  </tr>
                  <?php
                }
                ?>
              </tbody>
            </table>
            <h3 class="text-right"><b>Total en créditos: <?=$monedaString?> <?=number_format($totalEnCreditos,2)?><b></h3>
              <h3 class="text-right"><b>Total pendiente: <?=$monedaString?> <?=number_format($totalEnCreditos-$totalAbonado,2)?><b></h3>
              </div>
              <!-- ñññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññ           -->
            </div>
          </div>
        </div>
        <!-- ========================================================================================================================= -->

        <!-- Your Page Content Here -->
      </section>
      <!-- /.content -->
    </div>
  </div>
  <!-- /.content-wrapper -->

  <!-- ******************************************************************************************************************* -->
  <!-- ******************************************************************************************************************* -->
