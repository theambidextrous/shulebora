<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        return view('admin_home');
    }

    public function teacher()
    {
        if(!Auth::user()->is_teacher){
            abort(404); 
        }
        return view('teacher_home');
    }
    public function learner()
    {
        if(!Auth::user()->is_learner){
            abort(404); 
        }
        if(!Auth::user()->is_paid){
            if(!Session::has('order')){
                Session::put('order', $this->createCode(6));
            }
            return redirect(route('buy')); 
        }
        return view('learner_home');
    }
    protected function createCode($length = 20) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
