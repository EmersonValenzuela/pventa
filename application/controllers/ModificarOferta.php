<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModificarOferta extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if($this->session->userdata('rol')!=1){
      redirect(base_url());
    }
  }


  public function index($codigo='')
  {

    $idUser = $this->session->userdata('idUser');
    $rol= $this->session->userdata('rol');
    $user=$this->consultas->getUsers($idUser);
    $data = array();
    $tema = $this->consultas->configTema();
    // $inventario = $this->consultas->getInventario();
    $item = $this->consultas->getInventario($codigo);
    $departamentos = $this->consultas->getDepartamentos();
    $tiposVenta = $this->consultas->getTipoVenta();
    $dataSidebar = array();
    $dataHeader['titulo']="Inventario";
    $dataSidebar['classInventario']="active";
    $dataSidebar['classVentas']="";
    $dataSidebar['classUsuarios']="";
    $dataSidebar['classMovimientos']="";
    $dataSidebar['classCreditos']="";

    $dataSidebar['classInventarioGeneral']="";
    $dataSidebar['classInventarioModificar']="active";
    $dataSidebar['classInventarioAgregar']="";
    $dataSidebar['classInventarioNuevo']="";
    $dataSidebar['classInventarioCBarras']="";
    $dataSidebar['classConfiguraciones']="";
    $dataSidebar['classProveedores']="";
    $dataSidebar['classClientes']="";
    $dataSidebar['tema']="$tema";
    $dataSidebar['usuario']=$user;

    // $data['inventario']=$inventario;
    $data['departamentos']=$departamentos;
    $data['tiposVenta']=$tiposVenta;
    $data['item']=$item;
    $data['monedaString']=$this->consultas->getMonedaString();


    $this->load->view('header',$dataHeader);
    $this->load->view('sidebar',$dataSidebar);
    $this->load->view('inventario/modificar',$data);
    $this->load->view('main-footer');
    $dataFooter=array(
      'scripts'=> "<script src='".base_url()."js/admin.js'></script>"
    );
    $this->load->view('footer',$dataFooter);
  }

  public function modItem()
  {
    $id = $this->input->post('id');
    $descripcion = $this->input->post('descripcion');
    $costo = $this->input->post('costo');
    $precio = $this->input->post('precio');
    $pmayoreo = $this->input->post('pmayoreo');
    $cmayoreo = $this->input->post('cmayoreo');
    $departamento = $this->input->post('departamento');
    $tipoVenta = $this->input->post('tipoVenta');
    $datos = array(
      // 'codigo' => $codigo,
      'descripcion' => $descripcion,
      'costo' => $costo,
      'precio' => $precio,
      'precioMayoreo' => $pmayoreo,
      'cantidadMayoreo' => $cmayoreo,
      'idDepartamento' => $departamento,
      'idtipo'=>$tipoVenta
    );
    $where=array(
      'id'=>$id
    );

    $this->insertar->setProducto($datos,$where);
    echo "1"; // codigo 1 significa que termino con normalidad
  }
}
?>
