<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Timetable;
use App\Subject;
use Illuminate\Support\Facades\Auth;
use Validator;

class TimetableController extends Controller
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
        return view('admin_timetables')->with([
            'timetables' => $this->timetable_list(),
            'subjects' => $this->subject_list(),
            'teachers' => $this->teacher_list()
        ]);
    }
    public function show($id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        $timetable = Timetable::find($id)->toArray();
        return view('admin_timetables_update')->with([
            'timetable' => $timetable,
            'subjects' => $this->subject_list(),
            'teachers' => $this->teacher_list()
        ]);
    }
    public function create(Request $request)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'subject' => 'string|required',
                'teacher' => 'string|required',
                'date' => 'string|required',
                'time' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['created_by'] = Auth::user()->id;
            $input['is_active'] = true;
            $timetable = Timetable::create($input);
            if($timetable){
                return view('admin_timetables')->with([
                    'flag' => 1,
                    'timetables' => $this->timetable_list(),
                    'msg' => 'Timetable created successfully',
                    'subjects' => $this->subject_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            return view('admin_timetables')->with([
                'flag' => 2,
                'timetables' => $this->timetable_list(),
                'msg' => 'Timetable creation failed',
                'subjects' => $this->subject_list(),
                'teachers' => $this->teacher_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_timetables')->with([
                'flag' => 3,
                'timetables' => $this->timetable_list(),
                'msg' => 'Databse error. Most likely entry already exists',
                'subjects' => $this->subject_list(),
                'teachers' => $this->teacher_list()
            ]);
          }
    }
    public function update(Request $request, $id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            $this_timetable = Timetable::find($id)->toArray();
            Validator::make($request->all(),[
                'subject' => 'string|required',
                'teacher' => 'string|required',
                'date' => 'string|required',
                'time' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['created_by'] = Auth::user()->id;
            $timetable = Timetable::find($id);
            if(!$timetable){
                return back()->with([
                    'flag' => 2,
                    'timetable' => $this_timetable,
                    'timetables' => $this->timetable_list(),
                    'msg' => 'Item not fond',
                    'subjects' => $this->subject_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            $timetable->created_by = $input['created_by'];
            $timetable->subject = $input['subject'];
            $timetable->teacher = $input['teacher'];
            $timetable->date = $input['date'];
            $timetable->time = $input['time'];
            $timetable->save();
            return view('admin_timetables')->with([
                'flag' => 1,
                'msg' => 'Timetable updated!',
                'timetables' => $this->timetable_list(),
                'subjects' => $this->subject_list(),
                'teachers' => $this->teacher_list()
            ]);

        } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_timetables_update')->with([
                'flag' => 3,
                'timetable' => $this_timetable,
                'timetables' => $this->timetable_list(),
                'subjects' => $this->subject_list(),
                'msg' => 'Database error',
                'subjects' => $this->subject_list(),
                'teachers' => $this->teacher_list()
            ]);
        }
    }
    protected function timetable_list()
    {
        return Timetable::where('is_active', true)->get()->toArray();
    }
    protected function subject_list()
    {
        return Subject::where('is_active', true)->get()->toArray();
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

