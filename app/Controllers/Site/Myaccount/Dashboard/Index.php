<?php 
namespace App\Controllers\Site\Myaccount\Dashboard;

use App\Controllers\BaseController;
use App\Models\Event;
use App\Models\Booking;

class Index extends BaseController
{
	public function __construct()
	{	
		$this->event = new Event();
		$this->booking = new Booking();
	}
    
    public function index()
    { 	
		$countcurrentstall 		= 0;
      	$countcurrentbooking 	= 0;
      	$countpastevent 		= [];
      	$countpaststall 		= 0;
      	$countpastamount 		= 0;
      	$countpayedamount		= 0;
      	$countcurrentstall 		= 0;
      	$countcurrentevent  	= [];
		
     	$date				= date('Y-m-d');
    	$userdetail 		= getSiteUserDetails();
    	$usertype 			= $userdetail['type'];
    	$parentid 			= $userdetail['parent_id'];
		$parentdetails 		= getSiteUserDetails($parentid);
		$parenttype   		= $parentdetails ? $parentdetails['type'] : '';
    	$data['usertype'] 	= $this->config->usertype;
    	$userid 			= ($usertype=='4') ? $parentdetails['id'] : $userdetail['id'];
		$allids 			= getStallManagerIDS($userid); 
		array_push($allids, $userid);
		
      	if($usertype=='3' || ($usertype=='4' && $parenttype == '3')){ 
      		$currentreservation = $this->event->getEvent('all', ['event', 'barn', 'stall'],['status' => ['1'], 'userids' => $allids, 'type' => '1', 'gtenddate' => $date]);
      	}
      	if($usertype=='2' || ($usertype=='4' && $parenttype == '2')){
      		$currentreservation = $this->event->getEvent('all', ['event', 'barn', 'stall'],['status' => ['1'], 'userids' => $allids, 'type' => '2', 'fenddate' => $date]);
      	}
      	
      	if($usertype=='2' || $usertype =='3' || ($usertype=='4' && $parenttype == '2') || ($usertype=='4' && $parenttype == '3')){
	  		foreach ($currentreservation as $event) { 
	  			foreach ($event['barn'] as $barn) {
					$countcurrentstall += count(array_column($barn['stall'], 'id'));
				}
			
				$bookedevents = $this->booking->getBooking('all', ['booking','event','barnstall'],['eventid'=> $event['id'], 'status' => '1']);
				if(count($bookedevents) > 0){
					foreach($bookedevents as $bookedevent){
						$barnstall = $bookedevent['barnstall'];
						if(count($barnstall) > 0) $countcurrentbooking += count(array_column($barnstall, 'stall_id'));
					}
				}
	      	}
      	
	      	$pastevent = $this->booking->getBooking('all', ['booking','event','payment','barnstall'],['userid'=> $allids, 'ltenddate' => $date, 'status' => '1']);

			foreach ($pastevent as $event) {  
	  			$countpastevent[] = $event['event_id'];
	  			$barnstall = $event['barnstall'];
	  			if(count($barnstall) > 0) $countpaststall += count(array_column($barnstall, 'stall_id'));
	  			$countpastamount += $event['amount'];
	      	}
      	}
		

		$data['monthlyincome'] = $this->booking->getBooking('all', ['booking', 'event', 'payment'],['userid'=> $allids, 'status' => '1'], ['groupby' => 'DATE_FORMAT(b.created_at, "%M %Y")', 'select' => 'SUM(p.amount) as paymentamount, DATE_FORMAT(b.created_at, "%M %Y") AS month']);


		if($usertype=='2' || ($usertype=='4' && $parenttype == '2')){
			$data['upcomingevents'] = $this->event->getEvent('all', ['event'],['userids' => $allids, 'fenddate'=> $date, 'status' => ['1'], 'type' => '2']);
		}
		if($usertype=='3' || ($usertype=='4' && $parenttype == '3')){
			$data['upcomingevents'] = $this->event->getEvent('all', ['event'],['userids' => $allids, 'start_date' => $date, 'status' => ['1'], 'type' => '1']);
		}
    	
    	if($usertype=='5'){

    		$horseevent = $this->booking->getBooking('all', ['booking','event','payment','barnstall'],['userid'=> $allids,'ltcheck_out' => $date, 'status' => '1']);

    		foreach ($horseevent as $event) {  
	  			$countpastevent[] = $event['event_id'];
	  			$barnstall = $event['barnstall'];
	  			if(count($barnstall) > 0) $countpaststall += count(array_column($barnstall, 'stall_id'));
	  			$countpastamount += $event['amount'];
	      	}

    		$currentreservation = $this->booking->getBooking('all', ['booking','event','payment','barnstall'],['userid'=> $allids,'gtcheck_in' => $date, 'status' => '1']);
    		foreach ($currentreservation as $event) {  
	  			$countcurrentevent[] = $event['event_id'];
	  			$barnstall = $event['barnstall'];
	  			if(count($barnstall) > 0) $countcurrentstall += count(array_column($barnstall, 'stall_id'));
	  			$countpayedamount += $event['amount'];
	      	}

      		$data['countcurrentevent'] 	= count(array_unique($countcurrentevent));
    		$data['countpayedamount'] 	= $countpayedamount;
    		$data['countcurrentstall'] 	= $countcurrentstall;
    	}
      	
      	$data['userdetail'] 			= $userdetail;
      	$data['countcurrentstall'] 		= $countcurrentstall; 
      	$data['countcurrentbooking'] 	= $countcurrentbooking;
      	$data['countcurrentavailable'] 	= ($countcurrentstall - $countcurrentbooking);
      	$data['pastevent'] 				= count(array_unique($countpastevent));
      	$data['countpaststall'] 		= $countpaststall;
      	$data['countpastamount'] 		= $countpastamount;
		
		return view('site/myaccount/dashboard/index',$data);
	}
}
