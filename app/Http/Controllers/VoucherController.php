<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Response;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->print();
    }

    public function preCuenta(Request $request)
    {
        return $request->impresora;
        return $this->printPreCuenta($request);
    }



    private function print(){
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

    private function printPreCuenta($data){
        $connector = new WindowsPrintConnector($data->impresora);
        $impresora = new Printer($connector);

        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->setTextSize(2, 2);
        $impresora->text("Imprimiendo\n");
        $impresora->text("ticket\n");
        $impresora->text("desde\n");
        $impresora->text("Laravel\n");
        $impresora->setTextSize(1, 1);
        $impresora->text(env('LARAVEL_ECHO_HOST'));
        $impresora->feed(5);
        $impresora->cut();
        $impresora->close();
    }


}
