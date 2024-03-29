<!-- ******************************************************************************************************************* -->
<!-- ******************************************************************************************************************* -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper ">

  <!-- Main content -->
  <section class="content ">

    <section class="content ">
      <div class="row">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">Venta Temporal </h3><small class="pull-right" id="idVenta">Id Venta: <span id="idVentax"><?=$idVenta?></span></small>
          </div>
          <div class="box-body" id="general">
            <!--<div id="divbtnnv">
            <center>
            <button type="button" class="btn btn-warning" id="btnNV"><i class="fa fa-cart-plus" aria-hidden="true"></i> Nueva venta</button>
          </center>
        </div>-->
        <div id="miVenta">
          <div class="col-lg-12 col-xs-12">
            <!-- AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA    -->

            <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
              <form id="formventaCodigo" autocomplete="off">
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                  <label for="codigo">Cliente:</label>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user-circle fa-lg" aria-hidden="true"></i></span>
                    <select name="cliente" class="form-control input-sm select2" id="cliente">
                      <option value="x" selected>*** Agregar nuevo... ***</option>
                      <?php
                      foreach ($clientes as $cliente) {
                        $sld="";
                        if($cliente["id"]==$clienteVenta){
                          $sld="selected";
                        }
                        ?>
                        <option value="<?=$cliente["id"]?>" <?=$sld?>><?=$cliente["nombre"]?></option>
                        <?php
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <!-- ................... -->
                <div class="clearfix"></div><br>
                <!-- ................... -->
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                  <label for="codigo">Cantidad:</label>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-times fa-lg" aria-hidden="true"></i></span>
                    <input type="text" class="form-control input-sm" placeholder="1" maxlength="10" value="1" name="cantidad" id="cantidad">
                  </div>
                </div>
                <!-- ................... -->
                <div class="clearfix"></div><br>
                <!-- ................... -->
                <div class="col-md-6 col-lg-6 col-sm-12  col-xs-6">
                  <label for="codigo">Código del producto:</label>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-barcode" aria-hidden="true"></i></span>
                    <input type="text" class="form-control  input-sm" placeholder="Código" autofocus="" maxlength="13" name="codigo" id="codigo">
                  </div>
                  <br/>
                </div>
              </form>
            </div>

            <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
              <div class="total pull-right text-right">Subtotal: <?=$monedaString?> <span id="miTotal"><?=number_format($total,2)?></span><span id="miTotalx" class="hidden"><?=$total?></span>
                <br/>
                Descuento Oferta: <?=$monedaString?> <span id="miDescuento"><?=number_format($oferta,2)?></span>
                <br>
                <?php  $impuestoResult = 0 ?>
                <div class="<?=$impuesto_si?>">
                  <?php $impuestoResult = round($total*($configs->impuestoPorciento)*0.01); ?>
                  <?=$configs->nombreImpuesto?> (<?=$configs->impuestoPorciento?>%): <?=$monedaString?> <span id="miImpuesto"><?=$impuestoResult?></span><span id="miImpuestox" class="hidden"><?=$impuestoResult?></span>
                  <br/>
                </div>
                <?php
                if($configs->impuesto==0){
                  $impuestoResult=0;
                }
                $total = $total - $oferta + $impuestoResult; ?>
                <b>Total: <?=$monedaString?> <span id="miTotal2"><?=number_format(round($total),2)?></span></b><span id="miTotal2x" class="hidden"><?=round($total)?></span>
                <br/>
                <br/>
              </div>
            </div>

            <button type="button"  data-tipo="0" class="btn btn-sm btn-success pull-right alert cobrar"><i class="fa fa-money fa-lg" aria-hidden="true"></i> Cobrar por Efectivo [F6]</button>
               <div class="clearfix"></div>
            <button type="button"  data-tipo="1" class="btn btn-sm btn-warning pull-right alert cobrar"><i class="fa fa-credit-card fa-lg" aria-hidden="true"></i>&nbsp;Cobrar Por Tarjeta&nbsp;&nbsp; [F7]</button>
               <div class="clearfix"></div>
            <button type="button"  data-tipo="2" class="btn btn-sm btn-info pull-right alert cobrar"><i class="fa fa-puzzle-piece fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pago a Crédito&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [F8]</button>

            <!-- AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA    -->
            <br><br>
            <!-- BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB -->
            <table id="tbventa" class="table table-striped table-bordered dt-responsive nowrap table-hover table-condensed" cellspacing="0" width="100%" style="background: white!important">
              <thead>
                <tr>
                  <th class="bg-primary"><span>Codigo</span></th>
                  <th class="bg-primary"><span>Descripcion</span></th>
                  <th class="bg-primary"><span>Costo Unitario</span></th>
                  <th class="bg-primary"><span>Cantidad</span></th>
                  <th class="bg-primary"><span>Importe</span></th>
                  <th class="bg-primary"><span>Descartar</span></th>
                  <th class="hidden"><span>importex</span></th>
                </tr>
              </thead>
              <tbody>
                <?=$tabla?>
              </tbody>
            </table>
            <!-- BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB -->

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</section>
</div>

<script type="text/javascript">
var impuesto = "<?=$configs->impuesto?>";
if(impuesto){
  var impuestoPorciento="<?=$configs->impuestoPorciento?>";
}
var monedaString = "<?=$monedaString?>";
</script>
