<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Group;
use App\Gclass;
use App\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
/** mail */
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountCreation;
use App\Mail\AccountUpdate;

class StudentController extends Controller
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
        return view('admin_students')->with([
            'students' => $this->student_list(),
            'groups' => $this->group_list(),
            'classes' => $this->class_list(),
            'forms' => $this->form_list()
        ]);
    }
    public function show($id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        $student = User::where('id', $id)->where('is_learner', true)->first()->toArray();
        return view('admin_students_update')->with([
            'student' => $student,
            'groups' => $this->group_list(),
            'classes' => $this->class_list(),
            'forms' => $this->form_list()
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
                'class_form' => 'string|required',
                'school' => 'string|required',
                'password' => 'string|required'
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
            $input['has_profile'] = true;
            $input['is_learner'] = true;
            $input['is_active'] = true;
            $input['password'] = Hash::make($request->get('password'));
            $student = User::create($input);
            if($student){
                $u = User::where('email', $input['email'])->first();
                Mail::to($u->email)
                    ->send(new AccountCreation($u, $request->get('password')));
                return view('admin_students')->with([
                    'flag' => 1,
                    'students' => $this->student_list(),
                    'msg' => 'Student created successfully',
                    'groups' => $this->group_list(),
                    'classes' => $this->class_list(),
                    'forms' => $this->form_list()
                ]);
            }
            return view('admin_students')->with([
                'flag' => 2,
                'students' => $this->student_list(),
                'msg' => 'Student creation failed',
                'groups' => $this->group_list(),
                'classes' => $this->class_list(),
                'forms' => $this->form_list()
            ]);

          }catch(Exception $e ){
            return view('admin_students')->with([
                'flag' => 3,
                'students' => $this->student_list(),
                'msg' => $e->getMessage(),
                'groups' => $this->group_list(),
                'classes' => $this->class_list(),
                'forms' => $this->form_list()
            ]);
          }catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_students')->with([
                'flag' => 3,
                'students' => $this->student_list(),
                'msg' => 'Databse error. Most likely entry already exists',
                'groups' => $this->group_list(),
                'classes' => $this->class_list(),
                'forms' => $this->form_list()
            ]);
          }
    }
    public function update(Request $request, $id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            $this_student = User::find($id)->toArray();
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
            $student = User::find($id);
            if(!$student){
                    return view('admin_students')->with([
                        'flag' => 2,
                        'student' => $this_student,
                        'students' => $this->student_list(),
                        'msg' => 'Item not fond',
                        'groups' => $this->group_list(),
                        'classes' => $this->class_list(),
                        'forms' => $this->form_list()
                    ]);
            }
            $student->name = $input['name'];
            $student->email = $input['email'];
            $student->phone = $input['phone'];
            $student->email = $input['email'];
            $student->gender = $input['gender'];
            $student->group = $input['group'];
            $student->level = $input['level'];
            $student->save();
            Mail::to($student->email)
                ->send(new AccountUpdate($student));
            return view('admin_students_update')->with([
                'flag' => 1,
                'student' => $this_student,
                'students' => $this->student_list(),
                'msg' => 'Student updated!',
                'groups' => $this->group_list(),
                'classes' => $this->class_list(),
                'forms' => $this->form_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_students_update')->with([
                'flag' => 3,
                'student' => $this_student,
                'students' => $this->student_list(),
                'msg' => 'Database error',
                'groups' => $this->group_list(),
                'classes' => $this->class_list(),
                'forms' => $this->form_list()
            ]);
          }
    }
    public function update_access(Request $request, $id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            $this_student = User::find($id)->toArray();
            $input = $request->all();
            $input['can_access_lesson'] = 0;
            $input['is_paid'] = 0;
            if($request->get('can_access_lesson')){
                $input['can_access_lesson'] = 1;
            }
            if($request->get('is_paid')){
                $input['is_paid'] = 1;
            }
            $student = User::find($id);
            if(!$student){
                    return back()->with([
                        'flag' => 2,
                        'student' => $this_student,
                        'students' => $this->student_list(),
                        'msg' => 'Item not fond',
                        'groups' => $this->group_list(),
                        'classes' => $this->class_list(),
                        'forms' => $this->form_list()
                    ]);
            }

            $student->can_access_lesson = $input['can_access_lesson'];
            $student->is_paid = $input['is_paid'];
            $student->save();
            return back()->with([
                'flag' => 1,
                'student' => $this_student,
                'students' => $this->student_list(),
                'msg' => 'Student permission updated!',
                'groups' => $this->group_list(),
                'classes' => $this->class_list(),
                'forms' => $this->form_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return back()->with([
                'flag' => 3,
                'student' => $this_student,
                'students' => $this->student_list(),
                'msg' => 'Database error',
                'groups' => $this->group_list(),
                'classes' => $this->class_list(),
                'forms' => $this->form_list()
            ]);
          }
    }
    protected function student_list()
    {
        return User::where('is_learner', true)->where('is_active', true)->get()->toArray();
    }
    protected function group_list()
    {
        return Group::where('is_active', true)->get()->toArray();
    }
    protected function class_list()
    {
        return Gclass::where('is_active', true)->get()->toArray();
    }
    protected function form_list()
    {
        return Form::where('is_active', true)->get()->toArray();
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
