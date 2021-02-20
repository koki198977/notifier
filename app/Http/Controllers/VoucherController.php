<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Response;
use App\Services\PrinterService;

class VoucherController extends Controller
{
    protected $printService;

    public function __construct(PrinterService $service)
    {
        $this->printService = $service;
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
        return $this->printService->printSII($request);
    }


}
