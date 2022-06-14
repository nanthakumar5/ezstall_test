<?php
function getAdminUrl()
{
	return base_url().'/administrator';
}

function getUserDetails($id)
{	
	$users 	= new \App\Models\Users;
	$result = $users->getUsers('row', ['users', 'payment'], ['id' => $id]);

	if ($result) {
		return $result;
	} else {
		return false;
	}
}

function getUserID($id)
{
	$userDetails = getUserDetails($id);

	if ($userDetails) {
		return $userDetails['id'];
	} else {
		return false;
	}
}

function getAdminUserID($id='')
{
	if($id=='' && !isset(session()->get('adminsession')['userid'])) return false;
	$id = ($id=='') ? session()->get('adminsession')['userid'] : $id;
	return getUserID($id);
}

function getSiteUserID($id='')
{
	if($id=='' && !isset(session()->get('sitesession')['userid'])) return false;	
	$id = ($id=='') ? session()->get('sitesession')['userid'] : $id;
	return getUserID($id);
}

function getAdminUserDetails($id='')
{
	if($id=='' && !isset(session()->get('adminsession')['userid'])) return false;	
	$id = ($id=='') ? session()->get('adminsession')['userid'] : $id;
	return getUserDetails($id);
}

function getSiteUserDetails($id='')
{
	if($id=='' && !isset(session()->get('sitesession')['userid'])) return false;	
	$id = ($id=='') ? session()->get('sitesession')['userid'] : $id;
	return getUserDetails($id);
}

function checkEvent($data)
{
	$userdetail 	= getSiteUserDetails();
	$currentdate 	= date("Y-m-d");
	$userid 		= isset($userdetail['id']) ? $userdetail['id'] : '';
	$parentid 		= isset($userdetail['parent_id']) ? $userdetail['parent_id'] : '';
	$usertype 		= isset($userdetail['type']) ? $userdetail['type'] : '';
	$userplanend 	= isset($userdetail['subscriptionenddate']) ? date('Y-m-d', strtotime($userdetail['subscriptionenddate'])) : '';
	$strstartdate 	= date("Y-m-d", strtotime($data['start_date']));
	$strenddate 	= date("Y-m-d", strtotime($data['end_date']));

	if($currentdate >= $strstartdate && $currentdate <= $strenddate){
		$btn = "Book now";
		$status = "1";
		if(in_array($usertype, [2, 3])){
			if($userid == $data['user_id']){
				$btn = "Book now";
				$status = "1";
			}elseif($usertype == 4 && $parentid == $data['user_id']){
				$btn = "Book now";
				$status = "1";
			}else{
				$btn = "Booking Not Available";
				$status = "0";
			}
		}elseif($usertype==5 && $currentdate > $userplanend){
			$btn = "Subscription Expired";
			$status = "0";
		}
	}elseif($currentdate <= $strstartdate && $currentdate <= $strenddate){
		$btn = "Upcoming";
		$status = "1";
	}
	else{
		$btn = "Closed";
		$status = "0";
	}

	return ['btn' => $btn, 'status' => $status];
}

function getStallManagerIDS($parentid)
{	
	$users 		= new \App\Models\Users;	
	$result		= $users->getUsers('all', ['users'], ['parentid' => $parentid, 'status' => ['1']]);
	return array_column($result, 'id');
}

function checkSubscription()
{
	$date = date('Y-m-d');
	$userdetails = getSiteUserDetails();
	$type = '0';
	$facility = '0';
	$producer = '0';
	$stallmanager = '0';
	
	if(isset($userdetails)){
		$type = $userdetails['type'];
		
		if($type=='2' && $date < $userdetails['subscriptionenddate']){
			$facility = '1';
		}
		
		if($type=='3'){
			$producer = $userdetails['producer_count'];
		}

		if($type=='4' && $date < $userdetails['subscriptionenddate']){
			$stallmanager = '1';
		}
	}
	
	return ['type' => $type, 'facility' => $facility, 'producer' => $producer, 'stallmanager' => $stallmanager];
}

function dateformat($date, $type='')
{
	if ($type == '1') return date('m-d-Y H:i:s', strtotime($date));
	else return date('m-d-Y', strtotime($date));
}

function filedata($file, $path, $extras=[])
{
	$sourceimg 			= (in_array('profile', $extras)) ? base_url().'/assets/images/profile.jpg' : base_url().'/assets/images/upload.png';
	$pdfimg 			= base_url().'/assets/images/pdf.png';
	$relativepath		= str_replace(base_url(), '.', $path);
	
	if($file!=''){
		$explodefile 	= explode('.', $file);
		$ext 			= array_pop($explodefile);
		$image 			= (in_array($ext, ['pdf', 'tiff'])) ? $pdfimg : (file_exists($relativepath.$file) ? $path.$file : $sourceimg);
	}else{
		$image 			= $sourceimg;
	}
	
	return [$file, $image];
}

function filemove($file, $destination)
{
	createDirectory($destination);
	
	$source 			= './assets/uploads/temp/'.$file;
	$destination 		= $destination.'/'.$file;
		
	if(file_exists($source)) rename($source, $destination);
}

function createDirectory($path)
{
	$location = explode('/', $path);
	for($i=0; $i<count($location); $i++)
	{
		if($location[$i]!='.'){
			$dir = implode('/', array_slice($location, 0, $i+1));
			if(!is_dir($dir))
			{
				$mask = umask(0);
				mkdir($dir, 0755);
				umask($mask);
			}
		}
	}
}

function send_mail($to,$subject,$message)
{
	$email = \Config\Services::email();

	$config['protocol'] 	= 'smtp';
	$config['SMTPHost'] 	= 'mail.itfhrm.com';
	$config['SMTPUser'] 	= 'info@itfhrm.com';
	$config['SMTPPass'] 	= 'itFlex@123';
	$config['SMTPPort'] 	= '587';
	$config['SMTPCrypto'] 	= 'tls';
	$config['charset']  	= 'iso-8859-1';
	$config['wordWrap'] 	= true;

	$email->initialize($config);

	$email->setFrom('info@itfhrm.com', 'Ezstall');
	$email->setTo($to);
	$email->setSubject($subject);
	$email->setMessage($message);

    if($email->send()){
        return "sent";
    }else{
        print_r($email->printDebugger());exit;
        return "not sent";
    }

} 

function getUsersList($data=[])
{    
    $users         =     new \App\Models\Users;;    
    $result        =     $users->getUsers('all', ['users'], ['status' => ['1']]+$data);
    
    if(count($result) > 0) return ['' => 'Select User']+array_column($result, 'name', 'id');
    else return [];    
}


function formatdate($date, $type=''){
    if($type==''){
		$date = explode('-', $date);
		return $date[2].'-'.$date[0].'-'.$date[1]; //m-d-Y to Y-m-d
	}elseif($type=='1'){
		return date("m-d-Y", strtotime($date)); //Y-m-d to m-d-Y
	}elseif($type=='2'){
		return date('m-d-Y h:i A',strtotime($date)); //Y-m-d H:i:s to m-d-Y h:i A
	}
}

function formattime($time, $type=''){
    if($type==''){
    	return date('h:i A', strtotime($time)); //24 hours to 12 hours
	}elseif($type=='1'){
		return date("H:i A", strtotime($time));; //12 hours to 24 hours
	}
}

function getCart($type=''){ 
	$request 		= service('request');
    $condition 		= getSiteUserID() ? ['user_id' => getSiteUserID(), 'ip' => $request->getIPAddress()] : ['user_id' => 0, 'ip' =>$request->getIPAddress()] ;
	if($type!='') $condition['type'] = $type;
	$cart 		    = new \App\Models\Cart;
	$result         = $cart->getCart('all', ['cart', 'event', 'barn', 'stall'], $condition);
	
	if($result){
		$barnstall = [];
		foreach ($result as $res) {
			$barnstall[] = [
				'barn_id' => $res['barn_id'], 
				'barn_name' => $res['barnname'], 
				'stall_id' => $res['stall_id'],
				'stall_name' => $res['stallname'] 
			];
		}

		$event_id 				= array_unique(array_column($result, 'event_id'))[0];
		$event_name 			= array_unique(array_column($result, 'eventname'))[0];
		$event_location 		= array_unique(array_column($result, 'eventlocation'))[0];
		$event_description 		= array_unique(array_column($result, 'eventdescription'))[0];
	    $check_in       		= formatdate(array_unique(array_column($result, 'check_in'))[0], 1);
	    $check_out      		= formatdate(array_unique(array_column($result, 'check_out'))[0], 1);
	    $start          		= strtotime(array_unique(array_column($result, 'check_in'))[0]);
		$end            		= strtotime(array_unique(array_column($result, 'check_out'))[0]);
		$daydiff           		= ceil(abs($start - $end) / 86400);
		$interval           	= $daydiff==0 ? 1 : $daydiff;
		$price          		= array_sum(array_column($result, 'price'));
		$type          			= array_unique(array_column($result, 'type'))[0];
		
		return [
			'event_id' => $event_id, 
			'event_name' => $event_name, 
			'event_location' => $event_location, 
			'event_description' => $event_description, 
			'barnstall'=> $barnstall, 
			'price' => $price * $interval, 
			'interval' => $interval, 
			'check_in' => $check_in,
			'check_out' => $check_out,
			'type' => $type
		];	
	}else{
		return false;
	}
}

function getOccupied($eventid, $extras=[]){
	$condition 	= ['eventid' => $eventid,  'status' => '1'];
	if(count($extras) > 0) $condition 	= $condition+$extras;
		
	$booking	= new \App\Models\Booking;
	$booking 	= $booking->getBooking('all', ['booking','barnstall'], $condition);
	
	$occupied = [];
	foreach ($booking as  $bookdata) {
		$barnstall = $bookdata['barnstall'];
		$occupied[] = implode(',', array_column($barnstall, 'stall_id'));
	}

	return (count($occupied) > 0) ? explode(',', implode(',', $occupied)) : [];
}

function getReserved($eventid, $extras=[]){
	$condition 	= ['eventid' => $eventid];
	if(count($extras) > 0) $condition 	= $condition+$extras;
	
	$cart	= new \App\Models\Cart;
	$cart	= $cart->getCart('all', ['cart'], $condition);
	
	return (count($cart) > 0) ? array_column($cart, 'user_id', 'stall_id') : [];
}

function upcomingEvents()
{
	$event	= new \App\Models\Event;
	return $event->getEvent('all', ['event'],['status' => ['1'], 'start_date' => date('Y-m-d')], ['orderby' => 'e.id desc', 'limit' => '3', 'type' => '1']);
}

function getSettings()
{
	$settings = new \App\Models\Settings;
    return $settings->getSettings('row', ['settings'], ['id' => '1']);
}

function getBooking($condition=[])
{
	$booking = new \App\Models\Booking;
	return $booking->getBooking('all', ['booking','users','barnstall'], $condition);
}