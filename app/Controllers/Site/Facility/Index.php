<?php 

namespace App\Controllers\Site\Facility;

use App\Controllers\BaseController;
use App\Models\Event;
use App\Models\Users;
use App\Models\Stall;

class Index extends BaseController
{
	public function __construct()
	{
		$this->event   	= new Event();
		$this->users 	= new Users();
		$this->stall    = new Stall();
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

		if($this->request->getGet('name')!="")   			$searchdata['lname']    			= $this->request->getGet('name');
		if($this->request->getGet('no_of_stalls')!="")   	$searchdata['no_of_stalls']    		= $this->request->getGet('no_of_stalls');
		
		$facilitycount = count($this->event->getEvent('all', ['event', 'stallavailable'], $searchdata+['status'=> ['1'], 'type' => '2']));
		$facility = $this->event->getEvent('all', ['event', 'barn', 'stallavailable'], $searchdata+['status'=> ['1'], 'start' => $offset, 'length' => $perpage, 'type' => '2']);
	
		$data['eventdetail'] = $userdetail;
		$data['userdetail'] = $userdetail;
		$data['usertype'] = $this->config->usertype;
		$data['list'] = $facility;
		$data['searchdata'] = $searchdata;
        $data['pager'] = $pager->makeLinks($page, $perpage, $facilitycount);
		
    	return view('site/facility/list', $data);
    }
	
	public function detail($id)
    {  
		$currentdate = date("Y-m-d");
		$data['detail'] = $event = $this->event->getEvent('row', ['event', 'barn', 'stall'],['id' => $id, 'type' =>'2']);

		$data['detail'] 			= $event;
		$data['settings']  			= getSettings();
		$data['currencysymbol']  	= $this->config->currencysymbol;

    	return view('site/facility/detail',$data);
    }
}
