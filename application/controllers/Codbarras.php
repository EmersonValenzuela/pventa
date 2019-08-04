<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Codbarras extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if($this->session->userdata('rol')!=1){
      redirect(base_url());
    }
  }


  public function index()
  {
    $idUser = $this->session->userdata('idUser');
    $rol= $this->session->userdata('rol');
    $user=$this->consultas->getUsers($idUser);
    $data = array();
    $dataHeader['titulo']="Generar Código de barras";
    $dataSidebar = array();
    $dataSidebar['classInventario']="active";
    $dataSidebar['classVentas']="";
    $dataSidebar['classUsuarios']="";
    $dataSidebar['classMovimientos']="";
    $dataSidebar['classCreditos']="";

    $dataSidebar['classInventarioGeneral']="";
    $dataSidebar['classInventarioModificar']="";
    $dataSidebar['classInventarioAgregar']="";
    $dataSidebar['classInventarioNuevo']="";
    $dataSidebar['classInventarioCBarras']="active";
    $dataSidebar['classConfiguraciones']="";
    $dataSidebar['classProveedores']="";
    $dataSidebar['classClientes']="";
    $dataSidebar['usuario']=$user;

    $tema = $this->consultas->configTema();
    $inventario = $this->consultas->getInventario();
    $data['inventario']=$inventario;
    $dataSidebar['tema']="$tema";
    $this->load->view('header',$dataHeader);
    $this->load->view('sidebar',$dataSidebar);
    $this->load->view('inventario/codbarras',$data);
    $this->load->view('main-footer');
    $dataFooter=array(
      'scripts'=> "<script src='".base_url()."js/admin.js?v=3'></script>"
    );
    $dataFooter['scripts'].="<script src='".base_url()."js/tema.js?v=2'></script>";
    $this->load->view('footer',$dataFooter);
  }





  // new barCodeGenrator($code_number,0,$code_number.'.gif', 190, 130, true);

  // ====================================================================
  public function makeBarCode($code = '')
  {
    if(   strlen($code)>12   ){
      $code = substr($code, 0, -1);
    }

    $this->load->library('Zend');
    $this->zend->load('Zend/Barcode');

    Zend_Barcode::render('ean13', 'image', array('text'=>$code), array());


  }

}
?>
