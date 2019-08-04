<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class NuevaOferta extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if($this->session->userdata('rol')!=1){
      redirect(base_url());
    }
  }


  public function index($msj=0)
  {
    $idUser = $this->session->userdata('idUser');
    $rol= $this->session->userdata('rol');
    $user=$this->consultas->getUsers($idUser);
    $data = array();
    $tema = $this->consultas->configTema();

    $dataSidebar = array();
    $dataHeader['titulo']="Inventario";
    $dataSidebar['classInventario']="";
    $dataSidebar['classVentas']="";
    $dataSidebar['classUsuarios']="";
    $dataSidebar['classMovimientos']="";
    $dataSidebar['classCreditos']="";
    $dataSidebar['classOfertas']="active";

    $dataSidebar['classInventarioGeneral']="";
    $dataSidebar['classInventarioModificar']="";
    $dataSidebar['classInventarioAgregar']="";
    $dataSidebar['classInventarioNuevo']="active";
    $dataSidebar['classInventarioCBarras']="";
    $dataSidebar['classConfiguraciones']="";
    $dataSidebar['classProveedores']="";
    $dataSidebar['classClientes']="";
    $dataSidebar['tema']="$tema";
    $dataSidebar['usuario']=$user;

    $data['inventario']=$this->consultas->getInventario();;
    $data['monedaString']=$this->consultas->getMonedaString();


    $this->load->view('header',$dataHeader);
    $this->load->view('sidebar',$dataSidebar);
    $this->load->view('ofertas/nueva-oferta',$data);
    $this->load->view('main-footer');
    $dataFooter=array(
      'scripts'=> "<script src='".base_url()."js/jquery.quicksearch.js'></script>
                  <script src='".base_url()."js/jquery.multi-select.js'></script>
                  <script src='".base_url()."js/admin.js'></script>
                  <script src='".base_url()."js/oferta.js'></script>"
    );
    $dataFooter['scripts'].="<script src='".base_url()."js/tema.js'></script>";
    $this->load->view('footer',$dataFooter);
  }

  public function addNewItem()
  {
    $datos = array(
      'descripcion' => $this->input->post('descripcion'),
      'precio' => $this->input->post('precio'),
      'id_usuario_alta' => $this->session->userdata('idUser')
    );
    $this->db->trans_start();
    $idOferta = $this->insertar->newOferta($datos);
    foreach ($this->input->post('productos') as $idproducto) {
      $this->insertar->newOfertaInventario(
        array('id_inventario' => $idproducto,
              'id_oferta' => $idOferta)
      ); 
    }
    $this->db->trans_complete();
    echo "1";
 
  }

}
?>
