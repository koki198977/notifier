<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

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

}
