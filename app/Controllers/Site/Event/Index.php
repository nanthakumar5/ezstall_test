<?php 

namespace App\Controllers\Site\Event;

use App\Controllers\BaseController;
use App\Models\Event;
use App\Models\Users;
use App\Models\Comments;
use App\Models\Booking;
use App\Models\Cart;
use App\Models\Bookingdetails;


class Index extends BaseController
{
	public function __construct()
	{
		$this->event   	= new Event();
		$this->users 	= new Users();
		$this->comments = new Comments();
		$this->booking 	= new Booking();	
		$this->cart 	= new Cart();	
		$this->bookingdetails 	= new Bookingdetails();	
	}
    
    public function lists()
    {	
    	$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  5; 
		$offset = $page * $perpage;
		$userdetail = getSiteUserDetails();

		if($this->request->getVar('q')!==null){
			$searchdata = ['search' => ['value' => $this->request->getVar('q')], 'page' => 'events'];
			$data['search'] = $this->request->getVar('q');
		}else{
			$searchdata = [];
			$data['search'] = '';
		}

		if($this->request->getGet('location')!="")   		$searchdata['llocation']    		= $this->request->getGet('location');
		if($this->request->getGet('start_date')!="")   	 	$searchdata['btw_start_date']    	= formatdate($this->request->getGet('start_date'));
		if($this->request->getGet('end_date')!="")   	 	$searchdata['btw_end_date']    		= formatdate($this->request->getGet('end_date'));
		if($this->request->getGet('no_of_stalls')!="")   	$searchdata['no_of_stalls']    		= $this->request->getGet('no_of_stalls');
		
		$eventcount = count($this->event->getEvent('all', ['event', 'stallavailable'], $searchdata+['status'=> ['1'], 'type' => '1']));
		$event = $this->event->getEvent('all', ['event', 'stallavailable'], $searchdata+['status'=> ['1'], 'start' => $offset, 'length' => $perpage, 'type' => '1'], ['orderby' =>'e.id desc', 'groupby' => 'e.id']);

		$data['eventdetail'] = $userdetail;
		$data['userdetail'] = $userdetail;
		$data['usertype'] = $this->config->usertype;
		$data['list'] = $event;
		$data['searchdata'] = $searchdata;
        $data['pager'] = $pager->makeLinks($page, $perpage, $eventcount);
		
    	return view('site/events/list', $data);
    }
	
	public function detail($id)
    {  	
		if ($this->request->getMethod()=='post'){

			$requestData 	= $this->request->getPost();
        	$result 		= $this->comments->action($requestData);

        	if($result){
				$this->session->setFlashdata('success', 'Your Comment Submitted Successfully');
				return redirect()->to(base_url().'/events/detail/'.$id); 
        	}else {
				$this->session->setFlashdata('danger', 'Try Again');
				return redirect()->to(base_url().'/events/detail/'.$id); 
			}
		}

		$userdetail 		= getSiteUserDetails() ? getSiteUserDetails() : [];
		$userid 			= (isset($userdetail['id'])) ? $userdetail['id'] : 0;
		$usertype 			= (isset($userdetail['type'])) ? $userdetail['type'] : 0;

		$event 		= $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving'],['id' => $id, 'type' =>'1']);

		$bookings 	= $this->booking->getBooking('row', ['booking', 'event'],['user_id' => $userid, 'eventid' => $id,'status'=> ['1']]);
		$comments 	= $this->comments->getComments('all', ['comments','users','replycomments'],['commentid' => '0', 'eventid' => $id,'status'=> ['1']]);

		$data['checkevent'] 		= checkEvent($event);
		$data['detail']  			= $event;
		$data['bookings']  			= $bookings;
		$data['settings']  			= getSettings();
		$data['currencysymbol']  	= $this->config->currencysymbol;
		$data['usertype']			= $usertype;
		
		return view('site/events/detail',$data);
    }
	
	public function downloadeventflyer($filename)
	{  
		$filepath   = base_url().'/assets/uploads/eventflyer/'.$filename;		
		header("Content-Type: application/octet-stream"); 
        header("Content-Disposition: attachment; filename=\"". basename($filepath) ."\"");
        readfile ($filepath);
        exit();
	}

	public function downloadstallmap($filename)
	{ 
		$filepath   = base_url().'/assets/uploads/stallmap/'.$filename;		
		header("Content-Type: application/octet-stream"); 
        header("Content-Disposition: attachment; filename=\"". basename($filepath) ."\"");
        readfile ($filepath);
        exit();
	}

	public function latlong()
	{
		$data 	= $this->request->getPost(); 
		$lat 	= $data['latitude'];
		$long 	= $data['longitude'];
		$radius = 50; 
		$latlongs 	= $this->event->getEvent('all', ['event', 'latlong'], ['radius' => $radius, 'latitude' => $lat, 'longitude' => $long, 'status'=> ['1'], 'type' => '1'], ['orderby' =>'e.id desc', 'groupby' => 'e.id']);
		
		$bookingbtn = [];
		foreach ($latlongs as $key =>  $latlong) {
			$bookingbtn[] = checkEvent($latlong);
		}
		
		echo json_encode(['latlongs' => $latlongs, 'bookingbtn' => $bookingbtn]);
	}

	public function updatereservation($id='')
	{  
		$userdetail 		= getSiteUserDetails() ? getSiteUserDetails() : [];
		$userid 			= (isset($userdetail['id'])) ? $userdetail['id'] : 0;
		$usertype 			= (isset($userdetail['type'])) ? $userdetail['type'] : 0;
		$uri 				= current_url(true);
		$bookingid 			= $uri->getSegment(5);

		if ($this->request->getMethod()=='post'){ 
			$requestData 	= $this->request->getPost();
			$datauncheckedstallid = $requestData['uncheckedstallid'];
			$updatedbookingstall = $requestData['updatedbookingstall'];

			foreach($requestData['uncheckedstallid'] as $akey => $bkids){
				foreach($requestData['updatedbookingstall'] as $bkey => $ubstallid){
					
					if($akey==$bkey){
						$bk = $this->bookingdetails->getBookingdetails('row', ['bookingdetails'],['booking_id' => $requestData['bookingid'],'id' => $bkids['bkid']]);
						$result = $this->bookingdetails->action($bk);

						
						$this->bookingdetails->updatedbkstall(['bdid' => $bkids['bkid'], 'updastallid' => $ubstallid['stallid']]);
						}
				}
			}

			if($result){
				$this->session->setFlashdata('success', 'Your Stall is Updated Successfully');
				return redirect()->to(base_url().'/myaccount/bookings'); 
        	}

		}

		$event 		= $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving'],['id' => $id, 'type' =>'1']);

		$bookings 	= $this->booking->getBooking('row', ['booking', 'event', 'users','barnstall', 'rvbarnstall', 'feed', 'shaving','payment','paymentmethod'],['user_id' => $userid, 'id' => $bookingid,'status'=> ['1']]);



		$data['checkevent'] 		= checkEvent($event);
		$data['detail']  			= $event;
		$data['bookings']  			= $bookings;
		$data['settings']  			= getSettings();
		$data['currencysymbol']  	= $this->config->currencysymbol;
		$data['usertype']			= $usertype;
		
		return view('site/events/updatereservation',$data);
	}
}
