<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;
use BigFish\PDF417\Renderers\SvgRenderer;

class ExampleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $message = $request->message;
        broadcast( new \App\Events\ExampleEvent($message));
        return 'send => [' . $message . ']';
    }

    public function test(){
        $pdf417 = new PDF417();
        $data = $pdf417->encode("<note><to>Tove</to><from>Jani</from><heading>Reminder</heading><body>Don't forget me this weekend!</body></note>");

        // Create a PNG image, red on green background, extra big
        $renderer = new ImageRenderer();

        $image = $renderer->render($data);
        $image->save('hovercraft.png');
    }

}
