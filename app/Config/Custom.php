<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Custom extends BaseConfig
{
	public $usertype    		= ['1'=>'Admin','2'=>'Facility','3'=>'Producer','4'=>'Stall Manager','5'=>'Horse Owner','6'=>'Operators'];
	public $status1  			= ['1'=>'Enable','2'=>'Disable'];
	public $paymentinterval 	= [/*'week' => 'Weekly Subscription',*/ 'month' => 'Monthly Subscription', 'year' => 'Yearly Subscription'];
	public $paymenttype 		= ['1' => 'Payment', '2' => 'Subscription'];
	public $paymentuser 		= ['2' => 'Facility', '5' => 'Horse Owner'];
	public $paymentstatus 		= ['1' => 'Paid', '2' => 'Refunded'];
	public $bookingstatus 		= ['1' => 'Booked', '2' => 'Cancelled'];
	public $yesno 				= ['1' => 'Yes', '2' => 'No'];
	public $chargingflag 		= ['1' => 'Per Night', '2' => 'Per Week', '3' => 'Per Month', '4' => 'Flat Rate'];
	public $currencysymbol 		= "$";
	public $googleapikey 		= "AIzaSyDRvTJ00O76SJefErQP2FFz4IDmCigbS6w";
}
