<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Compras extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if($this->session->userdata('rol')!=1 && $this->session->userdata('rol')!=3){
      redirect(base_url());
    }
  }


  public function index()
  {
    $idCompra = $this->getIdCompra();
    $this->delete->delMovimientosCompraFail($idCompra);

    $idUser = $this->session->userdata('idUser');
    $rol= $this->session->userdata('rol');
    $user=$this->consultas->getUsers($idUser);
    $data = array();
    $dataHeader['titulo']="Compras";
    $dataSidebar = array();
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

    $tema = $this->consultas->configTema();
    $dataSidebar['tema']="$tema";
    $dataSidebar['usuario']=$user;

    $data['configs']=$this->consultas->getConfigs();

    $data['impuesto_si']="hidden";
    if ($data['configs']->impuesto==1) {
      $data['impuesto_si']="";
    }
    $data['monedaString']=$this->consultas->getMonedaString();
    $data['proveedores'] = $this->consultas->getProveedores();
    $tema = $this->consultas->configTema();
    // $data['idCompra'] = $idCompra;
    $data['monedaString']=$this->consultas->getMonedaString();
    $dataSidebar['tema']="$tema";
    $this->load->view('header',$dataHeader);
    $this->load->view('sidebar',$dataSidebar);
    $this->load->view('compras/compras-general',$data);
    $this->load->view('main-footer');
    $dataFooter=array(
      'scripts'=> "<script src='".base_url()."js/compras.js'></script>"
    );
    $dataFooter['scripts'].="<script src='".base_url()."js/tema.js'></script>";
    $this->load->view('footer',$dataFooter);
  }


  public function getIdCompra()
  {
    // eliminar nuevos precios descartados
    $this->delete->truncar("tb_precio_compra_temporal");
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



  public function getItem()
  {
    $codigo = $this->input->post('codigo');
    $item = $this->consultas->getInventario($codigo);
    echo json_encode($item);
  }


  public function concretarCompra()
  {
    $idCompra = 0;
    $idUser = $this->session->userdata('idUser');
    $idCompra = $this->consultas->getMaxIdVentasByUser($idUser);
    $idCompra = $idCompra["id"];
    $total=$this->input->post('total');
    $folio=$this->input->post('folio');
    $proveedor=$this->input->post('proveedor');
    if($folio==""){
      die("x1");
    }
    if($total==0){
      die("x2");
    }


    //obtener nuevos precios de esta compra
    $nuevosPrecios = $this->consultas->getTabla("tb_precio_compra_temporal");
    foreach ($nuevosPrecios as $nuevoPrecio) {
      // actualizamos los costos
      $this->insertar->setProducto(
        array('costo' => $nuevoPrecio["precioTemporal"]),
        array('id' => $nuevoPrecio["idProducto"])
      );
    }
    //eliminar nuevos precios
    $configuraciones = $this->consultas->getConfigs();
    $impuesto = $configuraciones->impuesto;

    $impuestoPorciento=0;
    $nombreImpuesto="";



    if($impuesto==1){
      $impuestoPorciento=$configuraciones->impuestoPorciento;
      $nombreImpuesto=$configuraciones->nombreImpuesto;
    }

    $data = array(
      'id_provedor'=>$proveedor,
      'folio_factura'=>$folio,
      'total'=>$total,
      'fecha'=>date("Y-m-d h:i:s"),
      'id_usuario' => $idUser,
      'idVenta'=>$idCompra
    );

    $this->insertar->newCompra($data);
    $data = array(
      'Total'=>$total,
      'Fecha'=>date("Y-m-d h:i:s"),
      'impuestoPorciento'=>$impuestoPorciento,
      'nombreImpuesto'=>$nombreImpuesto,
      'tipoMovimiento'=>1
    );
    $where =  $idCompra;
    $this->insertar->setVenta($data,$where);

    //sumar productos al inventario
    $listaItemsVenta = $this->consultas->getItemsDeVentas($idCompra);
    foreach ($listaItemsVenta as $itemVenta) {
      $myItem=$this->consultas->getInventariobyId($itemVenta['idInventario']);
      $nuevaCantidad = $myItem['cantidad']+$itemVenta['cantidad'];
      $dataSetItem=array(
        'cantidad'=>$nuevaCantidad
      );
      $whereSetItem=array(
        'id'=>$itemVenta['idInventario']
      );
      $this->insertar->setProducto($dataSetItem,$whereSetItem);
    }
    echo 1;
  }




  public function verificarProducto()
  {
    $codigo = $this->input->post('codigo');
    $respuesta =  $this->consultas->comprobarCodigo($codigo);
    if($respuesta) {
      $respuesta = 1;
    }
    else{
      die(0);
    }
    // verificar si producto ya esta en lista de compras para Descartar
    $idUser = $this->session->userdata('idUser');
    $idVenta = $this->consultas->getMaxIdVentasByUser($idUser);
    $idVenta = $idVenta["id"];
    $item = $this->consultas->getInventario($codigo);
    $exysteItemEnVenta = $this->consultas->comprobarItemEnVenta($idVenta,$item["id"]);
    if($exysteItemEnVenta){
      $respuesta = 2;
    }
    echo $respuesta;
  }



  public function delItemToCompra()
  {
    $idUser = $this->session->userdata('idUser');
    $idCompra= $this->consultas->getMaxIdVentasByUser($idUser);
    $idCompra= $idCompra["id"];
    $codigoItem=$this->input->post('codigo');
    $item = $this->consultas->getInventario($codigoItem);
    $this->delete->delMovimiento($idCompra,$item['id']);
    echo "1";
  }



  public function addItemToCompra()
  {
    $idVenta = 0;
    $idUser = $this->session->userdata('idUser');
    $idVenta = $this->consultas->getMaxIdVentasByUser($idUser);
    $idVenta = $idVenta["id"];

    $idItem = $this->input->post('idItem');
    $cantidad = $this->input->post('cantidad');

    $item=$this->consultas->getInventariobyId($idItem);
    if(!$item){
      $resultado = array(
        'validante'=>'n',
      );
      echo json_encode($resultado);
      die();
    }

    $costo=$item['costo'];


    $dataMovimiento = array(
      'idVenta' => $idVenta,
      'idInventario'=>$idItem,
      'cantidad'=>$cantidad,
      'costoUnitario'=>$costo,
      'tipo'=>1,
      'fechaEntrada'=>date("Y-m-d h:i:s"),
    );
    $this->insertar->newMovimientoVenta($dataMovimiento);
    $resultado = array(
      'validante'=>'x'
    );
    echo json_encode($resultado);

  }

  public function saveNewCostoTemporal()
  {
    $idProducto = $this->input->post('idProducto');
    $newCosto = $this->input->post('newCosto');
    // si el item esta entonces borrarlo
    $this->delete->eliminarGral("tb_precio_compra_temporal",array('idProducto' => $idProducto )   );
    // agregar nuevo precio temporal de item
    $dataPrecioTemporal = array(
      'idProducto' => $idProducto,
      'precioTemporal'=>$newCosto,
    );
    $this->insertar->insertarGral("tb_precio_compra_temporal",$dataPrecioTemporal);
  }


}

?>
