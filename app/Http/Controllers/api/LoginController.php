<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Form;
use App\Rotp;
use App\Gclass;
use App\Subject;
use App\Tsubject;
use App\Lesson;
use App\Assignment;
use App\Paper;
use App\Package;
use App\Userpackage;
use App\Curriculum;
use DateTime;
use Config;
use DateInterval;

class LoginController extends Controller
{
    protected function credentials(Request $request)
    {
        $whatcame = $this->format_login_tel($request->get('email'));
        if( substr( $whatcame, 0, 4) == '+254' ){
            return [ 'phone' => $whatcame,'password' => $request->get('password') ];
        }
        return $request->only($this->username(), 'password');
    }
    public function login(Request $request ){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        if ( $validator->fails()) {
            return response(['status' => -211, 'message' => 'Invalid username or password'], 401);
        }
        $credentials = [];
        $whatcame = $this->format_login_tel($request->get('email'));
        if( substr( $whatcame, 0, 4) == '+254' ){
            $credentials = [ 'phone' => $whatcame,'password' => $request->get('password') ];
        }else{
            $credentials = [ 'email' => $whatcame,'password' => $request->get('password') ];
        }
        if( !Auth::attempt( $credentials ) ){
            return response(['status' => 2, 'message' => 'Invalid username or password'], 200);
        }
        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        $user = Auth::user();
        $subscription_data = [];
        if(!$user['is_active']){
            return response(['status' => 3, 'message' => 'Account not active'], 200);
        }
        if(Auth::user()->is_learner)
        {
            $subscription_data = $this->check_expiry(Auth::user()->id);
        }
        if( Auth::user()->is_teacher || Auth::user()->is_admin )
        {
            return response([
                'status' => -211,
                'message' => 'failed request',
            ], 401); 
        }
        $user_final = User::find(Auth::user()->id);
        $user_final['formname'] = 'none';
        $pre_order = 'CRP-';
        if( Auth::user()->is_learner ){
            $pre_order = '';
            if( $user_final['group'] == 2)
            {
                $user_final['formname'] = Form::find($user_final['level'])->name;
            }else
            {
                $user_final['formname'] = Gclass::find($user_final['level'])->name;
            }
        }
        $user_final['usertype'] = 111;
        if( Auth::user()->is_learner == 1){
            $user_final['usertype'] = 112;
        }
        return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'subscription_data' => $subscription_data,
                'order' => $pre_order . $this->createCode(8),
                'data' => $user_final,
                'token' => $accessToken
            ]
        ], 200);
    }
    public function new_student(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'county' => 'required|string',
            'phone' => 'required|string',
            'form' => 'required|string',
            'password' => 'required|string',
        ]);
        if ( $validator->fails()) {
            return response(['status' => -211, 'message' => 'Invalid data'], 401);
        }
        $data = $request->all();
        $data['name'] = trim(strtoupper($data['name']));
        $data['email'] = $data['phone'] . '@shulebora.com';
        $data['school'] = trim(strtoupper($data['county']));
        $data['gender'] = "None";
        $data['phone'] = $this->format_tel(trim(strtoupper($data['phone'])));
        $w_str = explode('~', $data['form']);
        $data['level'] = trim($w_str[1]);
        $data['group'] = 1;/**primary */
        if($w_str[0] == 'is_h'){
            $data['group'] = 2;/**high school/seco */
        }
        $data['has_profile'] = true;
        $data['is_learner'] = true;
        $data['is_active'] = true;
        $data['package'] = 0;
        $data['password'] = Hash::make($data['password']);
        // if(User::where('email', $data['email'])->count() > 0 ){
        //     return response(['status' => 1, 'message' => 'email already used'], 200);
        // }
        if(User::where('phone', $data['phone'])->count() > 0 ){
            return response(['status' => 2, 'message' => 'phone already used'], 200);
        }
        $user = User::create($data);
        if(is_null($user)){
            return response(['status' => -211, 'message' => 'Invalid data'], 401);
        }
        $credentials = [];
        $whatcame = $this->format_login_tel($request->get('phone'));
        if( substr( $whatcame, 0, 4) == '+254' ){
            $credentials = [ 'phone' => $whatcame,'password' => $request->get('password') ];
        }else{
            $credentials = [ 'email' => $whatcame,'password' => $request->get('password') ];
        }
        if( !Auth::attempt( $credentials ) ){
            return response(['status' => -211, 'message' => 'Invalid username or password'], 401);
        }
        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        $user = Auth::user();
        $user['usertype'] = 112;
        if( $user['group'] == 2)
        {
            $user['formname'] = Form::find($user['level'])->name;
        }else
        {
            $user['formname'] = Gclass::find($user['level'])->name;
        }
        $sss = Userpackage::where('user', Auth::user()->id)
        ->where('package', Auth::user()->package)
        ->orderBy('id', 'desc')
        ->first();
        if(is_null($sss)){
            $sss = [];
        }
        return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'subscription_data' => $sss,
                'order' => $this->createCode(8),
                'data' => $user,
                'token' => $accessToken
            ]
        ], 200);
    }
    public function new_corporate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'school' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);
        if ( $validator->fails()) {
            return response(['status' => -211, 'message' => 'Invalid data'], 401);
        }
        $data = $request->all();
        $data['name'] = trim(strtoupper($data['name']));
        $data['school'] = trim(strtoupper($data['school']));
        $data['gender'] = trim(strtoupper('male'));
        $data['phone'] = $this->format_tel(trim(strtoupper($data['phone'])));
        $data['level'] = 0;
        $data['group'] = 0;
        $data['has_profile'] = true;
        $data['is_cop'] = true;
        $data['is_admin'] = false;
        $data['is_learner'] = false;
        $data['is_active'] = true;
        $data['package'] = 0;
        $data['password'] = Hash::make($data['password']);
        if(User::where('email', $data['email'])->count() > 0 ){
            return response(['status' => 1, 'message' => 'email already used'], 200);
        }
        if(User::where('phone', $data['phone'])->count() > 0 ){
            return response(['status' => 2, 'message' => 'phone already used'], 200);
        }
        $user = User::create($data);
        if(is_null($user)){
            return response(['status' => -211, 'message' => 'Invalid data'], 401);
        }
        $login = $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        if( !Auth::attempt( $login ) ){
            return response(['status' => -211, 'message' => 'Invalid data'], 401);
        }
        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        $user = Auth::user();
        $user['formname'] ='none';
        $user['usertype'] = 111;
        return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'subscription_data' => [],
                'order' => 'CRP-' . $this->createCode(8),
                'data' => $user,
                'token' => $accessToken
            ]
        ], 200);
    }
    public function userdata(){
        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        $user = Auth::user();
        $subscription_data = [];
        /** subscription */
        if(Auth::user()->is_learner)
        {
            $subscription_data = $this->check_expiry(Auth::user()->id);
        }
        
        if( Auth::user()->is_teacher || Auth::user()->is_admin )
        {
            return response([
                'status' => -211,
                'message' => 'failed request',
            ], 401); 
        }

        $user_final = User::find(Auth::user()->id);
        if( Auth::user()->is_cop ){
            $user_final['formname'] = 'none';
        }else{
            if( $user_final['group'] == 2)
            {
                $user_final['formname'] = Form::find($user_final['level'])->name;
            }else
            {
                $user_final['formname'] = Gclass::find($user_final['level'])->name;
            }
        }
        $user_final['usertype'] = 111;
        if( Auth::user()->is_learner == 1 ){
            $user_final['usertype'] = 112;
        }
        return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'subscription_data' => $subscription_data,
                'data' => $user_final,
                'token' => $accessToken
            ]
        ], 200);
    }
    public function is_active(Request $request ){
        if(Auth::user()->id){
            return response([
                'status' => 0,
                'message' => 'success request',
                'payload' => [
                    'data' => []
                ]
            ], 200);
        }
        return response(['status' => -211,'message' => 'is inactive'], 401);
    }
    public function requestcode($phone)
    {
        try{
            $phone = $this->format_tel($phone);
            $user = User::where('phone', $phone)->count();
            if(!$user){
                return response([
                    'status' => 2,
                    'message' => 'account not found',
                    'payload' => [
                        'data' => []
                    ]
                ], 200); 
            }
            Rotp::where('phone', $phone)->update(['used' => true]);
            $code = $this->createCode(6,1);
            $data = ['phone' => $phone, 'code' => $code ];
            if( Rotp::create($data) )
            {
                $msg = "Hi, use OTP code " . $code . " to validate your account.";
                $this->sendSMS($phone, $msg);
                return response([
                    'status' => 0,
                    'message' => 'success request',
                    'payload' => [
                        'data' => $data
                    ]
                ], 200);
            }
            return response([
                'status' => 0,
                'message' => 'invalid phone',
            ], 401);
            
        }catch( Exception $e){
            return response([
                'status' => 0,
                'message' => 'invalid phone',
            ], 401);
        }
    }
    public function validatecode($phone, $code)
    {
        try{
            $phone = $this->format_tel($phone);
            $data = ['phone' => $phone, 'code' => $code ];
            $isValid = Rotp::where('phone', $phone)
                ->where('code', $code)
                ->where('used', false)
                ->orderBy('created_at', 'desc')
                ->first();
            if( !is_null($isValid) )
            {
                $isValid->used = true;
                $isValid->save();
                return response([
                    'status' => 0,
                    'message' => 'success request',
                    'payload' => [
                        'data' => $data
                    ]
                ], 200);
            }
            return response([
                'status' => 2,
                'message' => 'You entered invalid code ' . $code,
            ], 200);
        }catch( Exception $e){
            return response([
                'status' => 0,
                'message' => 'system error',
            ], 401);
        }
    }
    public function pwdreset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string',
            'c_password' => 'required|same:password'
        ]);
        if( $validator->fails() ){
            return response([
                'status' => -211,
                'message' => 'Passwords do no match',
                'errors' => $validator->errors()
            ], 401);
        }
        $phone = $this->format_tel($request->get('phone'));
        $user = User::where('phone', $phone)->first();
        if(!is_null($user)){
            $user->password = Hash::make($request->get('password'));
            $user->save();
            return response([
                'status' => 0,
                'message' => 'success request',
                'payload' => [
                    'data' => []
                ]
            ], 200);
        }
        return response([
            'status' => 0,
            'message' => 'invalid phone',
        ], 401);
    }
    protected function check_expiry($user)
    {
        $subscription_data = Userpackage::where('user', $user)
            ->where('package', Auth::user()->package)
            ->orderBy('id', 'desc')
            ->first();
        if(!is_null($subscription_data)){
            if(is_null($subscription_data['expiry'])){
                $subscription_data = $subscription_data->toArray();
                $subscription_date = explode('T', $subscription_data['created_at'])[0];
                $obj_date = new DateTime($subscription_date);
                $renew_date = $obj_date->format('Y-m-d');
                $subscription_data['start'] = $renew_date;
                $obj_date->add(new DateInterval('P'.$subscription_data['max_usage'].'M'));
                $expiry_date = $obj_date->format('Y-m-d');
                $u = Userpackage::find($subscription_data['id']);
                $u->expiry = $expiry_date;
                $u->is_expired = false;
                $u->save();
                $subscription_data['expiry'] = $expiry_date;
                $subscription_data['packname'] = Package::find(Auth::user()->package)->name;

                return $subscription_data;
            }elseif(!is_null($subscription_data['expiry'])){
                $end_date = date('Y-m-d', strtotime($subscription_data['expiry']));
                $today = date('Y-m-d');
                if($today > $end_date ){
                    //set as expired
                    $p = Userpackage::find($subscription_data['id']);
                    $p->is_expired = true;
                    $p->save();
                    $u = User::find($user);
                    $u->package = 0;
                    $u->is_paid = false;
                    $u->can_access_lesson = false;
                    $u->save();
                    return $p;
                }
                return $subscription_data;
            }
        }
    }
    protected function createCode($length = 20, $t = 0) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if( $t > 0 ){
            $characters = '0123456789';
        }
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
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
            return $phone = '+254'.(int)$tel;
        }
        elseif( substr( $tel, 0, 1 ) === "1" && strlen($tel) == 9 ){
            return $phone = '+254'.(int)$tel;
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
    protected function format_login_tel($tel){
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
            return $phone = '+254'.(int)$tel;
        }
        elseif( substr( $tel, 0, 1 ) === "1" && strlen($tel) == 9 ){
            return $phone = '+254'.(int)$tel;
        }
        elseif( substr( $tel, 0, 5 ) === "+2547" && strlen($tel) == 13 ){
            return $phone = $tel;
        }
        elseif( substr( $tel, 0, 5 ) === "+2541" && strlen($tel) == 13 ){
            return $phone = $tel;
        }else{
            return $tel;
        }
    }

    public function sendSMS($phone, $message){
        try{
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, Config::get('app.app_sms_api_url'));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json')); 
            $c_data = [
                "apikey" => Config::get('app.app_sms_api_key'),
                "partnerID" => Config::get('app.app_sms_partner_id'),
                "message" => $message,
                "shortcode" => Config::get('app.app_sms_shortcode'),
                "mobile" => substr($this->format_tel($phone), 1, 12)
            ];
            $data_string = json_encode($c_data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            $curl_response = curl_exec($curl);
            return true;
            // return response([
            //     'status' => 0,
            //     'message' => 'success request',
            //     'payload' => [
            //         'data' => $curl_response
            //     ]
            // ], 200);
        }catch(Exception $e ){
            return false;
            return response([
                'status' => -211,
                'message' => $e->getMessage(),
            ], 401);  
        }
    }
}
