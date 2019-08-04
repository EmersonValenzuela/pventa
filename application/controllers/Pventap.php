<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pventap extends CI_Controller {

  public function index()
  {
    $idUser = $this->session->userdata('idUser');
    $rol= $this->session->userdata('rol');
    $user=$this->consultas->getUsers($idUser);
    if(!$idUser)
    {
      $dataHeader['titulo']="Login";
      $this->load->view('header',$dataHeader);
      $data['error'] ="";
      $this->load->view('login',$data);
      $dataFooter=array(
        'scripts'=> ""
      );
      $this->load->view('footer',$dataFooter);
      return false;
    }
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
    $ventastemp = $this->consultas->getVentaTempByUser($idUser);
    $data['ventastemp'] = $ventastemp->result_array();

    $dataSidebar['impresoras']=$this->consultas->getTabla("tb_impresoras");
    $dataSidebar['impresoraUsuario']=$this->consultas->getImpresoraUsuario($idUser);

    $this->load->view('header',$dataHeader);
    $this->load->view('sidebar',$dataSidebar);
    $this->load->view('generaltemp',$data);
    $this->load->view('main-footer');
    $dataFooter=array(
      'scripts'=> "<script src='".base_url()."js/pventap.js'></script>"
    );
    $this->load->view('footer',$dataFooter);

  }

  public function ventatemp($id)
  {
    // comprobar si venta ya fue cerrada
    $ventaCerrada = $this->consultas->getVentaById($id);
    $clienteVenta = $ventaCerrada["idCliente"];
    $ventaCerrada = $ventaCerrada["Total"];
    // si ya fue cerrada redirecionar a venta nueva
    if($ventaCerrada>0){
      redirect(base_url());
      die();
    }
    $idUser = $this->session->userdata('idUser');
    $rol= $this->session->userdata('rol');
    $user=$this->consultas->getUsers($idUser);
    if(!$idUser)
    {
      $dataHeader['titulo']="Login";
      $this->load->view('header',$dataHeader);
      $data['error'] ="";
      $this->load->view('login',$data);
      $dataFooter=array(
        'scripts'=> ""
      );
      $this->load->view('footer',$dataFooter);
      return false;
    }
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
    $data['clientes']=$this->consultas->getClientes();

    $data['impuesto_si']="hidden";
    if ($data['configs']->impuesto==1) {
      $data['impuesto_si']="";
    }
    $data['monedaString']=$this->consultas->getMonedaString();

    $dataSidebar['impresoras']=$this->consultas->getTabla("tb_impresoras");
    $dataSidebar['impresoraUsuario']=$this->consultas->getImpresoraUsuario($idUser);

    $data['idVenta'] = $id;
    $data['clienteVenta'] = $clienteVenta;
    $data['ventaTemp'] = $this->consultas->getMovimientoVentaAll($id);
    $data['tabla'] = "";
    $data['total'] = 0;
    $data['oferta'] = $this->verificarOfertaEz($id);

    foreach ($data['ventaTemp'] as $temp) {
      $data['total'] += $temp['cantidad']*$temp['precio'];
      $data['tabla'].= "<tr><td>".$temp['codigo']."</td><td>".$temp['descripcion']."</td><td>".$this->consultas->getMonedaString()." ".number_format($temp['precio'],2)."</td><td>".number_format($temp['cantidad'],2)."</td><td>".$this->consultas->getMonedaString()." ".number_format($temp['cantidad']*$temp['precio'],2)."</td><td class='text-center'><button class='delFila btn btn-danger btn-xs'><i class='fa fa-times-circle' aria-hidden='true'></i></button></td><td class='hidden'>".number_format($temp['cantidad']*$temp['precio'],2)."</td></tr>";

    }

    $this->load->view('header',$dataHeader);
    $this->load->view('sidebar',$dataSidebar);
    $this->load->view('ventatemp', $data);
    $this->load->view('main-footer');
    $dataFooter=array(
      'scripts'=> "<script src='".base_url()."js/pventap.js'></script>"
    );
    $this->load->view('footer',$dataFooter);


  }

  //  public function buscarIndiceOferta($ofertas,$value,$max = -1,$id_oferta = 0){
  //   if(isset($ofertas[$value['id_oferta']][$value['id_inventario']])){
  //     if(count($ofertas[$value['id_oferta']][$value['id_inventario']]) > $max){
  //       $max = count($ofertas[$value['id_oferta']][$value['id_inventario']]);
  //       $id_oferta = $value['id_oferta'];
  //       $this->buscarIndiceOferta($ofertas,$value,$max,$id_oferta);
  //     }
  //   }
  //   return $id_oferta;
  // }
  //
  // public function verificarOferta($idVenta,$validante = '',$sumCantidad = 0){
  //
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
  //   return $totalOferta;
  // }
  //
  // function ofertaCompletaArray($array) {
  //     foreach($array as $key => $val) {
  //         if (empty($val))
  //             return false;
  //     }
  //     return true;
  // }


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

}

/* End of file pventap.php */
/* Location: ./application/controllers/pventap.php */
