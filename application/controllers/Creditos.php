<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Creditos extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if($this->session->userdata('rol') !=1 && $this->session->userdata('rol') != 3)
    {
      redirect(base_url());
    }
  }

  public function index()
  {
    $idUser = $this->session->userdata('idUser');
    $rol= $this->session->userdata('rol');
    $user=$this->consultas->getUsers($idUser);
    $data = array();
    $dataHeader['titulo']="Créditos";
    $dataSidebar = array();
    $dataSidebar['classInventario']="";
    $dataSidebar['classVentas']="";
    $dataSidebar['classUsuarios']="";
    $dataSidebar['classMovimientos']="";
    $dataSidebar['classCreditos']="active";

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
    $creditos = $this->consultas->getCreditos();
    $data['creditos']=$creditos;
    $data['monedaString']=$this->consultas->getMonedaString();
    $dataSidebar['tema']="$tema";
    $this->load->view('header',$dataHeader);
    $this->load->view('sidebar',$dataSidebar);
    $this->load->view('creditos',$data);
    $this->load->view('main-footer');
    $dataFooter=array(
      'scripts'=> ""
    );
    $dataFooter['scripts'].="<script src='".base_url()."plugins/moment/moment.js'></script>";
    $dataFooter['scripts'].='<script src="'.base_url().'plugins/eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js"></script>';
    $dataFooter['scripts'].='<script src="'.base_url().'plugins/eonasdan-bootstrap-datetimepicker/src/js/locales/bootstrap-datetimepicker.es.js"></script>';
    $dataFooter['scripts'].="<script src='".base_url()."js/creditos.js'></script>";
    $dataFooter['scripts'].="<script src='".base_url()."js/tema.js'></script>";
    $this->load->view('footer',$dataFooter);
  }

  public function verDetalle()
  {
    $idVenta = $this->input->post('idVenta');
    $detalleItem = $this->consultas->getItemsDeVentas($idVenta);
    $detalleVenta = $this->consultas->getVentaById($idVenta);
    $vendedor=$this->consultas->getUsers($detalleVenta['idUsuario']);
    $data=array(
      'detalle'=>$detalleItem,
      'detalleVenta'=>$detalleVenta,
      'vendedor'=>$vendedor
    );
    $data['monedaString']=$this->consultas->getMonedaString();
    $data['abonos']=$this->consultas->getAbonosByidVenta($idVenta);
    $this->load->view('inventario/_detalleCredito',$data);
  }


  public function htmlNuevoAbono()
  {
    $idVenta = $this->input->post('idVenta');
    $data = array();
    $data['venta'] = $this->consultas->getVentaById($idVenta);
    $data['monedaString'] = $this->consultas->getMonedaString();
    $data['plazoTipo'] = $this->consultas->getTabla("tb_plazo_tipo");
    $this->load->view('_nuevoAbono',$data);
  }

  public function newAbono()
  {
    $idVenta=$this->input->post('idVenta');
    $abono=$this->input->post('recibido');
    $idTipoPago=$this->input->post('idTipoPago');
    // --
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

}
?>
