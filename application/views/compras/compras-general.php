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
            <h3 class="box-title">Compra </h3>
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
                  <form id="formcompraCodigo" autocomplete="off">
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
                      <div class="col-md-6 col-lg-6 col-sm-12  col-xs-6">
                      <label for="folio">Folio Factura:</label>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                        <input type="text" class="form-control  input-sm" placeholder="Folio Factura" autofocus="" maxlength="13" name="folio" id="folio">
                    <br>
                    </div>

                     <div class="col-md-6 col-lg-6 col-sm-12  col-xs-6">
                      <label for="folio">Proveedor:</label>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                      <select class="form-control" name="proveedor" id="proveedor">
                <?php
                foreach ($proveedores as $proveedor) {
                  ?>
                  <option value="<?=$proveedor['id']?>"><?=strtoupper($proveedor['nombre'])?></option>
                  <?php
                }
                ?>
              </select>
                      <br/>
                    </div>
                  </form>
                </div>

                <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
                  <div class="total pull-right text-right">Subtotal: <?=$monedaString?> <span id="miTotal">0.00</span><span id="miTotalx" class="hidden">0.00</span>
                    <br>
                    <div class="<?=$impuesto_si?>">
                      <?=$configs->nombreImpuesto?> (<?=$configs->impuestoPorciento?>%): <?=$monedaString?> <span id="miImpuesto">0.00</span><span id="miImpuestox" class="hidden">0.00</span>
                      <br/>
                      <b>Total: <?=$monedaString?> <span id="miTotal2">0.00</span></b><span id="miTotal2x" class="hidden">0.00</span>
                      <br/>
                    </div>
                    <br/>
                  </div>
                </div>

                <button type="button"  data-tipo="0" class="btn btn-lg btn-success pull-right alert" onclick="guardarCompra()">Guardar</button>
                <!--id="cobrar" id="cobrart" AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA    -->
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
                    <tr></tr>
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
