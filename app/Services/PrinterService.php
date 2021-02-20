<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

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
        $impresora->setTextSize(2,1);
        $impresora->text('Ovalle' . $this->jump);
        $impresora->text('Las Canteras, 132' . $this->jump);
        $impresora->feed(1);

        $impresora->setTextSize(1,1);
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->text("Mesa:" . $this->space . $data->mesa . $this->jump);
        $impresora->text("N int:" . $this->space . $data->movimiento . $this->jump);
        $impresora->text("Fecha:" . $this->space . date('Y-m-d H:i:s') . $this->jump);
        $impresora->text("Atendido por:" . $this->space . $data->mesero . $this->jump);
        $impresora->feed(1);
        // header

        $impresora->setTextSize(1, 2);
        $impresora->text($this->space . "PRODUCTO" . str_repeat(" ", 20) . "UNI PRECIO". $this->space. $this->space . "TOTAL" . $this->space . $this->jump);
        $impresora->text(str_repeat("-", $max_width) . $this->jump);
        $impresora->feed(1);

        // $impresora->setTextSize(1, 1);
        // foreach ($data->detalle as $key => $value) {
        //     $impresora->text($value['cantidad'] . $this->tab . $value['nombre'] . $this->jump);
        //     if(!empty($value['observacion'])){	
        //         $impresora->text($this->line . $value['observacion'] . $this->jump);
        //         $impresora->feed(1);
        //     }	
        // }

        $impresora->setTextSize(1, 2);
        $impresora->text(str_repeat("_", $max_width) . $this->jump);
        $impresora->feed(1);

        $impresora->setTextSize(1,1);
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->text("Total sin prop." . $this->space . $data->totales[0]['totalsinprop'] . $this->jump);
        $impresora->text("TOTAL:" . $this->space . $data->totales[0]['total'] . $this->jump);
        $impresora->text("Propina sugerida:" . $this->space . $data->totales[0]['propina'] . $this->jump);
        $impresora->text("Total con prop." . $this->space . $data->totales[0]['totalconprop'] . $this->jump);
        $impresora->feed(1);



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
        $impresora->feed(1);

        $impresora->setTextSize(1,1);
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->text("Mesa:" . $this->space . $data->mesa . $this->jump);
        $impresora->text("N int:" . $this->space . $data->movimiento . $this->jump);
        $impresora->text("Fecha:" . $this->space . date('Y-m-d H:i:s') . $this->jump);
        $impresora->text("Atendido por:" . $this->space . $data->mesero . $this->jump);
        $impresora->feed(1);

        $impresora->setTextSize(1, 2);
        $impresora->text("DESCRIPCION DEL PRODUCTO:" . $this->jump);
        $impresora->feed(1);

        $impresora->setTextSize(1, 1);
        foreach ($data->detalle as $key => $value) {
            $impresora->text($value['cantidad'] . $this->tab . $value['nombre'] . $this->jump);
            if(!empty($value['observacion'])){	
                $impresora->text($this->line . $value['observacion'] . $this->jump);
                $impresora->feed(1);
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
        $impresora->feed(1);

        $impresora->setTextSize(1,1);
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->text("Mesa:" . $this->space . $data->mesa . $this->jump);
        $impresora->text("N int:" . $this->space . $data->movimiento . $this->jump);
        $impresora->text("Fecha:" . $this->space . date('Y-m-d H:i:s') . $this->jump);
        $impresora->text("Atendido por:" . $this->space . $data->mesero . $this->jump);
        $impresora->feed(1);

        $impresora->setTextSize(1, 2);
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->text("TICKET VALIDO COMO HAPPY" . $this->jump);
        $impresora->feed(1);

        $impresora->setTextSize(1, 1);
        foreach ($data->detalle as $key => $value) {
            $impresora->text($value['nombre'] . $this->jump);
        }

        $impresora->feed(1);
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

}