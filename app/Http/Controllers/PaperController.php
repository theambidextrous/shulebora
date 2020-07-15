<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Subject;
use App\Tsubject;
use App\Paper;
use Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;

class PaperController extends Controller
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
        return view('admin_papers')->with([
            'papers' => $this->paper_list(),
            'subjects' => $this->subject_list(),
            'teachers' => $this->teacher_list()
        ]);
    }
    public function teacher_index()
    {
        if(!Auth::user()->is_teacher){
            abort(404); 
        }
        return view('teacher_papers')->with([
            'papers' => $this->teacher_paper_list(),
            'subjects' => $this->teacher_subject_list()
        ]);
    }
    public function show($id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        $paper = Paper::find($id)->toArray();
        return view('admin_papers_update')->with([
            'paper' => $paper,
            'papers' => $this->paper_list(),
            'subjects' => $this->subject_list(),
            'teachers' => $this->teacher_list()
        ]);
    }
    public function teacher_show($id)
    {
        if(!Auth::user()->is_teacher){
            abort(404); 
        }
        $paper = Paper::where('id', $id)->where('created_by', Auth::user()->id)->first()->toArray();
        return view('teacher_papers_update')->with([
            'paper' => $paper,
            'papers' => $this->teacher_paper_list(),
            'subjects' => $this->teacher_subject_list()
        ]);
    }
    public function create(Request $request)
    {
        $file_uuid = (string) Str::uuid();
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'title' => 'string|required',
                'subject' => 'string|required|not_in:nn',
                'teacher' => 'string|required|not_in:nn',
                'category' => 'string|required|not_in:nn'
            ])->validate();
            $input = $request->all();
            $input['is_paid'] = false;
            $input['is_active'] = true;
            if($input['category'] == 'PAID'){
                $input['is_paid'] = true;
            }
            $input['created_by'] = $input['teacher'];
            if( !$request->hasfile('file_content') ){
                return back()->with([
                    'flag' => 3,
                    'msg' => 'You must upload a file',
                    'papers' => $this->paper_list(),
                    'subjects' => $this->subject_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            $file_content = $request->file('file_content');
            $file_content_name = $file_uuid . $file_content->getClientOriginalName();
            Storage::disk('local')
                ->putFileAs('cls/trt/content', $file_content, $file_content_name);
            $input['file_content'] = $file_content_name;
            $paper = Paper::create($input);
            if($paper){
                return view('admin_papers')->with([
                    'flag' => 1,
                    'papers' => $this->paper_list(),
                    'msg' => 'paper created successfully',
                    'subjects' => $this->subject_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            return view('admin_papers')->with([
                'flag' => 2,
                'papers' => $this->paper_list(),
                'msg' => 'paper creation failed',
                'subjects' => $this->subject_list(),
                'teachers' => $this->teacher_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_papers')->with([
                'flag' => 3,
                'papers' => $this->paper_list(),
                'msg' => 'Databse error. Most likely entry already exists',
                'subjects' => $this->subject_list(),
                'teachers' => $this->teacher_list()
            ]);
          }
    }
    public function teacher_create(Request $request)
    {
        $file_uuid = (string) Str::uuid();
        if(!Auth::user()->is_teacher){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'title' => 'string|required',
                'subject' => 'string|required|not_in:nn',
                'category' => 'string|required|not_in:nn'
            ])->validate();
            $input = $request->all();
            $input['is_paid'] = false;
            $input['is_active'] = true;
            if($input['category'] == 'PAID'){
                $input['is_paid'] = true;
            }
            $input['created_by'] = Auth::user()->id;
            if( !$request->hasfile('file_content') ){
                return back()->with([
                    'flag' => 3,
                    'msg' => 'You must upload a file',
                    'papers' => $this->teacher_paper_list(),
                    'subjects' => $this->teacher_subject_list()
                ]);
            }
            $file_content = $request->file('file_content');
            $file_content_name = $file_uuid . $file_content->getClientOriginalName();
            Storage::disk('local')
                ->putFileAs('cls/trt/content', $file_content, $file_content_name);
            $input['file_content'] = $file_content_name;
            $paper = Paper::create($input);
            if($paper){
                return view('teacher_papers')->with([
                    'flag' => 1,
                    'msg' => 'paper created successfully',
                    'papers' => $this->teacher_paper_list(),
                    'subjects' => $this->teacher_subject_list()
                ]);
            }
            return view('admin_papers')->with([
                'flag' => 2,
                'msg' => 'paper creation failed',
                'papers' => $this->teacher_paper_list(),
                'subjects' => $this->teacher_subject_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_papers')->with([
                'flag' => 3,
                'msg' => 'Databse error. Most likely entry already exists',
                'papers' => $this->teacher_paper_list(),
                'subjects' => $this->teacher_subject_list()
            ]);
          }
    }
    public function update(Request $request, $id)
    {
        $file_uuid = (string) Str::uuid();
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'title' => 'string|required',
                'subject' => 'string|required|not_in:nn',
                'teacher' => 'string|required|not_in:nn',
                'category' => 'string|required|not_in:nn'
            ])->validate();
            $input = $request->all();
            $input['is_paid'] = false;
            $input['is_active'] = true;
            if($input['category'] == 'PAID'){
                $input['is_paid'] = true;
            }
            $input['created_by'] = $input['teacher'];
            if( $request->hasfile('file_content') ){
                $file_content = $request->file('file_content');
                $file_content_name = $file_uuid . $file_content->getClientOriginalName();
                Storage::disk('local')
                    ->putFileAs('cls/trt/content', $file_content, $file_content_name);
                $input['file_content'] = $file_content_name;
            }
            $paper = Paper::find($id)->update($input);
            if($paper){
                return back()->with([
                    'flag' => 1,
                    'papers' => $this->paper_list(),
                    'msg' => 'paper updated successfully',
                    'subjects' => $this->subject_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            return back()->with([
                'flag' => 2,
                'papers' => $this->paper_list(),
                'msg' => 'paper update failed',
                'subjects' => $this->subject_list(),
                'teachers' => $this->teacher_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return back()->with([
                'flag' => 3,
                'papers' => $this->paper_list(),
                'msg' => 'Databse error. Most likely entry already exists',
                'subjects' => $this->subject_list(),
                'teachers' => $this->teacher_list()
            ]);
          }
    }
    public function teacher_update(Request $request, $id)
    {
        $file_uuid = (string) Str::uuid();
        if(!Auth::user()->is_teacher){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'title' => 'string|required',
                'subject' => 'string|required|not_in:nn',
                'category' => 'string|required|not_in:nn'
            ])->validate();
            $input = $request->all();
            $input['is_paid'] = false;
            $input['is_active'] = true;
            if($input['category'] == 'PAID'){
                $input['is_paid'] = true;
            }
            $input['created_by'] = Auth::user()->id;
            if( $request->hasfile('file_content') ){
                $file_content = $request->file('file_content');
                $file_content_name = $file_uuid . $file_content->getClientOriginalName();
                Storage::disk('local')
                    ->putFileAs('cls/trt/content', $file_content, $file_content_name);
                $input['file_content'] = $file_content_name;
            }
            $paper = Paper::find($id)->update($input);
            if($paper){
                return back()->with([
                    'flag' => 1,
                    'msg' => 'paper updated successfully',
                    'papers' => $this->teacher_paper_list(),
                    'subjects' => $this->teacher_subject_list()
                ]);
            }
            return back()->with([
                'flag' => 2,
                'msg' => 'paper update failed',
                'papers' => $this->teacher_paper_list(),
                'subjects' => $this->teacher_subject_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return back()->with([
                'flag' => 3,
                'msg' => 'Database error. Most likely entry already exists',
                'papers' => $this->teacher_paper_list(),
                'subjects' => $this->teacher_subject_list()
            ]);
          }
    }
    public function stream($f_str)
    {
        $filename = ('app/cls/trt/content/'.$f_str);
        return response()->download(storage_path($filename), null, [], null);
    }
    protected function paper_list()
    {
        return Paper::where('is_active', true)->get()->toArray();
    }
    protected function teacher_paper_list()
    {
        return Paper::where('is_active', true)
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


