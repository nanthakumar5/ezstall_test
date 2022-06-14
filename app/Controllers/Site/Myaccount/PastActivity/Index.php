<?php
namespace App\Controllers\Site\Myaccount\PastActivity;

use App\Controllers\BaseController;
use App\Models\Booking;

class Index extends BaseController
{
	public function __construct()
	{
		$this->booking = new Booking();	
	}

	public function index()
    {
    	$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  5; 
		$offset = $page * $perpage;
     	$date	= date('Y-m-d');

    	$userdetail = getSiteUserDetails();
    	$userid=$userdetail['id'];
		$allids = getStallManagerIDS($userid);
		array_push($allids, $userid);

		$bookingcount = $this->booking->getBooking('count', ['booking', 'event', 'users'], ['userid' => $allids, 'ltenddate' => $date]);
		$data['bookings'] = $this->booking->getBooking('all', ['booking', 'event', 'users','barnstall','payment','paymentmethod'], ['userid' => $allids, 'ltenddate' => $date, 'start' => $offset, 'length' => $perpage], ['orderby' => 'b.id desc']);
		$data['pager'] = $pager->makeLinks($page, $perpage, $bookingcount);
		$data['bookingstatus'] = $this->config->bookingstatus;
		$data['usertype'] = $this->config->usertype;
		$data['currencysymbol'] = $this->config->currencysymbol;
		
    	return view('site/myaccount/pastactivity/index',$data);

    }
	
	public function view($id)
	{
		
    	$userid = getSiteUserID();

		$result = $this->booking->getBooking('row', ['booking', 'event', 'users','barnstall','payment','paymentmethod'], ['userid' => [$userid], 'id' => $id]);

		if($result){
			$data['result'] = $result;
		}else{
			$this->session->setFlashdata('danger', 'No Record Found.');
			return redirect()->to(base_url().'/myaccount/pastactivity'); 
		}
		$data['usertype'] = $this->config->usertype;
		return view('site/myaccount/pastactivity/view', $data);
	}
}