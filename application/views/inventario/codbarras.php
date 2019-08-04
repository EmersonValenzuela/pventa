<!-- ******************************************************************************************************************* -->
<!-- ******************************************************************************************************************* -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Código
      <small>de barras  <small>EAN13</small></small>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div id="msj"></div>
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">Generar codigo de barras</h3>
          </div>
          <div class="box-body">
            <div class="col-lg-12 col-xs-12">
              <!-- ñññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññ           -->
              <form id="formBarCode" autocomplete="off">
                <div class="col-md-3 col-lg-2 col-xs-12">
                  <label for="codigo">Código de producto:</label>
                </div>

                <div class="col-md-4 col-lg-4 col-xs-12">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-barcode" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Código" maxlength="13" name="makecodigo" id="makecodigo" autofocus="" required="required">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-primary  makecodebar">Dibujar</button>
                    </span>
                  </div>
                </div>
              </form>
              <br>
              <div class="clearfix"></div><br>
              <div class="row">
                <div class="col-md-4 col-lg-4 col-xs-12">
                  <div id="barcodeimg">
                  </div>
                </div>
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
    <!-- /.content-wrapper -->

    <!-- ******************************************************************************************************************* -->
    <!-- ******************************************************************************************************************* -->
