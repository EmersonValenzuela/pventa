<link rel="stylesheet" href="<?php echo base_url() ?>plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
<!-- ******************************************************************************************************************* -->
<!-- ******************************************************************************************************************* -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div id="msj"></div>
    <h1>
      Registro de Compras
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
          <div class="row">
            <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3">
              <form class='form' id='formFechasCompras' action="<?=base_url('vercompras/resultado')?>" method="post" target="_parent" autocomplete='off'>
                <div class="row">
                  <div class='col-md-6'>
                    <div class="form-group">
                      <label for="">Fecha Inicial</label>
                      <div class='input-group date' id='from'>
                        <input type='text' name="from" class="form-control" readonly required="required"/>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                      </div>
                    </div>
                  </div>
                  <div class='col-md-6'>
                    <div class="form-group">
                      <label for="">Fecha Final</label>
                      <div class='input-group date' id='to'>
                        <input type='text' name="to" class="form-control" readonly required="required"/>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                      </div>
                    </div>
                  </div>
                  <div class='col-md-12'>
                    <button type='submit' class='btn btn-success btn-block'>Filtrar Fechas</button>
                  </div>
                </div>
              </form>
              <p></p>
            </div>
          </div>
          <!-- ñññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññ           -->
          <div class="table-responsive">
            <!-- <table cellspacing="0" cellpadding="0" id="tbInventario" class="table table-striped table-bordered table-hover table-condensed"> -->
            <table id="tb_ventas" class="table table-striped table-bordered dt-responsive nowrap table-hover table-condensed" cellspacing="0" width="100%" style="background: white!important">
              <thead>
                <tr>
                  <th class="bg-primary"><span>Folio</span></th>
                  <th class="bg-primary"><span>Proveedor</span></th>
                  <th class="bg-primary text-center"><span>Total</span></th>
                  <th class="bg-primary text-center"><span>Tipo Pago</span></th>
                  <th class="bg-primary text-center"><span>Fecha</span></th>
                  <th class="bg-primary text-center"><span>Detalle</span></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $totalvs=0;
                $totalefectivo=0;
                $totaltarjeta=0;
                foreach ($ventas as $venta) {

                  if($venta['tipoPago']==0){
                    $tipoPago="<span class=\"label label-success\">&nbsp;&nbsp;EFECTIVO&nbsp;&nbsp;</span>"; //"Salida";
                  }
                  else{
                    $tipoPago="<span class=\"label label-warning\">TARJETA</span>"; //"Entrada";
                  }
                  ?>
                  <tr data-id="<?=$venta['id']?>">
                    <td><?=$venta['folio_factura']?></td>
                    <td><?=$venta['proveedor']?></td>
                    <td class="text-right"><?=$monedaString?> <?=number_format($venta['Total'],2)?></td>
                    <td class="text-right"><?=$tipoPago?></td>
                    <td class="text-center"><?=date('d/m/Y H:i:s', strtotime($venta['Fecha']))?></td>
                    <td class="text-center"><button class="btn btn-xs btn-info detalle" data-id="<?=$venta['id']?>">Ver Detalle</button></td>
                  </tr>
                  <?php
                  if($venta['tipoPago'] == 1)
                  {
                    $totaltarjeta+=$venta['Total'];
                  }
                  else
                  {
                    $totalefectivo+=$venta['Total'];
                  }
                  $totalvs+=$venta['Total'];
                }
                ?>
              </tbody>
            </table>
                <h3 class="text-right"><b>Suma Total: <?=$monedaString?> <?=number_format($totalvs,2)?><b></h3>
                </div>
                <!-- ñññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññ           -->
              </div>
            </div>
          </div>
          <!-- ========================================================================================================================= -->




          <!-- ñññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññ           -->
          <!-- ========================================================================================================================= -->

          <!-- Your Page Content Here -->
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <!-- ******************************************************************************************************************* -->
      <!-- ******************************************************************************************************************* -->
