<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StreamController extends Controller
{
    public function stream($f_str)
    {
        $filename = ('app/cls/trt/content/'.$f_str);
        return response()->download(storage_path($filename), null, [], null);
    }
}
