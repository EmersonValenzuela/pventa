<center>
  <h4>Cliente: <?=$venta['cliente']?></h4>
  <h4>Total: <?=$monedaString?><?=number_format($venta['Total'],2)?></h4>
  <form class="form form-inline" action="" method="post" id="formnewabono" autocomplete="off">
    <input type="hidden" name="idVenta" value="<?=$venta['id']?>">

    <div class="form-group">
      <label for="recibido">Abono:</label>
      <div class="input-group">
        <div class="input-group-addon"><b><?=$monedaString?></b></div>
        <input type="text" class="form-control" id="recibido"  name="recibido" maxlength="7" required="required" placeholder="<?=number_format($venta['Total'],2)?>">
      </div>
    </div>

    <br/>
    <br/>

    <div class="form-group">
      <label for="recibido">Tipo de Pago:</label>
      <div class="input-group">
        <select name="idTipoPago" class="form-control" style="width:180px">
          <option value="0">Efectivo</option>';
          <option value="1">Tarjeta crédito/débito</option>';
        </select>
      </div>
    </div>

    <br>
    <br>
    <button type="submit" class="btn btn-success btn-block">Guardar</button>
  </form>
</center>
