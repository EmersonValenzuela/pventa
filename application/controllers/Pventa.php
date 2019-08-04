<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pventa extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $idUser = $this->session->userdata('idUser');
    $rol= $this->session->userdata('rol');
    $user=$this->consultas->getUsers($idUser);
    $data = array();
    $dataHeader['titulo']="Punto de Venta";
    $tema = $this->consultas->configTema();
    $dataSidebar['tema']="$tema";
    $dataSidebar['classInventario']="";
    $dataSidebar['classVentas']="";
    $dataSidebar['classUsuarios']="";
    $dataSidebar['classMovimientos']="";
    $dataSidebar['classCreditos']="";

    $dataSidebar['classInventarioGeneral']="";
    $dataSidebar['classInventarioModificar']="";
    $dataSidebar['classInventarioAgregar']="";
    $dataSidebar['classInventarioNuevo']="";
    $dataSidebar['classInventarioCBarras']="";
    $dataSidebar['classConfiguraciones']="";
    $dataSidebar['classProveedores']="";
    $dataSidebar['classClientes']="";
    $dataSidebar['usuario']=$user;

    $data['configs']=$this->consultas->getConfigs();

    $data['impuesto_si']="hidden";
    if ($data['configs']->impuesto==1) {
      $data['impuesto_si']="";
    }
    $data['monedaString']=$this->consultas->getMonedaString();
    $data['clientes']=$this->consultas->getClientes();
    $data['idVenta'] = $this->getIdVenta();

    $dataSidebar['impresoras']=$this->consultas->getTabla("tb_impresoras");
    $dataSidebar['impresoraUsuario']=$this->consultas->getImpresoraUsuario($idUser);

    $this->load->view('header',$dataHeader);
    $this->load->view('sidebar',$dataSidebar);
    $this->load->view('main', $data);
    $this->load->view('main-footer');
    $dataFooter=array(
      'scripts'=> "<script src='".base_url()."js/pventa.js'></script>"
    );
    $this->load->view('footer',$dataFooter);
  }

  private function getIdVenta()
  {
    $idUser = $this->session->userdata('idUser');
    // // obtener el ultimo id de ventas del mismo usuario
    $maxIdVentas = $this->consultas->getMaxIdVentasByUser($idUser);

    /*print_r($maxIdVentas);
    die;*/

    $dataVenta = array(
      'idUsuario' => $idUser,
    );

    if(!$this->consultas->isTempVenta($maxIdVentas['id']))
    {

    if($maxIdVentas!=0)
    {
      if($maxIdVentas['Total']<=0)
      {
        $this->delete->delMovimientosFallidos($maxIdVentas['id']);
      }
      if($maxIdVentas['Total']<=0)
      {
        return $maxIdVentas['id'];
      }
      else{
        $idVenta = $this->insertar->newVenta($dataVenta);
        return $idVenta;
      }
    }
      // asignar nueva venta a este usuario
    $idVenta = $this->insertar->newVenta($dataVenta);

    }
    else
    {
      $idVenta = $this->insertar->newVenta($dataVenta);
    }
    return $idVenta;
  }

  public function getItem()
  {
    $codigo = $this->input->post('codigo');
    $item = $this->consultas->getInventario($codigo);
    echo json_encode($item);
  }

  public function relacionarImpresora()
  {
    $idImpresora = $this->input->post('idImpresora');
    $idVendedor = $this->session->userdata('idUser');
    $this->delete->eliminarGral("tb_usuario_impresora",array('idUsuario' => $idVendedor ));
    $this->insertar->insertarGral("tb_usuario_impresora",array('idUsuario' => $idVendedor,'idImpresora'=>$idImpresora ));
  }

  public function Importe()
  {
    $codigo = $this->input->post('codigo');
    $cantidad = $this->input->post('cantidad');

    $item = $this->consultas->getInventario($codigo);
    if($cantidad<$item['cantidadMayoreo'])
    {
      $total = $cantidad * $item['precio'];
    }
    else {
      $total = $cantidad * $item['precioMayoreo'];
    }
    echo $total;
  }

  public function NuevaVenta()
  {
    $idUser = $this->session->userdata('idUser');
    // // obtener el ultimo id de ventas del mismo usuario
    $maxIdVentas = $this->consultas->getMaxIdVentasByUser($idUser);
    $dataVenta = array(
      'idUsuario' => $idUser,
    );
    if($maxIdVentas!=0)
    {
      if($maxIdVentas['Total']<=0)
      {
        $this->delete->delMovimientosFallidos($maxIdVentas['id']);
      }
      if($maxIdVentas['Total']<=0)
      {
        echo $maxIdVentas['id'];
        die();
      }
      else{
        $idVenta = $this->insertar->newVenta($dataVenta);
        echo $idVenta;
        die();
      }
    }

    // asignar nueva venta a este usuario
    $idVenta = $this->insertar->newVenta($dataVenta);
    echo $idVenta;
  }

  public function delItemToVenta()
  {
    $idVenta=$this->input->post('idVenta');
    $codigoItem=$this->input->post('codigo');
    $item = $this->consultas->getInventario($codigoItem);
    $this->delete->delMovimiento($idVenta,$item['id']);
    echo "1";
  }

  public function v_recibido()
  {
    $total=$this->input->post('total');
    $idVenta=$this->input->post('idVenta');
    $impuesto=$this->input->post('impuesto');
    $tipo=$this->input->post('tipo');
    $descuento=$this->input->post('descuento');
    $data = array(
      'total'=>$total,
      'idVenta'=>$idVenta,
      'impuesto'=>$impuesto,
      'descuento'=>$descuento,
      'tipo' =>$tipo
    );
    if($impuesto){
      $configs=$this->consultas->getConfigs();
      $data['impuestoPorciento']=$configs->impuestoPorciento;
      $data['nombreImpuesto']=$configs->nombreImpuesto;
    }
    $data['monedaString']=$this->consultas->getMonedaString();
    if($tipo==0)
    {
      $this->load->view('_cobrar',$data);
    }
    elseif($tipo==1)
    {
      $this->load->view('_cobrar2',$data);
    }
    elseif($tipo==2)
    {
      $data['cliente'] = $this->consultas->getClienteByIdVenta($idVenta);
      $data['plazoTipo'] = $this->consultas->getTabla("tb_plazo_tipo");
      $this->load->view('_nuevoCredito',$data);
    }
  }

  public function newCredito()
  {
    $total=$this->input->post('total');
    $idVenta=$this->input->post('idVenta');
    $abono=$this->input->post('recibido');
    $numeroplazo=$this->input->post('numeroplazo');
    $idPlazoTipo=$this->input->post('idPlazoTipo');
    $idTipoPago=$this->input->post('idTipoPago');
    // --
    // ingresa los valores a tb_creditos
    $datosNewCredito = array(
      'idVenta' => $idVenta,
      'plazoNum' => $numeroplazo,
      'plazoTipo' => $idPlazoTipo
    );
    $this->insertar->insertarGral("tb_creditos",$datosNewCredito);

    if($abono>0){
      // ingresa los valores a tb_abonos
      $dataNewAbono = array(
        'idVenta' => $idVenta,
        'idUsuario' => $this->session->userdata('idUser'),
        'fecha' => date("Y-m-d H:i:s"),
        'monto' => $abono,
        'tipoPago' => $idTipoPago
      );
      $this->insertar->insertarGral("tb_abonos",$dataNewAbono);
    }
    // // serializa resultado
  }

  public function concretarVenta()
  {
    $total=$this->input->post('total');
    $idVenta=$this->input->post('idVenta');
    $recibido=$this->input->post('recibido');
    $impuesto=$this->input->post('impuesto');
    $tipo=$this->input->post('tipo');
    $descuento = $this->input->post('descuento');
    $impuestoPorciento=0;
    $nombreImpuesto="";
    $credito = 0;
    $cambio = 0;
    if($tipo == 0)
    {
      $cambio =$recibido-$total;
    }
    elseif ($tipo == 2)
    {
      $credito = 1;
    }
    $codigo =$this->input->post('codigo');

    $datos=array(
      'total' => $total,
      'idVenta' => $idVenta,
      'recibido' => $recibido,
      'cambio' => $cambio,
      'tipoPago' => $tipo
    );

    if($impuesto){
      $impuestoPorciento=$this->input->post('impuestoPorciento');
      $nombreImpuesto=$this->input->post('nombreImpuesto');
    }

    if($cambio<0 && $tipo==1)
    {
      echo json_encode($datos);
      die();
    }
    $data = array(
      'Total'=>$total,
      'Fecha'=>date("Y-m-d H:i:s"),
      'impuestoPorciento'=>$impuestoPorciento,
      'nombreImpuesto'=>$nombreImpuesto,
      'tipoPago' => $tipo,
      'impuestoDescuento' => $descuento,
      'operacionBaucher' => $codigo,
      'descuento'=> $this->verificarOfertaEz($idVenta),
      'credito' => $credito
    );

    $where=$idVenta;
    $this->insertar->setVenta($data,$where);
    //descontar productos del inventario
    $listaItemsVenta = $this->consultas->getItemsDeVentas($idVenta);
    foreach ($listaItemsVenta as $itemVenta) {
      $myItem=$this->consultas->getInventariobyId($itemVenta['idInventario']);
      $nuevaCantidad = $myItem['cantidad']-$itemVenta['cantidad'];
      $dataSetItem=array(
        'cantidad'=>$nuevaCantidad
      );
      $whereSetItem=array(
        'id'=>$itemVenta['idInventario']
      );
      $this->insertar->setProducto($dataSetItem,$whereSetItem);
    }
    $this->insertar->dropMovimientoTemporal($idVenta);

    echo json_encode($datos);
  }

  public function verificarProducto()
  {
    $codigo = $this->input->post('codigo');
    echo $this->consultas->comprobarCodigo($codigo);
  }

  public function addItemToVenta()
  {
    $idVenta = $this->input->post('idVenta');
    $idItem = $this->input->post('idItem');
    $cantidad = $this->input->post('cantidad');

    $item=$this->consultas->getInventariobyId($idItem);
    if(!$item)
    {
      $resultado = array(
        'validante'=>'n',
      );
      echo json_encode($resultado);
      die();
    }
    $costo=$item['precio'];
    if($cantidad>=$item['cantidadMayoreo']){
      $costo=$item['precioMayoreo'];
    }
    //comprobar si hay mas de este articulo para nadmas sumarlo
    $exysteItemEnVenta = $this->consultas->comprobarItemEnVenta($idVenta,$idItem);
    if($exysteItemEnVenta)
    {

      $movimiento = $this->consultas->getMovimientoVenta($idVenta,$idItem);
      $item = $this->consultas->getInventariobyId($idItem);
      $sumCantidad = $cantidad + $movimiento['cantidad'];
      $dataMovimiento = array(
        'cantidad'=>$sumCantidad,
        'cantidadOfertas'=>$sumCantidad,
        'costoUnitario'=>$costo
      );
      $whereMovimiento = array(
        'id'=>$movimiento['id']
      );
      $this->insertar->updateMovimientoVenta($dataMovimiento,$whereMovimiento);

      $resultado = array(
        'validante'=>'r',
        'ncantidad'=>$sumCantidad
      );
      echo json_encode($resultado);
    }
    else
    {

      $dataMovimiento = array(
        'idVenta' => $idVenta,
        'idInventario'=>$idItem,
        'cantidad'=>$cantidad,
        'cantidadOfertas'=>$cantidad,
        'costoUnitario'=>$costo
      );

      $temp = array(
        'id_venta' => $idVenta,
        'id_usuario' => $this->session->userdata('idUser')
      );
      $this->insertar->newMovimientoVenta($dataMovimiento);
      $this->insertar->newMovimientoTemporal($temp);

      // $this->verificarOferta();
      //$1this->consultas->isOferta($ids);
      $resultado = array(
        'validante'=>'x'
      );
      echo json_encode($resultado);
    }
  }

  public function buscarIndiceOferta($ofertas,$value,$max = -1,$id_oferta = 0){
    if(isset($ofertas[$value['id_oferta']][$value['id_inventario']])){
      if(count($ofertas[$value['id_oferta']][$value['id_inventario']]) > $max){
        $max = count($ofertas[$value['id_oferta']][$value['id_inventario']]);
        $id_oferta = $value['id_oferta'];
        $this->buscarIndiceOferta($ofertas,$value,$max,$id_oferta);
      }
    }
    return $id_oferta;
  }

  // public function verificarOferta($validante = '',$sumCantidad = 0){
  //   $idVenta = $this->input->post('idVenta');
  //   $itemsv = $this->consultas->getItemsDeVentas($idVenta);
  //   $articulos = array();
  //
  //   $ofertas = array();
  //
  //   $oferta_inventario = $this->consultas->getAllOfertaInventario();
  //
  //
  //   foreach ($oferta_inventario as $key => $value) {
  //     $ofertas[$value['id_oferta']][$value['id_inventario']] = array();
  //   }
  //
  //   foreach ($itemsv as $its) {
  //       $oferInv = $this->consultas->getOfertaInventarioCampos($its['idInventario']);
  //       for($i = 0; $i < $its['cantidad']; $i++){
  //         $enOferta = false;
  //         foreach ($oferInv as $key => $value) {
  //           $id_oferta = $this->buscarIndiceOferta($ofertas,$value);
  //           if(isset($ofertas[$id_oferta][$value['id_inventario']])){
  //               if(empty($ofertas[$id_oferta][$value['id_inventario']])){
  //                 $value['count'] = 1;
  //                 $ofertas[$id_oferta][$value['id_inventario']] = $value;
  //               }else
  //                 $ofertas[$id_oferta][$value['id_inventario']]['count']++;
  //           }
  //           break;
  //         }
  //       }
  //   }
  //
  //   $totalOferta = 0;
  //
  //   foreach ($ofertas as $key => $value) {
  //      if(count($value) >= 2){
  //        $count = 0;
  //        $totalOfertaItem = 0;
  //        $totalProductos = 0;
  //        if($this->ofertaCompletaArray($value)){
  //           foreach ($value as $key => $itemsOferta) {
  //             $count+= $itemsOferta['count'];
  //             $totalOfertaItem = $itemsOferta['precio_oferta'];
  //           }
  //           $count = floor(($count / count($value)));
  //           $totalOfertaItem = $totalOfertaItem * $count;
  //           foreach ($value as $key => $itemsOferta)  {
  //              $totalProductos+= $itemsOferta['precio'] * $count;
  //           }
  //           $totalOferta = $totalProductos - $totalOfertaItem;
  //        }
  //       }
  //   }
  //
  //   $resultado = array(
  //     'validante'=> $validante,
  //     'ncantidad' => $sumCantidad,
  //     'ofertamonto' => $totalOferta
  //   );
  //   echo json_encode($resultado);
  // }
  //
  // function ofertaCompletaArray($array) {
  //     foreach($array as $key => $val) {
  //         if (empty($val))
  //             return false;
  //     }
  //     return true;
  // }

  // public function impVenta()
  // {
  //   $idVenta = $this->input->post('idVenta');
  //   $detalleItem = $this->consultas->getItemsDeVentas($idVenta);
  //   $detalleVenta = $this->consultas->getVentaById($idVenta);
  //   $nombreEmpresa = $this->consultas->getConfigs()->nombreEmpresa;
  //   $vendedor=$this->consultas->getUsers($detalleVenta['idUsuario']);
  //   $data=array(
  //     'detalle'=>$detalleItem,
  //     'detalleVenta'=>$detalleVenta,
  //     'vendedor'=>$vendedor,
  //     'nombreEmpresa'=>$nombreEmpresa
  //   );
  //   $data['monedaString']=$this->consultas->getMonedaString();
  //   $this->load->view('inventario/_ticket',$data);
  // }


  public function impVenta()
  {
    $idUser = $this->session->userdata('idUser');
    $idVenta = $this->input->post('idVenta');
    $recibido = $this->input->post('recibido');
    $detalleItem = $this->consultas->getItemsDeVentas($idVenta);
    $detalleVenta = $this->consultas->getVentaById($idVenta);
    $nombreEmpresa = $this->consultas->getConfigs();
    $nombreEmpresa = $nombreEmpresa->nombreEmpresa;
    $vendedor = $this->consultas->getUsers($detalleVenta['idUsuario']);
    $moneda=$this->consultas->getMonedaString();
    // $impresora = "C:\Users\jalonso\Desktop\miprint.txt";//$this->consultas->getPrinterCaja();
    // $impresora=$this->consultas->getTiketera();
    $impresora=$this->consultas->getTiketeraByUsuario($idUser);
    try {
      $this->load->library('Ticket');
      $this->ticket->goTicket($detalleItem,$detalleVenta,$nombreEmpresa,$vendedor,$impresora,$recibido,$moneda);
    } catch (Exception $e) {
      log_message("error", "Error: Could not print. Message ".$e->getMessage());
      $this->ticket->close_after_exception();
    }


  }

  public function buscarpr()
  {
    $inventario = $this->consultas->getInventario();
    $data = array('inventario' => $inventario);
    $data['monedaString']=$this->consultas->getMonedaString();
    $this->load->view('_busqueda',$data);
  }

  public function verificarOfertaEz($idVenta)
  {
    $totalDescuento = 0;
    // obtenemos los items de la venta
    $itemsDeVenta = $this->consultas->getMovimientoVentaAll($idVenta);
    // verificamos si hay mas de 1 articulos en la venta
    if(count($itemsDeVenta)>1)
    {
      //recorrer cada elemento para ver si se encuentra en alguna promocion
      foreach ($itemsDeVenta as $itemDeVenta) {
        //buscar item en tabla de promociones mientras la promocion este activate
        $promociones = $this->consultas->ofertasConElemento($itemDeVenta["idInventario"]);
        // me aseguro que el elemento este en alguna promocion
        if($promociones){
          // recorro las promociones (ordenadas por el de menor precio final)
          foreach ($promociones as $promocion) {
            // tomo la promocion en curso y busco todos los elementos relacionados a esa promocion
            $elementosPromocion = $this->consultas->getElementosDeOferta($promocion["id_oferta"]);
            // recorro la lista de elementos y me aseguro de que todos se encuentran en la ventas
            $todosLosElementosEnVenta = true;
            $totalPrecioElementos=0;
            foreach ($elementosPromocion as $elementoPromocion) {
              $comprobacionDeElemento  = $this->consultas->conprobarElementoEnVenta($idVenta,$elementoPromocion["id_inventario"]);
              // verifico que el elemento esta en la venta
              if(   $comprobacionDeElemento  > 0   ){
                $totalPrecioElementos+=$comprobacionDeElemento;
              }
              else{
                $todosLosElementosEnVenta = false;
                break;
              }
            }
            if($todosLosElementosEnVenta){
              // al estar todos los elementos de la promocion en la venta entonces si cumple el descuento
              // asi que se procede a sumar el costo de cada articulo y restarlo con la promocion para saber de cuanto es el ahorro al cliente
              $descuento =  $totalPrecioElementos - $promocion["precio"];
              $totalDescuento += $descuento;
              // al aplicar el descuento se descartan estos elementos de los demas descuentos
              foreach ($elementosPromocion as $elementoPromocion) {
                // trer los datos de elemento en la ventas
                $miElementoEnVenta = $this->consultas->getMovimientoVenta($idVenta,$elementoPromocion["id_inventario"]);
                $data = array('cantidadOfertas' => $miElementoEnVenta["cantidadOfertas"]-1 );
                $where = array('id' => $miElementoEnVenta['id'] );
                // restarle una unidad a la cantidadOfertas
                $this->insertar->updateMovimientoVenta($data,$where);
              }
            }
          }
        }
      }
    }
    $elementosVenta = $this->consultas->getMovimientoVentaAll($idVenta);
    foreach ($elementosVenta as $elementoVenta) {
      // trer los datos de elemento en la ventas
      $data = array('cantidadOfertas' => $elementoVenta["cantidad"] );
      $where = array('id' => $elementoVenta['id'] );
      $this->insertar->updateMovimientoVenta($data,$where);
      // restarle una unidad a la cantidadOfertas
    }
    return $totalDescuento;
  }

  public function formMoviCaja()
  {
    $tipo = $this->input->post('tipo');
    $txtlabel = "Razón de Entrada";
    $txtbutton = "Registrar Entrada";
    if($tipo==2){
      $txtlabel = "Razón de Salida";
      $txtbutton = "Registrar Salida";
    }
    $data = array(
      'tipo' => $tipo,
      'txtlabel' => $txtlabel,
      'txtbutton' => $txtbutton
    );
    $this->load->view('_formMoviCaja',$data);
  }

  public function newMoviCaja()
  {
    $claveAdmin = $this->input->post('key');
    $claveOk = $this->consultas->comprobarClaveAdmin($this->encriptar($claveAdmin));
    if(!$claveOk){
      echo "x";
      die();
    }
    $idUser = $this->session->userdata('idUser');
    $tipo = $this->input->post('tipo');
    $monto = $this->input->post('monto');
    $razon = $this->input->post('razon');
    $datos = array(
      'tipo' => $tipo,
      'monto' => $monto,
      'fecha' => date("Y-m-d H:i:s"),
      'idUsuario' => $idUser,
      'razon' => $razon
    );
    $this->insertar->insertarGral("tb_movimientos_caja",$datos);
    echo 1;
  }

  // encriptar
  function encriptar($string) {
    $key=$this->config->item('mi_key');
    $result = '';
    for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
    }
    return base64_encode($result);
  }

  public function addClienteHtml()
  {
    $nombre="";
    $textoBoton="Agregar";
    $idForm="formAddCliente";
    $myCliente =array(
      'id'=>'',
      'nombre'=>'',
      'identidad'=>'',
      'direccion'=>'',
      'telefono'=>'',
      'correo'=>''
    );

    $data=array(
      'idForm'=>$idForm,
      'idCliente'=>0,
      'nombre'=>$nombre,
      'textoBoton'=>$textoBoton,
      'roles'=>$this->consultas->getRoles(),
      'cliente'=>$myCliente,
    );
    $this->load->view('_addCliente',$data);
  }

  public function addCliente()
  {
    $nombre = $this->input->post('nombre');
    $datos = array(
      'nombre'=>$nombre,
      'direccion'=>$this->input->post('direccion'),
      'telefono'=>$this->input->post('telefono'),
      'correo'=>$this->input->post('correo')
    );
    $idCliente = $this->insertar->newCliente($datos);

    $respuesta=array(
      'nombre'=>$nombre,
      'id'=>$idCliente
    );
    echo json_encode($respuesta);
  }

  public function actualizarClienteVenta()
  {
    $idVenta = $this->input->post('idVenta');
    $idCliente = $this->input->post('idCliente');
    $this->insertar->actualizarClienteVenta($idVenta,$idCliente);
  }


}
?>
