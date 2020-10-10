<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Paylogs;

class PaymentController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        $paylogs = Paylogs::where('paystring', 'like', '%"ResultCode":0%')
            ->orWhere('method', 'Mpesa c2b')
            ->orderBy('created_at', 'desc')->get()->toArray();
        
            return view('admin_payments')->with([
                'brd_title' => 'MPESA Payments',
                'p_title' => 'Successful MPESA Payments',
                'title' => 'Successful MPESA Payments',
                'payments' => $paylogs
            ]);
    }
    public function failed()
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        $paylogs = Paylogs::where('paystring', 'not like', '%"ResultCode":0%')
            ->where('method', '!=', 'Mpesa c2b')
            ->orderBy('created_at', 'desc')->get()->toArray();
        
            return view('admin_payments')->with([
                'brd_title' => 'Failed Payments',
                'p_title' => 'Failed MPESA Payments',
                'title' => 'Failed MPESA Payments',
                'payments' => $paylogs
            ]);
    }

}
