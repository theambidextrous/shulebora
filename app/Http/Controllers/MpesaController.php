<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MpesaController extends Controller
{
    private $consumer_key;
	private $consumer_secret;
	private $shortcode;
	private $msisdn;
	private $amount;
    private $billrefnumber;
    private $constants;//[env, confrimation, validdation]

	function __construct($consumer_key, $consumer_secret, $shortcode, $msisdn, $amount, $billrefnumber, $constants){
		$this->consumer_key = $consumer_key;
		$this->consumer_secret = $consumer_secret;
		$this->shortcode = $shortcode;
		$this->msisdn = $msisdn;
		$this->amount = $amount;
        $this->billrefnumber = $billrefnumber;
        $this->constants = $constants;
	}
	function CreateToken(){
		$url = 'https://'.$this->constants[0].'.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.base64_encode($this->consumer_key.':'.$this->consumer_secret)));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        $res = curl_exec($curl);
        // print_r('respo::: ' . $res . $this->consumer_key);
        if ( array_key_exists('access_token', json_decode($res, true)) ){
        	return json_decode($res)->access_token;
        }else{
        	return null;
        }
	}
	function RegisterUrl(){
	  $url = 'https://'.$this->constants[0].'.safaricom.co.ke/mpesa/c2b/v1/registerurl';
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array(
          'Content-Type:application/json',
          'Authorization:Bearer ' . $this->CreateToken())
          ); 
      $c_data = [
        'ShortCode'=>$this->shortcode,
        'ResponseType'=>'Completed',
        'ConfirmationURL'=>$this->constants[1],
        'ValidationURL'=>$this->constants[2]
      ];
      
      $data_string = json_encode($c_data);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    //   print($data_string);
      $curl_response = curl_exec($curl);
      return $curl_response;
	}
	function Simulate(){
		$url = 'https://'.$this->constants[0].'.safaricom.co.ke/mpesa/c2b/v1/simulate';
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->CreateToken()));
        $curl_post_data = array(
            'ShortCode' => $this->shortcode,
            'CommandID' => 'CustomerPayBillOnline',
            'Amount' => $this->amount,
            'Msisdn' => $this->msisdn,
            'BillRefNumber' => $this->billrefnumber
        );
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $res = curl_exec($curl);
        return $res;
	}
   
}

