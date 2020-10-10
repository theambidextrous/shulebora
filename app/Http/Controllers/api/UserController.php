<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Validator;
use Config;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Str;
/**mailables */
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function new_student(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string',
                'c_password' => 'required|same:password',
                'phone' => 'required|string',
                'gender' => 'required|string',
                'school' => 'required|string',
                'level' => 'required|string'
            ]);
            if( $validator->fails() ){
                return response([
                    'status' => -211,
                    'message' => 'Invalid or empty fields',
                    'errors' => $validator->errors()
                ], 401);
            }
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $input['name'] = trim(strtoupper($input['name']));
            $input['school'] = trim(strtoupper($input['school']));
            $input['phone'] = $this->format_tel(trim(strtoupper($input['phone'])));
            $input['group'] = 2;//high school
            $input['has_profile'] = true;
            $input['is_learner'] = true;
            $input['is_active'] = true;
            $user = User::create($input);
            $accesstoken = $user->createToken('authToken')->accessToken;
            return response([
                'status' => 0,
                'message' => 'User created successfully',
                'payload' => [
                    'data' => $user,
                    'token' => $accesstoken
                ]
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response([
                'status' => -211,
                'message' => 'Database server rule violation error'
            ], 401);
        } catch (PDOException $e) {
            return response([
                'status' => -211,
                'message' => 'Database rule violation error'
            ], 401);
        }catch (Exception $e) {
            return response([
                'status' => -211,
                'message' => $e->getMessage()
            ], 401);
        }
    }

    public function new_corporate(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string',
                'c_password' => 'required|same:password',
                'phone' => 'required|string',
                'school' => 'required|string'
            ]);
            if( $validator->fails() ){
                return response([
                    'status' => -211,
                    'message' => 'Invalid or empty fields',
                    'errors' => $validator->errors()
                ], 401);
            }
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $input['name'] = trim(strtoupper($input['name']));
            $input['school'] = trim(strtoupper($input['school']));
            $input['gender'] = 'MALE';
            $input['phone'] = $this->format_tel(trim(strtoupper($input['phone'])));
            $input['level'] = null;
            $input['group'] = null;
            $input['has_profile'] = true;
            $input['is_learner'] = false;
            $input['is_cop'] = true;
            $input['is_active'] = true;
            $user = User::create($input);
            $accesstoken = $user->createToken('authToken')->accessToken;
            return response([
                'status' => 0,
                'message' => 'User created successfully',
                'payload' => [
                    'data' => $user,
                    'token' => $accesstoken
                ]
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response([
                'status' => -211,
                'message' => 'Database server rule violation error'
            ], 401);
        } catch (PDOException $e) {
            return response([
                'status' => -211,
                'message' => 'Database rule violation error'
            ], 401);
        }catch (Exception $e) {
            return response([
                'status' => -211,
                'message' => $e->getMessage()
            ], 401);
        }
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
