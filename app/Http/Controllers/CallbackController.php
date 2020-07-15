<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CallbackController extends Controller
{
    /**
     * Mpesa call backs 
     */
    public function c2b(Request $r)
    {
        $data = $r->getContent();
        if(!$data)
        {
            return response(['ResultCode' => '0', 'ResultDesc' => 'Accepted Successfully']);
        }
        else
        {
            Storage::disk('local')->prepend('mpesalog_c2b.log', $data);
        }
    }
    public function express(Request $r)
    {
        $data = $r->getContent();
        if(!$data)
        {
            return response(['ResultCode' => '0', 'ResultDesc' => 'Accepted Successfully']);
        }
        else
        {
            Storage::disk('local')->prepend('mpesalog_exp.log', $data);
        }
    }
}
