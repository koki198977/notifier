<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;
use BigFish\PDF417\Renderers\SvgRenderer;

class Pdf417Service
{

    public function __construct()
    {
        //
    }

    public function createPdf417($data){
        $pdf417 = new PDF417();
        $data = $pdf417->encode($data);
        $renderer = new ImageRenderer();

        $image = $renderer->render($data);
        $image->save('pdf417code.png');
        return;
    }
}