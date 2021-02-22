<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Response;
use App\Services\PrinterService;
use App\Services\GetXMLService;
use App\Services\Pdf417Service;

class VoucherController extends Controller
{
    protected $printService;
    protected $xmlService;
    protected $pdf417Service;

    public function __construct(PrinterService $printService, GetXMLService $xmlService, Pdf417Service $pdf417Service)
    {
        $this->printService = $printService;
        $this->xmlService = $xmlService;
        $this->pdf417Service = $pdf417Service;
    }

    public function index(Request $request)
    {
        return $this->print();
    }

    public function preCuenta(Request $request)
    {
        return  $this->printService->printPreCuenta($request);
    }

    public function solicitaTicket(Request $request)
    {
        return $this->printService->printTicket($request);
    }

    public function solicitaHappy(Request $request)
    {
        return $this->printService->printHappy($request);
    }

    public function solicitaElectronica(Request $request)
    {
        $data = $this->xmlService->getXML($request);
        return $data['json'];
        $this->pdf417Service->createPdf417("<note><to>Tove</to><from>Jani</from><heading>Reminder</heading><body>Don't forget me this weekend!</body></note>");
        // $this->printService->printSII($request);
        return;
    }


}
