<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $time = date('Y-m-d H:i:s');
        $file = Storage::disk('local')->put($time.'.txt', $request->data);
        return $file;
    }

}
