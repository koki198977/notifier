<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Illuminate\Support\Facades\Storage;

class PrinterService
{

    protected $jump = "\n";
    protected $space = " ";
    protected $tab = "   ";
    protected $line = "----";

    public function __construct()
    {
        //
    }

    public function print(){
        $nombreImpresora = "CUPS-BRF-Printer";
        $connector = new WindowsPrintConnector($nombreImpresora);
        $impresora = new Printer($connector);
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->setTextSize(2, 2);
        $impresora->text("Imprimiendo\n");
        $impresora->text("ticket\n");
        $impresora->text("desde\n");
        $impresora->text("Laravel\n");
        $impresora->setTextSize(1, 1);
        $impresora->text("https://parzibyte.me");
        $impresora->feed(5);
        $impresora->cut();
        $impresora->close();
    }

    public function printPreCuenta($data){
        $connector = new WindowsPrintConnector($data->impresora);
        $impresora = new Printer($connector);

        $max_width = 48; 

        // header
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->setTextSize(3,2);
        $impresora->text($data->comercio . $this->jump);
        $impresora->setTextSize(1,1);
        $impresora->text($data->comuna . $this->jump);
        $impresora->text($data->direccion . $this->jump);
        $impresora->feed();

        $impresora->setTextSize(1,1);
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->text("Mesa:" . $this->space . $data->mesa . $this->jump);
        $impresora->text("N int:" . $this->space . $data->movimiento . $this->jump);
        $impresora->text("Fecha:" . $this->space . date('Y-m-d H:i:s') . $this->jump);
        $impresora->text("Atendido por:" . $this->space . $data->mesero . $this->jump);
        $impresora->feed();

        // body
        $impresora->setTextSize(1, 1);
        $impresora->text($this->set_space_col("PRODUCTO", 25) . $this->set_space_col("UNI", 3) . $this->set_space_col("PRECIO", 8, true) . $this->set_space_col("TOTAL", 12, true) . $this->jump);
        $impresora->setTextSize(1, 1);
        $impresora->text(str_repeat("_", $max_width) . $this->jump);
        $impresora->feed();

        $impresora->setTextSize(1, 1);
        foreach ($data->detalle as $key => $value) {
            if(strlen($value['nombre']) > 25){
                $impresora->text($value['nombre'] . $this->jump);
                $impresora->text($this->set_space_col("", 25) . $this->set_space_col($value['cantidad'], 3, true) . $this->set_space_col(number_format($value['precio'], 0), 8, true) . $this->set_space_col(number_format($value['subtotal'], 0), 12, true) . $this->jump);
            }else{
                $impresora->text($this->set_space_col($value['nombre'], 25) . $this->set_space_col($value['cantidad'], 3, true) . $this->set_space_col(number_format($value['precio'], 0), 8, true) . $this->set_space_col(number_format($value['subtotal'], 0), 12, true) . $this->jump);
            }
        }

        // totales
        $impresora->setTextSize(1, 1);
        $impresora->text(str_repeat("_", $max_width) . $this->jump);
        $impresora->feed();

        $impresora->setTextSize(1,1);
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->text($this->set_space_footer("Total sin prop.", $this->space . $this->currency($data->totales[0]['totalsinprop']), $max_width) . $this->jump);
        $impresora->text($this->set_space_footer("Total:", $this->space . $this->currency($data->totales[0]['total']), $max_width) . $this->jump);
        $impresora->text($this->set_space_footer("Propina sugerida:", $this->space . $this->currency($data->totales[0]['propina']), $max_width) . $this->jump);
        $impresora->text($this->set_space_footer("Total con prop.", $this->space . $this->currency($data->totales[0]['totalconprop']), $max_width) . $this->jump);
        $impresora->feed();

        // footer
        $impresora->feed(2);
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->setTextSize(1, 1);
        $impresora->text('https://realdev.cl');

        $impresora->feed(3);
        $impresora->cut();
        $impresora->close();
    }

    public function printTicket($data){
        $connector = new WindowsPrintConnector($data->impresora);
        $impresora = new Printer($connector);

        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->setTextSize(3,2);
        $impresora->text($data->comercio . $this->jump);
        $impresora->feed();

        $impresora->setTextSize(1,1);
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->text("Mesa:" . $this->space . $data->mesa . $this->jump);
        $impresora->text("N int:" . $this->space . $data->movimiento . $this->jump);
        $impresora->text("Fecha:" . $this->space . date('Y-m-d H:i:s') . $this->jump);
        $impresora->text("Atendido por:" . $this->space . $data->mesero . $this->jump);
        $impresora->feed();

        $impresora->setTextSize(1, 2);
        $impresora->text("DESCRIPCION DEL PRODUCTO:" . $this->jump);
        $impresora->feed();

        $impresora->setTextSize(1, 1);
        foreach ($data->detalle as $key => $value) {
            $impresora->text($value['cantidad'] . $this->tab . $value['nombre'] . $this->jump);
            if(!empty($value['observacion'])){	
                $impresora->text($this->line . $value['observacion'] . $this->jump);
                $impresora->feed();
            }	
        }
        $impresora->feed(2);
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->setTextSize(1, 1);
        $impresora->text('https://realdev.cl');

        $impresora->feed(3);
        $impresora->cut();
        $impresora->close();
    }

    public function printHappy($data){
        $connector = new WindowsPrintConnector($data->impresora);
        $impresora = new Printer($connector);

        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->setTextSize(3,2);
        $impresora->text($data->comercio . $this->jump);
        $impresora->feed();

        $impresora->setTextSize(1,1);
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->text("Mesa:" . $this->space . $data->mesa . $this->jump);
        $impresora->text("N int:" . $this->space . $data->movimiento . $this->jump);
        $impresora->text("Fecha:" . $this->space . date('Y-m-d H:i:s') . $this->jump);
        $impresora->text("Atendido por:" . $this->space . $data->mesero . $this->jump);
        $impresora->feed();

        $impresora->setTextSize(1, 2);
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->text("TICKET VALIDO COMO HAPPY" . $this->jump);
        $impresora->feed();

        $impresora->setTextSize(1, 1);
        foreach ($data->detalle as $key => $value) {
            $impresora->text($value['nombre'] . $this->jump);
        }

        $impresora->feed();
        $impresora->barcode($data->comercio, Printer::BARCODE_CODE39);
        $impresora->text($data->comercio . $this->jump);

        $impresora->feed(2);
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->setTextSize(1, 1);
        $impresora->text('https://realdev.cl');

        $impresora->feed(3);
        $impresora->cut();
        $impresora->close();
    }

    public function printSII($value, $data){
        $caratula = $value['SetDTE']['Caratula'];
        $encabezado = $value['SetDTE']['DTE']['Documento']['Encabezado'];
        $detalle = $value['SetDTE']['DTE']['Documento']['Detalle'];

        $connector = new WindowsPrintConnector($data->impresora);
        $impresora = new Printer($connector);

        $max_width = 48; 

        // header
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->setTextSize(2,2);
        $impresora->text("R.U.T.." . $this->space . $encabezado['Emisor']['RUTEmisor'] . $this->jump);
        $impresora->text("BOLETA ELECTRONICA" . $this->jump);
        $impresora->setTextSize(1,1);
        $impresora->text("Folio:" . $this->space . $encabezado['IdDoc']['Folio']);
        $impresora->feed();
    
        $impresora->text(str_repeat("_", $max_width) . $this->jump);
        $impresora->feed();

        $impresora->setTextSize(1,1);
        $impresora->text("S.I.I. -" . $this->space . $encabezado['Emisor']['CmnaOrigen'] . $this->jump);
        $impresora->feed();

        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->setTextSize(1,1);
        $impresora->text($encabezado['Emisor']['RznSocEmisor']  . $this->jump);
        $impresora->text($encabezado['Emisor']['GiroEmisor']  . $this->jump);
        $impresora->text($encabezado['Emisor']['DirOrigen'] . ',' . $this->space . $encabezado['Emisor']['CmnaOrigen']  . $this->jump);
        $impresora->feed();

        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->text("Emision:" . $this->space . $this->dateToText($encabezado['IdDoc']['FchEmis']) . $this->jump);
        $impresora->feed();

        // body
        $impresora->setTextSize(1, 1);
        $impresora->text(str_repeat("_", $max_width) . $this->jump);
        $impresora->text($this->set_space_col("Item", 12) . $this->set_space_col("P. unitario", 14) . $this->set_space_col("Cant.", 10) . $this->set_space_col("Total item", 12, true));
        $impresora->text(str_repeat("_", $max_width) . $this->jump);
        $impresora->feed();

        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->setTextSize(1, 1);
        foreach ($detalle as $key => $value) {
            $impresora->text($value['NmbItem'] . $this->jump);
            $impresora->text($this->set_space_col("", 12) . $this->set_space_col(number_format($value['PrcItem'], 0), 14) . $this->set_space_col(number_format($value['QtyItem'], 0), 10) . $this->set_space_col(number_format($value['MontoItem'], 0), 12, true) . $this->jump);
        }
        $impresora->text(str_repeat("_", $max_width) . $this->jump);
        $impresora->feed();

        // totales
        $impresora->text($this->set_space_col("Neto $ :", 32, true) . $this->set_space_col(number_format($encabezado['Totales']['MntNeto'],0), 16, true) . $this->jump);
        $impresora->text($this->set_space_col("Exento $ :", 32, true) . $this->set_space_col(number_format($encabezado['Totales']['MntExe'],0), 16, true) . $this->jump);
        $impresora->text($this->set_space_col("IVA (%) $ :", 32, true) . $this->set_space_col(number_format($encabezado['Totales']['IVA'],0), 16, true) . $this->jump);
        $impresora->text($this->set_space_col("Total $ :", 32, true) . $this->set_space_col(number_format($encabezado['Totales']['MntTotal'],0), 16, true) . $this->jump);
        $impresora->feed();

        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $img = EscposImage::load(public_path() . '/pdf417code.png', false);
        $impresora->bitImage($img);
        $impresora->text("Timbre Electronico SII" . $this->jump);
        $impresora->text("Resolucion" . $this->space . $caratula['NroResol'] . ' de ' . explode('-', $caratula['FchResol'])[0] . $this->jump);
        $impresora->text("Verifique documento: www.sii.cl" . $this->jump);
        $impresora->feed();

        $impresora->text(str_repeat("_", $max_width) . $this->jump);
        $impresora->feed();
        $impresora->text("GRACIAS POR PREFERIRNOS" . $this->jump);
        $impresora->text($data->comercio . $this->jump);
        $impresora->feed(3);

        $impresora->cut();
        $impresora->close();
    }

    private function currency($value){
        return '$' . number_format($value, 0);
    }

    private function set_space_col($value, $size, $is_reverse = false){
        $count = $size - strlen($value);
        return !$is_reverse ? $value . str_repeat(" ", $count) : str_repeat(" ", $count) . $value;
    }

    private function set_space_footer($title, $value, $size){
        $count = $size - (strlen($title) + strlen($value));
        return $title . str_repeat(" ", $count) . $value;
    }

    private function dateToText($value){
        $date = explode('-', $value);
        $year = $date[0];
        $month = intval($date[1]);
        $day = $date[2];
        $month_str = '';

        switch ($month) {
            case 1:
                $month_str = 'Enero';
                break;
            case 2:
                $month_str = 'Febrero';
                break;
            case 3:
                $month_str = 'Marzo';
                break;
            case 4:
                $month_str = 'Abril';
                break;
            case 5:
                $month_str = 'Mayo';
                break;
            case 6:
                $month_str = 'Junio';
                break;
            case 7:
                $month_str = 'Julio';
                break;
            case 8:
                $month_str = 'Agosto';
                break;
            case 9:
                $month_str = 'Septiembre';
                break;
            case 10:
                $month_str = 'Octubre';
                break;
            case 11:
                $month_str = 'Noviembre';
                break;
            case 12:
                $month_str = 'Diciembre';
                break;
            
            default:
                $month_str = '--';
                break;
        }

        return $day . $this->space . 'de' . $this->space . $month_str . $this->space . 'del' . $this->space . $year;
    }
}