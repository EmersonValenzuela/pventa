<center>
  <h2 style="margin:0;padding:0;">Nuevo Crédito</h2>
  <h4>Cliente: <?=$cliente?></h4>
  <h4>Total: <?=$monedaString?><?=number_format($total,2)?></h4>
  <form class="form form-inline" action="" method="post" id="formnewcredito" autocomplete="off">
    <input type="hidden" name="idVenta" value="<?=$idVenta?>">
    <input type="hidden" name="total" value="<?=$total?>">
    <input type="hidden" name="impuesto" value="<?=$impuesto?>">
    <input type="hidden" name="descuento" value="<?=$descuento?>">
    <input type="hidden" name="tipo" value="<?=$tipo?>">
    <?php
    if($impuesto)
    {
      ?>
      <input type="hidden" name="impuestoPorciento" value="<?=$impuestoPorciento?>">
      <input type="hidden" name="nombreImpuesto" value="<?=$nombreImpuesto?>">
      <?php
    }
    ?>


    <div class="form-group">
      <div class="row">
        <nobr>
          <label>Plazo</label>
          <div class="input-group">
            <input type="number" name="numeroplazo" class="form-control" style="width:70px" autofocus id="numeroplazo" maxlength="5" required="required" placeholder="1"/>
          </div>
          <!-- == -->
          <select name="idPlazoTipo" class="form-control" style="width:150px">
            <?php
            foreach ($plazoTipo as $pt)
            {
              ?>
              <option value="<?=$pt['idPlazoTipo']?>"><?=$pt['nombre']?></option>';
              <?php
            }
            ?>
          </select>
        </div>
      </nobr>
    </div>

    <br/>
    <br/>

    <div class="form-group">
      <label for="recibido">Pago inicial:</label>
      <div class="input-group">
        <div class="input-group-addon"><b><?=$monedaString?></b></div>
        <input type="text" class="form-control" id="recibido"  name="recibido" maxlength="7" required="required" placeholder="<?=number_format($total,2)?>">
      </div>
    </div>

    <br/>
    <br/>

    <div class="form-group">
      <label for="recibido">Tipo de Pago:</label>
      <div class="input-group">
        <select name="idTipoPago" class="form-control" style="width:180px">
          <option value="0">Efectivo</option>';
          <option value="1">Targeta crédito/débito</option>';
        </select>
      </div>
    </div>

    <br>
    <br>
    <button type="submit" class="btn btn-success btn-block">Continuar</button>
  </form>
</center>
