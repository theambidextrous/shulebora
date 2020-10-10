<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Group;
use Illuminate\Support\Facades\Auth;
use Validator;

class GroupController extends Controller
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
        return view('admin_groups')->with([
            'groups' => $this->group_list()
        ]);
    }
    public function show($id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        $group = Group::find($id)->toArray();
        return view('admin_groups_update')->with([
            'group' => $group
        ]);
    }
    public function create(Request $request)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'name' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['name'] = trim(strtoupper($request->get('name')));
            $group = Group::create($input);
            if($group){
                return view('admin_groups')->with([
                    'flag' => 1,
                    'groups' => $this->group_list(),
                    'msg' => 'Group created successfully'
                ]);
            }
            return view('admin_groups')->with([
                'flag' => 2,
                'groups' => $this->group_list(),
                'msg' => 'Group creation failed'
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_groups')->with([
                'flag' => 3,
                'groups' => $this->group_list(),
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
            $this_group = Group::find($id)->toArray();
            Validator::make($request->all(),[
                'name' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['name'] = trim(strtoupper($request->get('name')));
            $group = Group::find($id);
            if(!$group){
                return view('admin_groups_update')->with([
                    'flag' => 2,
                    'group' => $this_group,
                    'groups' => $this->group_list(),
                    'msg' => 'Item not fond'
                ]);
            }
            $group->name = $input['name'];
            $group->save();
            return view('admin_groups')->with([
                'flag' => 1,
                'groups' => $this->group_list(),
                'msg' => 'Group updated!'
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_groups_update')->with([
                'flag' => 3,
                'group' => $this_group,
                'groups' => $this->group_list(),
                'msg' => 'Database error'
            ]);
          }
    }
    protected function group_list()
    {
        return Group::where('is_active', true)->get()->toArray();
    }
}
