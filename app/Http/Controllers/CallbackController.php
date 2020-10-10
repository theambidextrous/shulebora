<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Order;
use App\User;
use App\Paylogs;
use App\Userpackage;
use App\Package;
use App\Lessonpurchase;
class CallbackController extends Controller
{
    /**
     * Mpesa call backs 
     */
    public function c2b()
    {
        $data = file_get_contents("php://input");
        // $data = '{"TransactionType": "Pay Bill",
        //     "TransID": "OGJ21HCDXG",
        //     "TransTime": "20200719220209",
        //     "TransAmount": "10.00",
        //     "BusinessShortCode": "600000",
        //     "BillRefNumber": "LJPK4U",
        //     "InvoiceNumber": "",
        //     "OrgAccountBalance": "83769022.00",
        //     "ThirdPartyTransID": "",
        //     "MSISDN": "254708374149",
        //     "FirstName": "John",
        //     "MiddleName": "J.",
        //     "LastName": "Doe"
        // }';
        if(!$data)
        {
            return response(['ResultCode' => '0', 'ResultDesc' => 'Accepted Successfully']);
        }
        else
        {
            Storage::disk('local')->prepend('mpesalog_c2b.log', $data);
            $paylog = json_decode($data);
            if( substr($paylog->BillRefNumber, 0, 3) == 'CRP' ) // corporate
            {
                $order = Lessonpurchase::where('orderid', strtoupper($paylog->BillRefNumber))
                    ->where('paid', false)->first();
                if(is_null($order)){
                    return response(['ResultCode' => '0', 'ResultDesc' => 'Accepted Successfully']);
                }
                $user = User::find($order->buyer);
                $paylog->TransAmount = $order->cost;//test
                if( floor($paylog->TransAmount) == floor($order->cost) )//amt paid equal order cost
                {
                    $input = [
                        'order' => $paylog->BillRefNumber,
                        'buyer' => $order->buyer,
                        'payer' => $paylog->FirstName . ' ' . $paylog->LastName,
                        'phone' => $paylog->MSISDN,
                        'payref' => $paylog->TransID,
                        'amount' => $paylog->TransAmount,
                        'time' => $paylog->TransTime,
                        'method' => 'Mpesa c2b',
                        'paystring' => $data
                    ];
                    Paylogs::create($input);
                    //finally mark ordr as paid
                    $order->paid_amount = $paylog->TransAmount;
                    $order->payref = $paylog->TransID;
                    $order->paid = true;
                    $order->save();
                }
                return response(['ResultCode' => '0', 'ResultDesc' => 'Accepted Successfully']);
            }
            else
            {
                $order = Order::where('orderid', strtoupper($paylog->BillRefNumber))
                    ->where('paid', false)->first();
                if(is_null($order)){
                    return response(['ResultCode' => '0', 'ResultDesc' => 'Accepted Successfully']);
                }
                $user = User::find($order->buyer);
                $paylog->TransAmount = $order->cost;//test
                if( floor($paylog->TransAmount) == floor($order->cost) )//amt paid equal order cost
                {
                    $user->is_paid = true;
                    $user->can_access_lesson = true;
                    $user->package = $order->package;
                    $user->save();
                    //
                    $input = [
                        'order' => $paylog->BillRefNumber,
                        'buyer' => $order->buyer,
                        'payer' => $paylog->FirstName . ' ' . $paylog->LastName,
                        'phone' => $paylog->MSISDN,
                        'payref' => $paylog->TransID,
                        'amount' => $paylog->TransAmount,
                        'time' => $paylog->TransTime,
                        'method' => 'Mpesa c2b',
                        'paystring' => $data
                    ];
                    Paylogs::create($input);
                    $payload = [
                        'user' => $order->buyer,
                        'package' => $order->package,
                        'usage' => 1,
                        'max_usage' => Package::find($order->package)->max_usage,
                        'is_expired' => false
                    ];
                    Userpackage::create($payload);
                    //finally mark ordr as paid
                    $order->paid_amount = $paylog->TransAmount;
                    $order->payref = $paylog->TransID;
                    $order->paid = true;
                    $order->save();
                }
                return response(['ResultCode' => '0', 'ResultDesc' => 'Accepted Successfully']);
            }
        }
    }
    public function express()
    {
        $data = file_get_contents("php://input");
        if(!$data)
        {
            return response(['ResultCode' => '0', 'ResultDesc' => 'Accepted Successfully']);
        }
        else
        {
            Storage::disk('local')->prepend('mpesalog_exp.log', $data);
            /** callback urls */
            $paylog = json_decode($data, true);
            $stkCallback = $paylog['Body']['stkCallback'];
            $CheckoutRequestID = $stkCallback['CheckoutRequestID'];

            $order = Order::where('payref', $CheckoutRequestID)
                ->where('paid', false)->first();
            if(is_null($order)){
                $order = Lessonpurchase::where('payref', $CheckoutRequestID)
                    ->where('paid', false)->first();
            }
            if( substr($order->orderid, 0, 3) == 'CRP' ) // corporate
            {
                $user = User::find($order->buyer);
                if( $stkCallback['ResultCode'] == '0' )//success
                {
                    $stk_meta_data = $stkCallback['CallbackMetadata']['Item'];
                    $input = [
                        'order' => $order->orderid,
                        'buyer' => $order->buyer,
                        'payer' => 'n/a',
                        'phone' => $stk_meta_data[4]['Value'],
                        'payref' => $stk_meta_data[1]['Value'],
                        'amount' => $stk_meta_data[0]['Value'],
                        'time' => $stk_meta_data[3]['Value'],
                        'method' => 'Mpesa Express',
                        'paystring' => $data
                    ];
                    Paylogs::create($input);
                    //finally mark ordr as paid
                    $order->paid_amount = $stk_meta_data[0]['Value'];
                    $order->payref = $stk_meta_data[1]['Value'];
                    $order->paid = true;
                    $order->save();
                }
                /** end callbacks */
                return response(['ResultCode' => '0', 'ResultDesc' => 'Accepted Successfully']);
            }
            else
            {
                $user = User::find($order->buyer);
                if( $stkCallback['ResultCode'] == '0' )//success
                {
                    $user->is_paid = true;
                    $user->can_access_lesson = true;
                    $user->package = $order->package;
                    $user->save();
                    //
                    $stk_meta_data = $stkCallback['CallbackMetadata']['Item'];
                    //
                    $input = [
                        'order' => $order->orderid,
                        'buyer' => $order->buyer,
                        'payer' => 'n/a',
                        'phone' => $stk_meta_data[4]['Value'],
                        'payref' => $stk_meta_data[1]['Value'],
                        'amount' => $stk_meta_data[0]['Value'],
                        'time' => $stk_meta_data[3]['Value'],
                        'method' => 'Mpesa Express',
                        'paystring' => $data
                    ];
                    Paylogs::create($input);
                    $payload = [
                        'user' => $order->buyer,
                        'package' => $order->package,
                        'usage' => 1,
                        'max_usage' => Package::find($order->package)->max_usage,
                        'is_expired' => false
                    ];
                    Userpackage::create($payload);
                    //finally mark ordr as paid
                    $order->paid_amount = $stk_meta_data[0]['Value'];
                    $order->payref = $stk_meta_data[1]['Value'];
                    $order->paid = true;
                    $order->save();
                }
                /** end callbacks */
                return response(['ResultCode' => '0', 'ResultDesc' => 'Accepted Successfully']);
            }
        }
    }
    public function status_check($order)
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        $source = [
            'data' => 'Payment Still Processing...',
            'status' => 1
        ];
        $ord = Order::where('orderid', strtoupper($order))->first();
        if( substr($order, 0, 3) == 'CRP' ) // corporate
        {
            $ord = Lessonpurchase::where('orderid', strtoupper($order))->first();
        }
        if( $ord->paid )
        {
            $event_data = '<div class="d-flex justify-content-center"><div class="alert alert-success"><b>Success!</b> Payment of KES '.$ord->paid_amount.' has been received. </div></div>';

            $source = [
                'data' => $event_data,
                'status' => 0
            ];
        }
        else
        {
            $event_data = '<div class="d-flex justify-content-center"><div class="spinner-border spinner-border-lg spinner-grow-lg" role="status"><span class="sr-only">Loading...</span></div></div>';
            $source = [
                'data' => $event_data,
                'status' => 1
            ];
        }
        $res = json_encode($source);
        echo "data: {$res}\n\n";
        flush();
    }
}
