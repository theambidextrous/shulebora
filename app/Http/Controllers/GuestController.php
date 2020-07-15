<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Group;
use App\Package;
use App\Gclass;
use App\Form;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
/** mail */
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountCreation;

class GuestController extends Controller
{
    use RegistersUsers;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    // protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectTo(){ 
        if( Auth::user()->is_admin ){
           return route('school');
        }
        if( Auth::user()->is_cop ){
            return route('corporate');
        }
        if( Auth::user()->is_teacher ){
            return route('teacher');
        }
        if( Auth::user()->is_learner ){
            Session::put('order', $this->createCode(6));
            return route('learner');
        }
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showRegistrationForm()
    {
        return view('guest_new_student')->with([
            'groups' => $this->group_list(),
            'classes' => $this->class_list(),
            'forms' => $this->form_list()
        ]);
    }    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string','max:15'],
            'gender' => ['required', 'string', 'max:10'],
            'class_form' => ['required', 'string'],
            'school' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $data['name'] = trim(strtoupper($data['name']));
        $data['school'] = trim(strtoupper($data['school']));
        $data['gender'] = trim(strtoupper($data['gender']));
        $data['phone'] = $this->format_tel(trim(strtoupper($data['phone'])));
        $w_str = explode('~', $data['class_form']);
        $data['level'] = trim($w_str[1]);
        $data['group'] = 1;/**primary */
        if($w_str[0] == 'is_h'){
            $data['group'] = 2;/**high school/seco */
        }
        $data['has_profile'] = true;
        $data['is_learner'] = true;
        $data['is_active'] = true;
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
        // return User::create([
        //     'name' => strtoupper($data['name']),
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        //     'is_active' => true,
        // ]);
    }
    
    protected function package_list()
    {
        return Package::where('is_active', true)->get()->toArray();
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
    protected function createCode($length = 20) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
