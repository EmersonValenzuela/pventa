<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// IMPORTANT - Replace the following line with your path to the escpos-php autoload script

require __DIR__ . '..\Mike42\autoload.php';
use Mike42\Escpos\Printer;
// use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\EscposImage;
// use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class Ticket {

  private $CI;
  private $connector;
  private $printer;
  // TODO: printer settings
  // Make this configurable by printer (32 or 48 probably)
  private $printer_width = 48;
  function __construct()
  {
    $this->CI =& get_instance(); // This allows you to call models or other CI objects with $this->CI->...
  }


  public function connect($c)
  {
    // $this->connector = new NetworkPrintConnector($ip_address, $port);
    // $this->printer = new Printer($this->connector);
    // $this->connector = new FilePrintConnector($c);
    // $this->printer = new Printer($this->connector);
  }

  public function close_after_exception()
  {
    if (isset($this->printer) && is_a($this->printer, 'Mike42\Escpos\Printer')) {
      $this->printer->close();
    }
    $this->connector = null;
    $this->printer = null;
    $this->emc_printer = null;
  }
  // Calls printer->text and adds new line
  private function add_line($text = "", $should_wordwrap = true)
  {
    $text = $should_wordwrap ? wordwrap($text, $this->printer_width) : $text;
    $this->printer->text($text."\n");
  }



  public function goTicket($detalleItem,$detalleVenta,$nombreEmpresa,$vendedor,$impresora,$recibido,$moneda)
  {
    // $connector = new FilePrintConnector('C:\Users\Harf-03\Desktop\p.txt');
    // $connector = new FilePrintConnector('\\\\localhost\\TIKETERA');
    $connector = new FilePrintConnector($impresora);
    $this->printer = new Printer($connector);
    //
    // $this->printer->setJustification(Printer::JUSTIFY_CENTER);
    // $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    $this->printer-> setEmphasis(true);
    $this->printer-> text(str_pad("********************************", 48, " ", STR_PAD_BOTH) . "\n");
    $this->printer-> text(str_pad($nombreEmpresa, 48, " ", STR_PAD_BOTH) . "\n");
    $this->printer-> text(str_pad("********************************", 48, " ", STR_PAD_BOTH) . "\n");
    $this->printer-> setEmphasis(false);
    $this->printer-> feed();
    $this->printer-> selectPrintMode();

    //datos generales del Ticket
    $this->printer-> text("Id Ticket:" . $detalleVenta['id'] . "\n");
    $this->printer-> text("Fecha: " . date('d-m-Y H:i', strtotime($detalleVenta['Fecha'])) . "\n");
    $this->printer-> text("Vendedor: ".$vendedor['nombre']."\n");
    $this->printer-> feed();

    //encabezados tabla
    //codigo 7-7-0
    //Descripcion 31-17-27
    //P.U.  (11)-8-8
    //cnatidad 9-8-5
    //importe 11-8-8
    $this->printer-> setEmphasis(true);
    // $codigo=str_pad("Codigo", 7, " ", STR_PAD_BOTH);
    $descripcion=str_pad("Descripcion", 27, " ", STR_PAD_RIGHT);
    $pu=str_pad("P.U ", 8, " ", STR_PAD_BOTH);
    $cantidad=str_pad("Cant ", 5, " ", STR_PAD_BOTH);
    $importe=str_pad("Importe", 8, " ", STR_PAD_LEFT);
    $linea=$descripcion.$pu.$cantidad.$importe;
    $this->printer-> text($linea . "\n");
    $this->printer-> setEmphasis(false);
    // $codigo=str_pad("008", 7, " ", STR_PAD_BOTH);
    $importeTotal=0;
    foreach ($detalleItem as $fila){
      $importeFila = $fila['costoUnitario']*$fila['cantidad'];
      $descripcion=str_pad(substr($fila['descripcion'], 0, 27), 27, " ", STR_PAD_RIGHT);
      $pu=str_pad($moneda." ".number_format($fila['costoUnitario'],2), 8, " ", STR_PAD_LEFT);
      $cantidad=str_pad(number_format($fila['cantidad'],2), 5, " ", STR_PAD_BOTH);
      $importe=str_pad($moneda." ".number_format($importeFila,2), 8, " ", STR_PAD_LEFT);
      $linea=$descripcion.$pu.$cantidad.$importe;
      $this->printer-> text("$linea" . "\n");
      $importeTotal+=$importeFila;
    }

    $this->printer-> feed();

    $this->printer-> setJustification(Printer::JUSTIFY_LEFT);
    $this->printer-> setEmphasis(true);
    $impuesto = $importeTotal*$detalleVenta['impuestoPorciento']*0.01;
    $this->printer-> text(str_pad("Subtotal: ".$moneda." ".number_format($importeTotal,2), 48, " ", STR_PAD_LEFT) . "\n");
    $this->printer-> text(str_pad("Impuestos: ".$moneda." ".number_format($impuesto,2), 48, " ", STR_PAD_LEFT) . "\n");
    $this->printer-> text(str_pad("Ofertas: ".$moneda." ".number_format($detalleVenta['descuento'],2), 48, " ", STR_PAD_LEFT) . "\n");
    $this->printer-> text(str_pad("Total: ".$moneda." ".number_format($detalleVenta['Total'],2), 48, " ", STR_PAD_LEFT) . "\n");
    if($recibido<0 ){
      $this->printer-> text(str_pad("Recibido: ".$moneda." ".number_format($recibido,2), 48, " ", STR_PAD_LEFT) . "\n");
      $this->printer-> text(str_pad("Cambio: ".$moneda." ".number_format($recibido-$detalleVenta['Total'],2), 48, " ", STR_PAD_LEFT) . "\n");
    }
    $this->printer-> setEmphasis(false);
    $this->printer-> selectPrintMode();

    $this->printer-> feed();

    $this->printer->setJustification(Printer::JUSTIFY_CENTER);
    $this->printer-> text(str_pad("Gracias por su compra", 48, " ", STR_PAD_BOTH) . "\n");
    $this->printer-> selectPrintMode();
    $this->printer-> feed();
    $this->printer-> feed();
    $this->printer-> feed();
    $this->printer-> feed();
    $this->printer-> feed();
    $this->printer-> feed();
    $this->printer->cut();
    $this->printer->close();


  }




  //
  //
  // public function goCuenta($detalleItem,$detalleVenta,$nombreEmpresa,$impresora)
  // {
  //   // $connector = new FilePrintConnector('C:\Users\Harf-03\Desktop\p.txt');
  //   // $connector = new FilePrintConnector('\\\\localhost\\TIKETERA');
  //   $connector = new FilePrintConnector($impresora);
  //   $this->printer = new Printer($connector);
  //   //
  //   // $this->printer->setJustification(Printer::JUSTIFY_CENTER);
  //   // $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
  //   $this->printer-> setEmphasis(true);
  //   $this->printer-> text(str_pad("********************************", 48, " ", STR_PAD_BOTH) . "\n");
  //   $this->printer-> text(str_pad($nombreEmpresa, 48, " ", STR_PAD_BOTH) . "\n");
  //   $this->printer-> text(str_pad("********************************", 48, " ", STR_PAD_BOTH) . "\n");
  //   $this->printer-> setEmphasis(false);
  //   $this->printer-> feed();
  //   $this->printer-> selectPrintMode();
  //
  //   //datos generales del Ticket
  //   $this->printer-> text("Mesa:" . $detalleVenta['mesa'] . "\n");
  //   // $this->printer-> text("Fecha: " . date('d-m-Y H:i', strtotime($detalleVenta['Fecha'])) . "\n");
  //   $this->printer-> feed();
  //
  //   //encabezados tabla
  //   //codigo 7-7-0
  //   //Descripcion 31-17-27
  //   //P.U.  (11)-8-8
  //   //cnatidad 9-8-5
  //   //importe 11-8-8
  //   $this->printer-> setEmphasis(true);
  //   // $codigo=str_pad("Codigo", 7, " ", STR_PAD_BOTH);
  //   $descripcion=str_pad("Descripcion", 27, " ", STR_PAD_RIGHT);
  //   $pu=str_pad("P.U ", 8, " ", STR_PAD_BOTH);
  //   $cantidad=str_pad("Cant ", 5, " ", STR_PAD_BOTH);
  //   $importe=str_pad("Importe", 8, " ", STR_PAD_LEFT);
  //   $linea=$descripcion.$pu.$cantidad.$importe;
  //   $this->printer-> text($linea . "\n");
  //   $this->printer-> setEmphasis(false);
  //   // $codigo=str_pad("008", 7, " ", STR_PAD_BOTH);
  //   $sumax = 0;
  //   foreach ($detalleItem as $fila){
  //     $importex = $fila['precio']*$fila['cantidad'];
  //     $descripcion=str_pad(substr($fila['descripcion'], 0, 27), 27, " ", STR_PAD_RIGHT);
  //     $pu=str_pad("$ ".number_format($fila['precio'],2), 8, " ", STR_PAD_LEFT);
  //     $cantidad=str_pad($fila['cantidad'], 5, " ", STR_PAD_BOTH);
  //     $importe=str_pad("$ ".number_format($importex,2), 8, " ", STR_PAD_LEFT);
  //     $linea=$descripcion.$pu.$cantidad.$importe;
  //     $this->printer-> text("$linea" . "\n");
  //     $sumax += $importex;
  //   }
  //
  //   $this->printer-> feed();
  //
  //   $this->printer-> setJustification(Printer::JUSTIFY_LEFT);
  //   $this->printer-> setEmphasis(true);
  //   $this->printer-> text(str_pad("Total: $ ".number_format($sumax,2), 48, " ", STR_PAD_LEFT) . "\n");
  //   $this->printer-> setEmphasis(false);
  //   $this->printer-> selectPrintMode();
  //
  //   $this->printer-> feed();
  //
  //   $this->printer-> feed();
  //   $this->printer-> feed();
  //   $this->printer-> feed();
  //   $this->printer-> feed();
  //   $this->printer-> feed();
  //   $this->printer-> feed();
  //   $this->printer->cut();
  //   $this->printer->close();
  //
  //
  // }
  //


  //
  // public function goComanda($detalle,$detalleVenta,$nombreEmpresa,$impresora)
  // {
  //   // $connector = new FilePrintConnector('C:\Users\Harf-03\Desktop\p.txt');
  //   // $connector = new FilePrintConnector('\\\\localhost\\TIKETERA');
  //   $connector = new FilePrintConnector($impresora);
  //   $this->printer = new Printer($connector);
  //
  //
  //
  //   $this->printer-> setEmphasis(true);
  //   $this->printer-> text(str_pad($nombreEmpresa, 48, " ", STR_PAD_BOTH) . "\n");
  //   $this->printer-> setEmphasis(false);
  //   $this->printer-> feed();
  //   $this->printer-> selectPrintMode();
  //
  //   //datos generales del Ticket
  //   $this->printer-> text("Mesa:" . $detalleVenta['mesa'] . "\n");
  //   $this->printer-> text("Mozo:" . $detalleVenta['nombre'] . "\n");
  //   $this->printer-> text("Fecha: " . date('d-m-Y H:i') . "\n");
  //   $this->printer-> feed();
  //
  //   //encabezados tabla
  //   //Descripcion 31-17-27
  //   //cnatidad 9-8-5
  //   $this->printer-> setEmphasis(true);
  //   // $codigo=str_pad("Codigo", 7, " ", STR_PAD_BOTH);
  //   $descripcion=str_pad("Descripcion", 40, " ", STR_PAD_RIGHT);
  //   $cantidad=str_pad("Cantidad", 8, " ", STR_PAD_BOTH);
  //   $linea=$descripcion.$cantidad;;
  //   $this->printer-> text($linea . "\n");
  //   $this->printer-> setEmphasis(false);
  //   // $codigo=str_pad("008", 7, " ", STR_PAD_BOTH);
  //   foreach ($detalle as $fila){
  //     if($fila['cantidad']==0)continue;
  //     $descripcion=str_pad(substr($fila['descripcion'], 0, 40), 40, " ", STR_PAD_RIGHT);
  //     $cantidad=str_pad($fila['cantidad'], 8, " ", STR_PAD_BOTH);
  //     $linea=$descripcion.$cantidad;
  //     $this->printer-> text("$linea" . "\n");
  //   }
  //
  //   $this->printer-> feed();
  //
  //   $this->printer-> setEmphasis(true);
  //   $this->printer-> text(str_pad("El cliente espera...", 48, " ", STR_PAD_BOTH) . "\n");
  //   $this->printer-> setEmphasis(false);
  //   $this->printer-> feed();
  //   $this->printer-> feed();
  //   $this->printer-> feed();
  //   $this->printer-> feed();
  //   $this->printer-> feed();
  //   $this->printer-> feed();
  //   $this->printer->cut();
  //   $this->printer->close();
  // }



  // public function goVentashoy($detalleVenta,$nombreEmpresa,$fecha,$impresora)
  // {
  //   // $connector = new FilePrintConnector('C:\Users\Harf-03\Desktop\p.txt');
  //   // $connector = new FilePrintConnector('\\\\localhost\\TIKETERA');
  //   $connector = new FilePrintConnector("$impresora");
  //   $this->printer = new Printer($connector);
  //
  //   $this->printer-> setEmphasis(true);
  //   $this->printer-> text(str_pad("********************************", 48, " ", STR_PAD_BOTH) . "\n");
  //   $this->printer-> text(str_pad($nombreEmpresa, 48, " ", STR_PAD_BOTH) . "\n");
  //   $this->printer-> text(str_pad("Reporte del dia ".date_format($fecha, 'd/m/Y'), 48, " ", STR_PAD_BOTH) . "\n");
  //   $this->printer-> text(str_pad("********************************", 48, " ", STR_PAD_BOTH) . "\n");
  //   $this->printer-> setEmphasis(false);
  //   $this->printer-> feed();
  //   $this->printer-> selectPrintMode();
  //
  //   $total=0;
  //   foreach ($detalleVenta as $fila){
  //     $miImporte = $fila['precio']*$fila['cantidad'];
  //     $descripcion=str_pad(substr($this->limpiarString($fila['descripcion']), 0, 27), 27, " ", STR_PAD_RIGHT);
  //     $pu=str_pad("$ ".number_format($fila['precio'],2), 8, " ", STR_PAD_LEFT);
  //     $cantidad=str_pad($fila['cantidad'], 5, " ", STR_PAD_BOTH);
  //     $importe=str_pad("$ ".number_format($miImporte,2), 8, " ", STR_PAD_LEFT);
  //     $linea=$descripcion.$pu.$cantidad.$importe;
  //     $this->printer-> text("$linea" . "\n");
  //     $total += $miImporte;
  //   }
  //
  //   $this->printer-> feed();
  //
  //   $this->printer-> setJustification(Printer::JUSTIFY_LEFT);
  //   $this->printer-> setEmphasis(true);
  //   $this->printer-> text(str_pad("Total: $ ".number_format($total,2), 48, " ", STR_PAD_LEFT) . "\n");
  //   $this->printer-> setEmphasis(false);
  //   $this->printer-> selectPrintMode();
  //
  //   $this->printer-> feed();
  //   $this->printer-> feed();
  //   $this->printer-> feed();
  //   $this->printer-> feed();
  //   $this->printer-> feed();
  //   $this->printer-> feed();
  //   $this->printer->cut();
  //   $this->printer->close();
  // }




  function limpiarString($String){
    $String = str_replace(array('á','à','â','ã','ª','ä'),"a",$String);
    $String = str_replace(array('Á','À','Â','Ã','Ä'),"A",$String);
    $String = str_replace(array('Í','Ì','Î','Ï'),"I",$String);
    $String = str_replace(array('í','ì','î','ï'),"i",$String);
    $String = str_replace(array('é','è','ê','ë'),"e",$String);
    $String = str_replace(array('É','È','Ê','Ë'),"E",$String);
    $String = str_replace(array('ó','ò','ô','õ','ö','º'),"o",$String);
    $String = str_replace(array('Ó','Ò','Ô','Õ','Ö'),"O",$String);
    $String = str_replace(array('ú','ù','û','ü'),"u",$String);
    $String = str_replace(array('Ú','Ù','Û','Ü'),"U",$String);
    $String = str_replace(array('[','^','´','`','¨','~',']'),"",$String);
    $String = str_replace("ç","c",$String);
    $String = str_replace("Ç","C",$String);
    $String = str_replace("ñ","n",$String);
    $String = str_replace("Ñ","N",$String);
    $String = str_replace("Ý","Y",$String);
    $String = str_replace("ý","y",$String);

    $String = str_replace("&aacute;","a",$String);
    $String = str_replace("&Aacute;","A",$String);
    $String = str_replace("&eacute;","e",$String);
    $String = str_replace("&Eacute;","E",$String);
    $String = str_replace("&iacute;","i",$String);
    $String = str_replace("&Iacute;","I",$String);
    $String = str_replace("&oacute;","o",$String);
    $String = str_replace("&Oacute;","O",$String);
    $String = str_replace("&uacute;","u",$String);
    $String = str_replace("&Uacute;","U",$String);
    return $String;
  }



} // fin class
