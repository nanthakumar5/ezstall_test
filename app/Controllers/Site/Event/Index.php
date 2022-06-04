<?php 

namespace App\Controllers\Site\Event;

use App\Controllers\BaseController;
use App\Models\Event;
use App\Models\Users;

class Index extends BaseController
{
	public function __construct()
	{
		$this->event   	= new Event();
		$this->users 	= new Users();
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
		$event = $this->event->getEvent('row', ['event', 'barn', 'stall'],['id' => $id, 'type' =>'1']);
		$data['checkevent'] 		= checkEvent($event);
		$data['detail']  			= $event;
		$data['settings']  			= getSettings();
		$data['currencysymbol']  	= $this->config->currencysymbol;
		
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
}
