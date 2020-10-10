<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MpesaExpressController extends Controller
{
    private $ConsumerKey;
	private $ConsumerSecret;
	private $ShortCode;
    private $PassKey;
    private $Constants;//[env]
	private $TransType;
	private $Amount;
	private $CustomerPhone;
	private $CallBackUrl;
	private $AccountReference;
	private $TransDesc;
    private $Remark;

	function __construct($ConsumerKey, $ConsumerSecret, $ShortCode, $PassKey, $Constants, $TransType = null, $Amount = null, $CustomerPhone = null, $CallBackUrl = null, $AccountReference = null, $TransDesc = null, $Remark = null){
		$this->ConsumerKey = $ConsumerKey;
		$this->ConsumerSecret = $ConsumerSecret;
		$this->ShortCode = $ShortCode;
        $this->PassKey = $PassKey;
        $this->Constants = $Constants;
		$this->TransType = $TransType;
		$this->Amount = $Amount;
		$this->CustomerPhone = $CustomerPhone;
		$this->CallBackUrl = $CallBackUrl;
		$this->AccountReference = $AccountReference;
		$this->TransDesc = $TransDesc;
		$this->Remark = $Remark;
	}
	function CreatePassword(){
		$timestamp = date("Ymdhis");
        return base64_encode($this->ShortCode.$this->PassKey.$timestamp);
	}
	function CreateToken(){
        $url = 'https://'.$this->Constants[0].'.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode($this->ConsumerKey.':'.$this->ConsumerSecret))); 
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $curl_response = curl_exec($curl);
        // print_r(curl_getinfo($curl));
        // print_r('respo::: ' . $curl_response);
        // return $curl_response;
        return json_decode($curl_response)->access_token;
	}
	function TriggerStkPush(){
        $url = 'https://'.$this->Constants[0].'.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        	'Content-Type:application/json',
        	'Authorization:Bearer '.$this->CreateToken()
        ));
        $curl_post_data = array(
            'BusinessShortCode' => $this->ShortCode,
            'Password' => $this->CreatePassword(),
            'Timestamp' => date("Ymdhis"),
            'TransactionType' => $this->TransType,
            'Amount' => $this->Amount,
            'PartyA' => $this->CustomerPhone,
            'PartyB' => $this->ShortCode,
            'PhoneNumber' => $this->CustomerPhone,
            'CallBackURL' => $this->CallBackUrl,
            'AccountReference' => $this->AccountReference,
            'TransactionDesc' => $this->TransDesc,
            'Remark'=> $this->Remark
        );
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $res = curl_exec($curl);
        // print_r($res);
        return $res;
    }
    function QueryStkStatus($CheckoutRequestID){       
        $url = 'https://'.$this->Constants[0].'.safaricom.co.ke/mpesa/stkpushquery/v1/query';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        	'Content-Type:application/json',
        	'Authorization:Bearer '.$this->CreateToken()
        ));
        $curl_post_data = array(
            'BusinessShortCode' => $this->ShortCode,
            'Password' => $this->CreatePassword(),
            'Timestamp' => $date("Ymdhis"),
            'CheckoutRequestID' => $CheckoutRequestID
        );
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $res = curl_exec($curl);
        return $res;
    }
}
