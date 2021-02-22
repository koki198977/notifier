<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GetXMLService
{

    public function __construct()
    {
        //
    }

    public function getXML($data){
        $disk = Storage::disk('sftp');
        $xml = $disk->get($data->url);
        $xml_to_string = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml_to_string);
        $array = json_decode($json,TRUE);
        return array('xml' => $xml, 'json' => $array);
    }
}