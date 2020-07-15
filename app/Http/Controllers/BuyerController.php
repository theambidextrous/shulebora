<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Package;
use App\Order;
use Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
/** mpesa */
use App\Http\Controllers\MpesaExpressController;
use App\Http\Controllers\MpesaController;
/** mail */
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountCreation;

class BuyerController extends Controller
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

    
    public function showPackagesForm()
    {
        if(!Session::has('order')){
            return redirect()->route('learner');
        }
        return view('guest_packages')->with([
            'orderid' => Session::get('order'),
            'packages' => $this->package_list()
        ]);
    }
    public function showPaymentForm()
    {
        return view('guest_pay_options')->with([
            'orderid' => Session::get('order'),
            'packages' => $this->package_list()
        ]);
    }
    public function order(Request $request )
    {
        if(!Auth::user()->is_learner){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'package' => 'string|required',
                'cost' => 'string|required',
                'orderid' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['buyer'] = Auth::user()->id;
            $order = Order::where('orderid', $input['orderid'])->where('paid', false)->first();
            if($order){
                $order->cost = $input['cost'];
                $order->package = $input['package'];
                $order->save();
                return redirect()->route('payform')->with('full_order', $order->toArray());
            }
            Order::create($input);
            $neworder = Order::where('orderid', $input['orderid'])->first()->toArray();
            if($neworder){
                return redirect()->route('payform')->with('full_order', $neworder);
            }
            return back()->with([
                'flag' => 2,
                'msg' => 'Order creation failed',
                'packages' => $this->package_list()
            ]);
        }catch(\Illuminate\Database\QueryException $ex){ 
            return back()->with([
                'flag' => 3,
                'msg' => 'Databse error. Most likely entry already exists',
                'packages' => $this->package_list()
            ]);
        }
    }
    public function pay(Request $request){
        if(!Auth::user()->is_learner){
            abort(404); 
        }
        $the_order = Order::where('orderid', $request->get('orderid'))->first()->toArray();
        try { 
            Validator::make($request->all(),[
                'phone' => 'string|required',
                'cost' => 'string|required',
                'orderid' => 'string|required'
            ])->validate();
            $input = $request->all();
            $phone_n = $this->format_tel($input['phone']);
            $phone_n = substr($phone_n, 1, 12);
            $input['phone'] = $phone_n;
            if(!count($the_order)){
                return back()->with(['error' => 'order not found', 'full_order' => $the_order]);
            }
            $mpesa_instructions = $this->mpesa_process(Config::get('APP_MPESA_PAYBILL'), $input['orderid'], $input['cost']);
            $express = new MpesaExpressController(
                Config::get('APP_MPESA_C_KEY'),
                Config::get('APP_MPESA_C_SECRET'),
                Config::get('APP_MPESA_PAYBILL'),
                Config::get('APP_MPESA_PASSKEY'),
                [Config::get('APP_MPESA_ENV')],
                Config::get('APP_MPESA_PASSKEY'),
                $input['cost'],
                $input['phone'],
                route('express'),
                $input['orderid'],
                'Shulebora',
                'no remarks'
            );
            $c2b = new MpesaController(
                Config::get('APP_MPESA_C2B_C_KEY'),
                Config::get('APP_MPESA_C2B_C_SECRET'),
                Config::get('APP_MPESA_C2B_PAYBILL'),
                Config::get('APP_MPESA_C2B_PHONE'),
                $input['cost'],
                $input['orderid'],
                [Config::get('APP_MPESA_ENV'), route('callback'), route('callback')]
            );
            $a = $express->TriggerStkPush();
            $b = $c2b->RegisterUrl();
            $c = $c2b->Simulate();
            return back()->with([
                    'mpesa' => [
                        'a' => $a,
                        'b' => $b,
                        'c' => $c
                    ],
                    'full_order' => $the_order
                ]);
        }catch(\Illuminate\Database\QueryException $ex){ 
            return back()->with([
                'error' => 'Database error. Most likely entry already exists',
                'full_order' => $the_order
            ]);
        }catch(Exception $ex){ 
            return back()->with([
                'error' => $ex->getMessage(),
                'full_order' => $the_order
            ]);
        }
    }
    protected function package_list()
    {
        return Package::where('is_active', true)->get()->toArray();
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
    protected function mpesa_process($paybill, $invoice, $amt)
    {
        return '
        <div class="article_text">
            <p><span style="font-size:14pt;"><strong>You can also pay as follows</strong></span></p>
            <ol>
                <li>Select <b>"Pay bill"</b> from your Safaricom MPesa Menu.</li>
                <li>Enter ShuleBora Business Number <b>"'.$paybill.'"</b>.</li>
                <li>Select <b>"Enter Account Number"</b>.</li>
                <li>Enter Order number <b>"'.$invoice.'"</b>.</li>
                <li>Enter Amount <b>"'.floor($amt).'"</b> </li>
                <li>Enter <b>"PIN"</b> then Press "OK"</li>
                <li>You will then Receive a "Confirmation Message" from MPesa.</li>
            </ol>
        </div>
        ';
    }
}
