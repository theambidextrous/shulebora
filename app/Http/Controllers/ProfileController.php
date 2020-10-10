<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;

class ProfileController extends Controller
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
        return view('general_profile')->with([
            'user' => Auth::user()
        ]);
    }
    public function update(Request $request, $id)
    {
        try { 
            Validator::make($request->all(),[
                'title' => 'string|required',
                'subject' => 'string|required|not_in:nn',
                'teacher' => 'string|required|not_in:nn',
                'category' => 'string|required|not_in:nn'
            ])->validate();
            $input = $request->all();
        } catch(\Illuminate\Database\QueryException $ex){ 
            return back()->with([
                'flag' => 3,
                'msg' => 'Databse error. Most likely entry already exists'
            ]);
        }
    }
}


