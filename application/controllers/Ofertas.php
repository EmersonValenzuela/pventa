<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ofertas extends CI_Controller
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
    $idUser = $this->session->userdata('idUser');
    $rol= $this->session->userdata('rol');
    $user=$this->consultas->getUsers($idUser);
    $data = array();
    $dataHeader['titulo']="Ofertas";
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
    $data['ofertas'] = $this->consultas->getOfertas();
    $data['monedaString']=$this->consultas->getMonedaString();
    $dataSidebar['tema']="$tema";
    $this->load->view('header',$dataHeader);
    $this->load->view('sidebar',$dataSidebar);
    $this->load->view('ofertas/ofertas-general',$data);
    $this->load->view('main-footer');
    $dataFooter=array(
      'scripts'=> "<script src='".base_url()."js/admin.js'></script>"
    );
    $dataFooter['scripts'].="<script src='".base_url()."js/tema.js'></script>";
    $this->load->view('footer',$dataFooter);
  }

  public function editar($id)
  {
    if(isset($id)){
        $idUser = $this->session->userdata('idUser');
        $rol= $this->session->userdata('rol');
        $user=$this->consultas->getUsers($idUser);

        $data = array();
        $dataHeader['titulo']="Ofertas";
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
        $data['oferta'] = $this->consultas->findIdOferta($id);
        $data['inventario_oferta'] = $this->consultas->findIdOfertaProducto($id);
        $data['inventario'] =  $this->consultas->getInventario();
        $data['monedaString']=$this->consultas->getMonedaString();
        $dataSidebar['tema']="$tema";
        $this->load->view('header',$dataHeader);
        $this->load->view('sidebar',$dataSidebar);
        $this->load->view('ofertas/modificar-oferta',$data);
        $this->load->view('main-footer');
        $dataFooter=array(
          'scripts'=> "<script src='".base_url()."js/admin.js'></script>
                       <script src='".base_url()."js/jquery.quicksearch.js'></script>
                       <script src='".base_url()."js/jquery.multi-select.js'></script>
                       <script src='".base_url()."js/oferta.js'></script>"
        );
        $dataFooter['scripts'].="<script src='".base_url()."js/tema.js'></script>";
        $this->load->view('footer',$dataFooter);
    }

  }

  public function actualiza()
  {
    $data = array(
                'descripcion' => $this->input->post('descripcion'),
                'precio' => $this->input->post('precio'),
                'estatus' => $this->input->post('estatus')
            );

    $this->db->trans_start();
    $this->insertar->setOferta($data,$this->input->post('id'));
    $this->insertar->dropOfertaInventario($this->input->post('id'));
    if(!empty($_POST['productos'])){
    foreach ($this->input->post('productos') as $idproducto) {
        $this->insertar->newOfertaInventario(
            array('id_inventario' => $idproducto,
                  'id_oferta' => $this->input->post('id'))
          ); 
    }
    }
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
        echo 0;
    }
    else
    {
        echo 1;
    }


  }
}
?>