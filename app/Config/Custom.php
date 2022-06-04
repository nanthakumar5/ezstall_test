<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Custom extends BaseConfig
{
	public $usertype    		= ['1'=>'Admin','2'=>'Facility','3'=>'Producer','4'=>'Stall Manager','5'=>'Horse Owner'];
	public $status1  			= ['1'=>'Enable','2'=>'Disable'];
	public $paymentinterval 	= [/*'week' => 'Weekly Subscription',*/ 'month' => 'Monthly Subscription', 'year' => 'Yearly Subscription'];
	public $paymenttype 		= ['1' => 'Payment', '2' => 'Subscription'];
	public $paymentuser 		= ['2' => 'Facility', '5' => 'Horse Owner'];
	public $paymentstatus 		= ['1' => 'Paid', '2' => 'Refunded'];
	public $bookingstatus 		= ['1' => 'Booked', '2' => 'Cancelled'];
	public $stripemode 			= ['1' => 'Live', '2' => 'Sandbox'];
	public $currencysymbol 		= "$";
}
