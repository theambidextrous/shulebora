<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Gclass;
use App\Form;
use App\Subject;
use App\Tsubject;
use App\Curriculum;
use Illuminate\Support\Facades\Auth;
use Validator;

class SubjectController extends Controller
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
        return view('admin_subjects')->with([
            'subjects' => $this->subject_list(),
            'forms' => $this->form_list(),
            'classes' => $this->class_list()
        ]);
    }
    public function teacher_index()
    {
        if(!Auth::user()->is_teacher){
            abort(404); 
        }
        return view('teacher_subjects')->with([
            'subjects' => $this->teacher_subject_list(),
            'forms' => $this->form_list(),
            'classes' => $this->class_list()
        ]);
    }
    public function show($id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        $subject = Subject::find($id)->toArray();
        return view('admin_subjects_update')->with([
            'subject' => $subject,
            'forms' => $this->form_list(),
            'classes' => $this->class_list()
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
                'class_form' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['name'] = trim(strtoupper($request->get('name')));
            $w_str = explode('~', $request->get('class_form'));
            $input['form_or_class'] = trim($w_str[1]);
            $input['is_what'] = 1;
            if($w_str[0] == 'is_h'){
                $input['is_what'] = 2;
            }
            $subject = Subject::create($input);
            if($subject){
                return view('admin_subjects')->with([
                    'flag' => 1,
                    'subjects' => $this->subject_list(),
                    'msg' => 'Subject created successfully',
                    'forms' => $this->form_list(),
                    'classes' => $this->class_list()
                ]);
            }
            return view('admin_subjects')->with([
                'flag' => 2,
                'subjects' => $this->subject_list(),
                'msg' => 'Subject creation failed',
                'forms' => $this->form_list(),
                'classes' => $this->class_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_subjects')->with([
                'flag' => 3,
                'subjects' => $this->subject_list(),
                'msg' => 'Databse error. Most likely entry already exists',
                'forms' => $this->form_list(),
                'classes' => $this->class_list()
            ]);
          }
    }
    public function update(Request $request, $id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            $this_subject = Subject::find($id)->toArray();
            Validator::make($request->all(),[
                'name' => 'string|required',
                'class_form' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['name'] = trim(strtoupper($request->get('name')));
            $w_str = explode('~', $request->get('class_form'));
            $input['form_or_class'] = trim($w_str[1]);
            $input['is_what'] = 1;
            if($w_str[0] == 'is_h'){
                $input['is_what'] = 2;
            }
            $subject = Subject::find($id);
            if(!$subject){
                return view('admin_subjects_update')->with([
                    'flag' => 2,
                    'subject' => $this_subject,
                    'subjects' => $this->subject_list(),
                    'msg' => 'Item not fond',
                    'forms' => $this->form_list(),
                    'classes' => $this->class_list()
                ]);
            }
            $subject->name = $input['name'];
            $subject->form_or_class = $input['form_or_class'];
            $subject->is_what = $input['is_what'];
            $subject->save();
            return view('admin_subjects')->with([
                'flag' => 1,
                'subjects' => $this->subject_list(),
                'msg' => 'Subject updated!',
                'forms' => $this->form_list(),
                'classes' => $this->class_list()
            ]);

        } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_subjects_update')->with([
                'flag' => 3,
                'subject' => $this_subject,
                'subjects' => $this->subject_list(),
                'msg' => 'Database error',
                'forms' => $this->form_list(),
                'classes' => $this->class_list()
            ]);
        }
    }
    public function update_topics(Request $request, $subject)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            $this_subject = Subject::find($subject)->toArray();
            Validator::make($request->all(),[
                'topic' => 'string|required',
                'number' => 'integer|required',
                'required_lessons' => 'integer|required'
            ])->validate();
            $input = $request->all();
            $input['topic'] = trim(strtoupper($request->get('topic')));
            $input['subject'] = $subject;
            $curriculum = Curriculum::create($input);
            return back()->with([
                'flag' => 1,
                'subject' => $this_subject,
                'subjects' => $this->subject_list(),
                'msg' => 'Topic added!',
                'forms' => $this->form_list(),
                'classes' => $this->class_list()
            ]);
        } catch(\Illuminate\Database\QueryException $ex){ 
            return back()->with([
                'flag' => 3,
                'subject' => $this_subject,
                'subjects' => $this->subject_list(),
                'msg' => 'Database error' . $ex->getMessage(),
                'forms' => $this->form_list(),
                'classes' => $this->class_list()
            ]);
        }
    }
    public function drop_topic($topic)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            Curriculum::find($topic)->delete();
            return back()->with([
                'flag' => 1,
                'subjects' => $this->subject_list(),
                'msg' => 'Topic dropped!',
                'forms' => $this->form_list(),
                'classes' => $this->class_list()
            ]);
        }catch(\Illuminate\Database\QueryException $ex){ 
            return back()->with([
                'flag' => 3,
                'subjects' => $this->subject_list(),
                'msg' => 'Database error' . $ex->getMessage(),
                'forms' => $this->form_list(),
                'classes' => $this->class_list()
            ]);
        }
    }
    public function high()
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        return view('admin_subjects')->with([
            'p_title' => 'High School Subjects',
            'subjects' => $this->subject_list(2),
            'msg' => 'Topic dropped!',
            'forms' => $this->form_list(),
            'classes' => $this->class_list()
        ]);
    }
    public function prim()
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        return view('admin_subjects')->with([
            'p_title' => 'Primary School Subjects',
            'subjects' => $this->subject_list(1),
            'msg' => 'Topic dropped!',
            'forms' => $this->form_list(),
            'classes' => $this->class_list()
        ]);
    }
    protected function subject_list($is_what = 0)
    {
        if($is_what > 0){
            return Subject::where('is_active', true)->where('is_what', $is_what)->get()->toArray();
        }
        return Subject::where('is_active', true)->get()->toArray();
    }
    protected function teacher_subject_list($is_what = 0)
    {
        $assigned_sub = Tsubject::select('subject')
            ->where('teacher', Auth::user()->id)->get()->toArray();
        return Subject::where('is_active', true)
            ->whereIn('id', $assigned_sub)->get()->toArray();
    }
    protected function form_list()
    {
        return Form::where('is_active', true)->get()->toArray();
    }
    protected function class_list()
    {
        return Gclass::where('is_active', true)->get()->toArray();
    }
}

