<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Subject;
use App\Tsubject;
use App\Assignment;
use Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;

class AssignmentController extends Controller
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
        return view('admin_assignments')->with([
            'assignments' => $this->assignment_list(),
            'subjects' => $this->subject_list(),
            'teachers' => $this->teacher_list()
        ]);
    }
    public function teacher_index()
    {
        if(!Auth::user()->is_teacher){
            abort(404); 
        }
        return view('teachers_assignments')->with([
            'assignments' => $this->teacher_assignment_list(),
            'subjects' => $this->teacher_subject_list()
        ]);
    }
    public function show($id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        $assignment = Assignment::find($id)->toArray();
        return view('admin_assignments_update')->with([
            'assignment' => $assignment,
            'assignments' => $this->assignment_list(),
            'subjects' => $this->subject_list(),
            'teachers' => $this->teacher_list()
        ]);
    }
    public function teacher_show($id)
    {
        if(!Auth::user()->is_teacher){
            abort(404); 
        }
        $assignment = Assignment::where('id', $id)->where('created_by', Auth::user()->id)->first()->toArray();
        return view('teacher_assignments_update')->with([
            'assignment' => $assignment,
            'assignments' => $this->teacher_assignment_list(),
            'subjects' => $this->teacher_subject_list()
        ]);
    }
    public function create(Request $request)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'title' => 'string|required|not_in:nn',
                'subject' => 'string|required|not_in:nn',
                'teacher' => 'string|required|not_in:nn',
                'category' => 'string|required|not_in:nn',
                'content' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['is_paid'] = false;
            $input['is_active'] = true;
            if($input['category'] == 'PAID'){
                $input['is_paid'] = true;
            }
            $input['created_by'] = $input['teacher'];
            $assignment = Assignment::create($input);
            if($assignment){
                return view('admin_assignments')->with([
                    'flag' => 1,
                    'assignments' => $this->assignment_list(),
                    'msg' => 'assignment created successfully',
                    'subjects' => $this->subject_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            return view('admin_assignments')->with([
                'flag' => 2,
                'assignments' => $this->assignment_list(),
                'msg' => 'assignment creation failed',
                'subjects' => $this->subject_list(),
                'teachers' => $this->teacher_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_assignments')->with([
                'flag' => 3,
                'assignments' => $this->assignment_list(),
                'msg' => 'Databse error. Most likely entry already exists',
                'subjects' => $this->subject_list(),
                'teachers' => $this->teacher_list()
            ]);
          }
    }
    public function teacher_create(Request $request)
    {
        if(!Auth::user()->is_teacher){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'title' => 'string|required|not_in:nn',
                'subject' => 'string|required|not_in:nn',
                'category' => 'string|required|not_in:nn',
                'content' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['is_paid'] = false;
            $input['is_active'] = true;
            if($input['category'] == 'PAID'){
                $input['is_paid'] = true;
            }
            $input['created_by'] = Auth::user()->id;
            $assignment = Assignment::create($input);
            if($assignment){
                return view('teacher_assignments')->with([
                    'flag' => 1,
                    'msg' => 'assignment created successfully',
                    'assignments' => $this->teacher_assignment_list(),
                    'subjects' => $this->teacher_subject_list()
                ]);
            }
            return view('teacher_assignments')->with([
                'flag' => 2,
                'msg' => 'assignment creation failed',
                'assignments' => $this->teacher_assignment_list(),
                'subjects' => $this->teacher_subject_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('teacher_assignments')->with([
                'flag' => 3,
                'msg' => 'Databse error. Most likely entry already exists',
                'assignments' => $this->teacher_assignment_list(),
                'subjects' => $this->teacher_subject_list()
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
                'title' => 'string|required|not_in:nn',
                'teacher' => 'string|required|not_in:nn',
                'category' => 'string|required|not_in:nn',
                'content' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['is_paid'] = false;
            $input['is_active'] = true;
            if($input['category'] == 'PAID'){
                $input['is_paid'] = true;
            }
            $input['created_by'] = $input['teacher'];
            $assignment = Assignment::find($id)->update($input);
            if($assignment){
                return back()->with([
                    'flag' => 1,
                    'assignments' => $this->assignment_list(),
                    'msg' => 'assignment update successfully',
                    'subjects' => $this->subject_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            return back()->with([
                'flag' => 2,
                'assignments' => $this->assignment_list(),
                'msg' => 'assignment update failed',
                'subjects' => $this->subject_list(),
                'teachers' => $this->teacher_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return back()->with([
                'flag' => 3,
                'assignments' => $this->assignment_list(),
                'msg' => 'Databse error. Most likely entry already exists',
                'subjects' => $this->subject_list(),
                'teachers' => $this->teacher_list()
            ]);
          }
    }
    public function teacher_update(Request $request, $id)
    {
        if(!Auth::user()->is_teacher){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'title' => 'string|required|not_in:nn',
                'category' => 'string|required|not_in:nn',
                'content' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['is_paid'] = false;
            $input['is_active'] = true;
            if($input['category'] == 'PAID'){
                $input['is_paid'] = true;
            }
            $input['created_by'] = Auth::user()->id;
            $assignment = Assignment::find($id)->update($input);
            if($assignment){
                return back()->with([
                    'flag' => 1,
                    'msg' => 'assignment update successfully',
                    'assignments' => $this->teacher_assignment_list(),
                    'subjects' => $this->teacher_subject_list()
                ]);
            }
            return back()->with([
                'flag' => 2,
                'msg' => 'assignment update failed',
                'assignments' => $this->teacher_assignment_list(),
                'subjects' => $this->teacher_subject_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return back()->with([
                'flag' => 3,
                'msg' => 'Databse error. Most likely entry already exists',
                'assignments' => $this->teacher_assignment_list(),
                'subjects' => $this->teacher_subject_list()
            ]);
          }
    }
    public function stream($f_str)
    {
        $filename = ('app/cls/trt/content/'.$f_str);
        return response()->download(storage_path($filename), null, [], null);
    }
    protected function assignment_list()
    {
        return Assignment::where('is_active', true)->get()->toArray();
    }
    protected function teacher_assignment_list()
    {
        return Assignment::where('is_active', true)
            ->where('created_by', Auth::user()->id)->get()->toArray();
    }
    protected function subject_list()
    {
        return Subject::where('is_active', true)->get()->toArray();
    }
    protected function teacher_subject_list()
    {
        $assigned_sub = Tsubject::select('subject')
            ->where('teacher', Auth::user()->id)->get()->toArray();
        return Subject::where('is_active', true)
            ->whereIn('id', $assigned_sub)->get()->toArray();
    }
    protected function teacher_list()
    {
        return User::where('is_teacher', true)->get()->toArray();
    }
    protected function class_list()
    {
        return Gclass::where('is_active', true)->get()->toArray();
    }
}


