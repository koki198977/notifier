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
        $pdf417->setColumns(9);
        
        $ted = $this->clearXML($data);

        $pdf = $pdf417->encode($ted);
        $renderer = new ImageRenderer([ 'scale' => 10, 'ratio' => 1]);

        $image = $renderer->render($pdf);
        $image->save('pdf417code.png');
        return;
    }

    private function clearXML($data){
        $ted = strstr($data,'<TED');
        $toFind = '</TED>';
        $pos = strpos($ted, $toFind);
        $ted = substr($ted, 0, $pos + strlen($toFind));
        $ted = mb_detect_encoding($ted, ['UTF-8', 'ISO-8859-1']) != 'ISO-8859-1' ? utf8_decode($ted) : $ted;

        return $ted;
    }
}