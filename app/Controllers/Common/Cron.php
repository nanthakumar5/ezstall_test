<?php

namespace App\Controllers\Common;

use App\Controllers\BaseController;

class Cron extends BaseController
{
    public function __construct()
    {
		$this->db = db_connect();
    }
	
	public function cartremoval()
	{	
		$ip 		= 	$this->request->getIPAddress();
		$datetime 	= 	date("Y-m-d H:i:s");
		
		$cart 		= 	$this->db->table('cart')
						->select('max(datetime) as datetime, user_id')
						->groupBy('user_id', 'desc')
						->having('DATE_ADD(datetime, INTERVAL 30 MINUTE) <=', $datetime)
						->get()
						->getResultArray();
		
		if(count($cart) > 0){
			foreach($cart as $data){
				$this->db->table('cart')->delete(['user_id' => $data['user_id']]);
			}
		}
		
		die;
	}
}