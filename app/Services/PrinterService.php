<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class PrinterService
{

    protected $jump = '\n';
    protected $tab = '\t';

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

    public function printTicket($data){
        return $data->impresora;
        $connector = new WindowsPrintConnector($data->impresora);
        $impresora = new Printer($connector);

        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->setTextSize(2,2);
        $impresora->text("TURQUESA".$jump);
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->text("Mesa:" . $data->mesa . $jump);
        $impresora->setTextSize(1,1);
        $impresora->text("N int:" . $data->movimiento . $jump);
        $impresora->text("Fecha:" . date('Y-m-d H:i:s') . $jump);
        $impresora->text("Atendido por:" . $data->mesero . $jump);
        $impresora->setLineSpacing(1);

        $impresora->setTextSize(2, 1);
        $impresora->text("DESCRIPCIÓN DEL PRODUCTO:" . $jump);
        $impresora->setTextSize(1, 1);
        foreach ($data->detalle as $key => $value) {
            $impresora->text($value->cantidad . $tab . $value->nombre . $jump);
            $impresora->text($value->observacion . $jump);
        }
        $impresora->setLineSpacing(2);
        $impresora->setTextSize(1, 1);
        $impresora->text(env('LARAVEL_ECHO_HOST'));

        $impresora->feed(3);
        $impresora->cut();
        $impresora->close();
    }

}