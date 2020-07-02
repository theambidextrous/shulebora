<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Form;
use App\Group;
use Illuminate\Support\Facades\Auth;
use Validator;

class FormController extends Controller
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
        return view('admin_forms')->with([
            'forms' => $this->form_list(),
            'groups' => $this->group_list()
        ]);
    }
    public function show($id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        $form = Form::find($id)->toArray();
        return view('admin_forms_update')->with([
            'form' => $form,
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
            $form = Form::create($input);
            if($form){
                return view('admin_forms')->with([
                    'flag' => 1,
                    'forms' => $this->form_list(),
                    'msg' => 'Forms created successfully',
                    'groups' => $this->group_list()
                ]);
            }
            return view('admin_forms')->with([
                'flag' => 2,
                'forms' => $this->form_list(),
                'msg' => 'Forms creation failed',
                'groups' => $this->group_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_forms')->with([
                'flag' => 3,
                'forms' => $this->form_list(),
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
            $this_form = Form::find($id)->toArray();
            Validator::make($request->all(),[
                'name' => 'string|required',
                'group' => 'string|required',
                'alias' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['name'] = trim(strtoupper($request->get('name')));
            $input['alias'] = trim(strtoupper($request->get('alias')));
            $form = Form::find($id);
            if(!$form){
                return view('admin_forms_update')->with([
                    'flag' => 2,
                    'form' => $this_form,
                    'forms' => $this->form_list(),
                    'msg' => 'Item not fond',
                    'groups' => $this->group_list()
                ]);
            }
            $form->name = $input['name'];
            $form->alias = $input['alias'];
            $form->group = $input['group'];
            $form->save();
            return view('admin_forms')->with([
                'flag' => 1,
                'forms' => $this->form_list(),
                'msg' => 'Forms updated!',
                'groups' => $this->group_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_forms_update')->with([
                'flag' => 3,
                'form' => $this_form,
                'forms' => $this->form_list(),
                'msg' => 'Database error',
                'groups' => $this->group_list()
            ]);
          }
    }
    protected function form_list()
    {
        return Form::where('is_active', true)->get()->toArray();
    }
    protected function group_list()
    {
        return Group::where('is_active', true)->get()->toArray();
    }
}
