<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Package;
use Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;

class PackageController extends Controller
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
        return view('admin_packages')->with([
            'packages' => $this->package_list()
        ]);
    }
    public function show($id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        $package = Package::find($id)->toArray();
        return view('admin_packages_update')->with([
            'package' => $package,
            'packages' => $this->package_list()
        ]);
    }
    public function create(Request $request)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'name' => 'string|required',
                'description' => 'string|required',
                'addons' => 'string|required',
                'price' => 'string|required',
                'max_usage' => 'integer|required'
            ])->validate();
            $input = $request->all();
            $input['name'] = strtoupper($input['name']);
            $input['addons'] = strtoupper($input['addons']);
            $input['is_active'] = true;
            $package = Package::create($input);
            if($package){
                return view('admin_packages')->with([
                    'flag' => 1,
                    'packages' => $this->package_list(),
                    'msg' => 'package created successfully'
                ]);
            }
            return view('admin_packages')->with([
                'flag' => 2,
                'packages' => $this->package_list(),
                'msg' => 'package creation failed'
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_packages')->with([
                'flag' => 3,
                'packages' => $this->package_list(),
                'msg' => 'Databse error. Most likely entry already exists'
            ]);
          }
    }
    public function update(Request $request, $id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'name' => 'string|required',
                'description' => 'string|required',
                'addons' => 'string|required',
                'price' => 'string|required',
                'max_usage' => 'integer|required'
            ])->validate();
            $input = $request->all();
            $input['is_active'] = true;
            $package = Package::find($id)->update($input);
            if($package){
                return back()->with([
                    'flag' => 1,
                    'packages' => $this->package_list(),
                    'msg' => 'package updated successfully'
                ]);
            }
            return back()->with([
                'flag' => 2,
                'packages' => $this->package_list(),
                'msg' => 'package update failed'
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return back()->with([
                'flag' => 3,
                'packages' => $this->package_list(),
                'msg' => 'Databse error. Most likely entry already exists'
            ]);
          }
    }
    protected function package_list()
    {
        return Package::where('is_active', true)->get()->toArray();
    }
}


