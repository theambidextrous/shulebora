<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Gclass;
use App\Subject;
use App\Tsubject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
/** mail */
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountCreation;
use App\Mail\AccountUpdate;

class TeacherController extends Controller
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
        return view('admin_teachers')->with([
            'teachers' => $this->teacher_list(),
            'classes' => $this->class_list(),
            'subjects' => $this->subject_list()
        ]);
    }
    public function show($id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        $teacher = User::where('id', $id)->where('is_teacher', true)->first()->toArray();
        return view('admin_teachers_update')->with([
            'teacher' => $teacher,
            'classes' => $this->class_list(),
            'subjects' => $this->subject_list()
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
                'email' => 'string|required',
                'phone' => 'string|required',
                'gender' => 'string|required',
                'subjects' => 'array|required',
                'school' => 'string|required',
                'password' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['name'] = trim(strtoupper($request->get('name')));
            $input['school'] = trim(strtoupper($request->get('school')));
            $input['gender'] = trim(strtoupper($request->get('gender')));
            $input['phone'] = $this->format_tel(trim(strtoupper($request->get('phone'))));
            $input['has_profile'] = true;
            $input['is_teacher'] = true;
            $input['is_learner'] = false;
            $input['is_active'] = true;
            $input['password'] = Hash::make($request->get('password'));
            $teacher = User::create($input);
            if($teacher){
                $t = User::where('email', $input['email'])->first();
                foreach( $input['subjects'] as $subj ):
                    $tsub = [ 'teacher' => $t->id, 'subject' => $subj ];
                    Tsubject::create($tsub);
                endforeach;
                Mail::to($t->email)
                    ->send(new AccountCreation($t, $request->get('password')));
                return view('admin_teachers')->with([
                    'flag' => 1,
                    'teachers' => $this->teacher_list(),
                    'msg' => 'Teacher created successfully',
                    'classes' => $this->class_list(),
                    'subjects' => $this->subject_list()
                ]);
            }
            return view('admin_teachers')->with([
                'flag' => 2,
                'teachers' => $this->teacher_list(),
                'msg' => 'Teacher creation failed',
                'classes' => $this->class_list(),
                'subjects' => $this->subject_list()
            ]);

          }catch(Exception $e ){
            return view('admin_teachers')->with([
                'flag' => 3,
                'teachers' => $this->teacher_list(),
                'msg' => $e->getMessage(),
                'classes' => $this->class_list(),
                'subjects' => $this->subject_list()
            ]);
          }catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_teachers')->with([
                'flag' => 3,
                'teachers' => $this->teacher_list(),
                'msg' => 'Databse error. Most likely entry already exists',
                'classes' => $this->class_list(),
                'subjects' => $this->subject_list()
            ]);
          }
    }
    public function update(Request $request, $id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            $this_teacher = User::find($id)->toArray();
            Validator::make($request->all(),[
                'name' => 'string|required',
                'email' => 'string|required',
                'phone' => 'string|required',
                'gender' => 'string|required',
                'class_form' => 'string|required',
                'school' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['name'] = trim(strtoupper($request->get('name')));
            $input['school'] = trim(strtoupper($request->get('school')));
            $input['gender'] = trim(strtoupper($request->get('gender')));
            $input['phone'] = $this->format_tel(trim(strtoupper($request->get('phone'))));
            $w_str = explode('~', $request->get('class_form'));
            $input['level'] = trim($w_str[1]);
            $input['group'] = 1;/**primary */
            if($w_str[0] == 'is_h'){
                $input['group'] = 2;/**high school/seco */
            }
            $teacher = User::find($id);
            if(!$teacher){
                    return view('admin_teachers')->with([
                        'flag' => 2,
                        'teacher' => $this_teacher,
                        'teachers' => $this->teacher_list(),
                        'msg' => 'Item not fond',
                        'classes' => $this->class_list(),
                        'subjects' => $this->subject_list()
                    ]);
            }
            $teacher->name = $input['name'];
            $teacher->email = $input['email'];
            $teacher->phone = $input['phone'];
            $teacher->email = $input['email'];
            $teacher->gender = $input['gender'];
            $teacher->group = $input['group'];
            $teacher->level = $input['level'];
            $teacher->save();
            Mail::to($teacher->email)
                ->send(new AccountUpdate($teacher));
            return view('admin_teachers_update')->with([
                'flag' => 1,
                'teacher' => $this_teacher,
                'teachers' => $this->teacher_list(),
                'msg' => 'Teacher updated!',
                'classes' => $this->class_list(),
                'subjects' => $this->subject_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_teachers_update')->with([
                'flag' => 3,
                'teacher' => $this_teacher,
                'teachers' => $this->teacher_list(),
                'msg' => 'Database error',
                'classes' => $this->class_list(),
                'subjects' => $this->subject_list()
            ]);
          }
    }
    public function update_access(Request $request, $id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            $this_teacher = User::find($id)->toArray();
            $input = $request->all();
            $input['can_access_lesson'] = 0;
            $input['is_paid'] = 0;
            if($request->get('can_access_lesson')){
                $input['can_access_lesson'] = 1;
            }
            if($request->get('is_paid')){
                $input['is_paid'] = 1;
            }
            $teacher = User::find($id);
            if(!$teacher){
                    return back()->with([
                        'flag' => 2,
                        'teacher' => $this_teacher,
                        'teachers' => $this->teacher_list(),
                        'msg' => 'Item not fond',
                        'classes' => $this->class_list(),
                        'subjects' => $this->subject_list()
                    ]);
            }

            $teacher->can_access_lesson = $input['can_access_lesson'];
            $teacher->is_paid = $input['is_paid'];
            $teacher->save();
            return back()->with([
                'flag' => 1,
                'teacher' => $this_teacher,
                'teachers' => $this->teacher_list(),
                'msg' => 'Teacher permission updated!',
                'classes' => $this->class_list(),
                'subjects' => $this->subject_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return back()->with([
                'flag' => 3,
                'teacher' => $this_teacher,
                'teachers' => $this->teacher_list(),
                'msg' => 'Database error',
                'classes' => $this->class_list(),
                'subjects' => $this->subject_list()
            ]);
          }
    }
    protected function teacher_list()
    {
        return User::where('is_teacher', true)->where('is_active', true)->get()->toArray();
    }
    protected function class_list()
    {
        return Gclass::where('is_active', true)->get()->toArray();
    }
    protected function subject_list()
    {
        return Subject::where('is_active', true)->get()->toArray();
    }
    protected function format_tel($tel){
        $phone =  '';
        $tel = str_replace(' ', '', $tel);
        if( substr( $tel, 0, 2 ) === "07" && strlen($tel) == 10 ){
            return $phone = '+254'.(int)$tel;
        }
        elseif( substr( $tel, 0, 2 ) === "01" && strlen($tel) == 10 ){
            return $phone = '+254'.(int)$tel;
        }
        elseif( substr( $tel, 0, 4 ) === "2547" && strlen($tel) == 12 ){
            return $phone = '+'.$tel;
        }
        elseif( substr( $tel, 0, 4 ) === "2541" && strlen($tel) == 12 ){
            return $phone = '+'.$tel;
        }
        elseif( substr( $tel, 0, 5 ) === "25407" && strlen($tel) == 13 ){
            $phone = strstr($tel, '0');
            return	$phone = '+254'.(int)$phone;
        }
        elseif( substr( $tel, 0, 5 ) === "25401" && strlen($tel) == 13 ){
            $phone = strstr($tel, '0');
            return	$phone = '+254'.(int)$phone;
        }
        elseif( substr( $tel, 0, 6 ) === "+25407" && strlen($tel) == 14 ){
            $phone = strstr($tel, '0');
            return $phone = '+254'.(int)$phone;
        }
        elseif( substr( $tel, 0, 6 ) === "+25401" && strlen($tel) == 14 ){
            $phone = strstr($tel, '0');
            return $phone = '+254'.(int)$phone;
        }
        elseif( substr( $tel, 0, 1 ) === "7" && strlen($tel) == 9 ){
            return $phone = '+254'.(int)$phone;
        }
        elseif( substr( $tel, 0, 1 ) === "1" && strlen($tel) == 9 ){
            return $phone = '+254'.(int)$phone;
        }
        elseif( substr( $tel, 0, 5 ) === "+2547" && strlen($tel) == 13 ){
            return $phone = $tel;
        }
        elseif( substr( $tel, 0, 5 ) === "+2541" && strlen($tel) == 13 ){
            return $phone = $tel;
        }else{
            throw new Exception('Invalid phone number. try format 07XXX or 01XXX');
        }
    }
}

