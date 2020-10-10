<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Gclass;
use App\Group;
use Illuminate\Support\Facades\Auth;
use Validator;

class GclassController extends Controller
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
        return view('admin_classes')->with([
            'classes' => $this->class_list(),
            'groups' => $this->group_list()
        ]);
    }
    public function show($id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        $form = Gclass::find($id)->toArray();
        return view('admin_classes_update')->with([
            'class' => $form,
            'groups' => $this->group_list()
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
                'group' => 'string|required',
                'alias' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['name'] = trim(strtoupper($request->get('name')));
            $input['alias'] = trim(strtoupper($request->get('alias')));
            $form = Gclass::create($input);
            if($form){
                return view('admin_classes')->with([
                    'flag' => 1,
                    'classes' => $this->class_list(),
                    'msg' => 'Forms created successfully',
                    'groups' => $this->group_list()
                ]);
            }
            return view('admin_classes')->with([
                'flag' => 2,
                'classes' => $this->class_list(),
                'msg' => 'Forms creation failed',
                'groups' => $this->group_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_classes')->with([
                'flag' => 3,
                'classes' => $this->class_list(),
                'msg' => 'Databse error. Most likely entry already exists',
                'groups' => $this->group_list()
            ]);
          }
    }
    public function update(Request $request, $id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            $this_form = Gclass::find($id)->toArray();
            Validator::make($request->all(),[
                'name' => 'string|required',
                'group' => 'string|required',
                'alias' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['name'] = trim(strtoupper($request->get('name')));
            $input['alias'] = trim(strtoupper($request->get('alias')));
            $form = Gclass::find($id);
            if(!$form){
                return view('admin_classes_update')->with([
                    'flag' => 2,
                    'class' => $this_form,
                    'classes' => $this->class_list(),
                    'msg' => 'Item not fond',
                    'groups' => $this->group_list()
                ]);
            }
            $form->name = $input['name'];
            $form->alias = $input['alias'];
            $form->group = $input['group'];
            $form->save();
            return view('admin_classes')->with([
                'flag' => 1,
                'classes' => $this->class_list(),
                'msg' => 'Forms updated!',
                'groups' => $this->group_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_classes_update')->with([
                'flag' => 3,
                'class' => $this_form,
                'classes' => $this->class_list(),
                'msg' => 'Database error',
                'groups' => $this->group_list()
            ]);
          }
    }
    protected function class_list()
    {
        return Gclass::where('is_active', true)->get()->toArray();
    }
    protected function group_list()
    {
        return Group::where('is_active', true)->get()->toArray();
    }
}

