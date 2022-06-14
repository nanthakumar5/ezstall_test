<?php

namespace App\Models;

use App\Models\BaseModel;

class Event extends BaseModel
{	
	public function getEvent($type, $querydata=[], $requestdata=[], $extras=[])
    {		
    	$select 			= [];
		
		if(in_array('event', $querydata)){
			$data		= 	['e.*'];							
			$select[] 	= 	implode(',', $data);
		}

		if(in_array('stallavailable', $querydata)){
			$condition = '';
			if(isset($requestdata['btw_start_date']) && !isset($requestdata['btw_end_date'])) 	$condition .= " and '".$requestdata['btw_start_date']."' BETWEEN e.start_date AND e.end_date";
			if(!isset($requestdata['btw_start_date']) && isset($requestdata['btw_end_date'])) 	$condition .= " and '".$requestdata['btw_end_date']."' BETWEEN e.start_date AND e.end_date";
			if(isset($requestdata['btw_start_date']) && isset($requestdata['btw_end_date'])) 	$condition .= " and ('".$requestdata['btw_start_date']."' BETWEEN e.start_date AND e.end_date or '".$requestdata['btw_end_date']."' BETWEEN e.start_date AND e.end_date)";
			
			if($condition!=''){
				$select[] = '((select count("*") from  stall as s where s.event_id = e.id and s.status="1") - (select  count("*") from  booking as b left join booking_details as bd on bd.booking_id = b.id where b.event_id = e.id '.$condition.')) as stallavailable';							
			}else{
				$select[] = '(select count("*") from  stall as s where s.event_id = e.id and s.status="1") as stallavailable';							
			}
		}
		
		$query = $this->db->table('event e');
		
		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('e.id', $requestdata['id']);
		if(isset($requestdata['location'])) 			$query->where('e.location', $requestdata['location']);
		if(isset($requestdata['status'])) 				$query->whereIn('e.status', $requestdata['status']);
		if(isset($requestdata['userid'])) 				$query->where('e.user_id', $requestdata['userid']);
		if(isset($requestdata['userids'])) 				$query->whereIn('e.user_id', $requestdata['userids']);
		if(isset($requestdata['llocation'])) 			$query->like('e.location', $requestdata['llocation']);
		if(isset($requestdata['lname'])) 				$query->like('e.name', $requestdata['lname']);
		if(isset($requestdata['type'])) 				$query->where('e.type', $requestdata['type']);

		if(isset($requestdata['start_date'])) 			$query->where('e.start_date >=', date('Y-m-d', strtotime($requestdata['start_date'])));
		if(isset($requestdata['end_date'])) 			$query->where('e.end_date <', date('Y-m-d', strtotime($requestdata['end_date'])));
		if(isset($requestdata['gtenddate'])) 			$query->where('e.end_date >=', $requestdata['gtenddate']);
		if(isset($requestdata['btw_start_date']) && !isset($requestdata['btw_end_date'])) $query->groupStart()->where("'".$requestdata['btw_start_date']."' BETWEEN e.start_date AND e.end_date")->orWhere('e.start_date >=', $requestdata['btw_start_date'])->groupEnd();
		if(!isset($requestdata['btw_start_date']) && isset($requestdata['btw_end_date'])) $query->groupStart()->where("'".$requestdata['btw_end_date']."' BETWEEN e.start_date AND e.end_date")->orWhere('e.end_date <=', $requestdata['btw_end_date'])->groupEnd();
		if(isset($requestdata['btw_start_date']) && isset($requestdata['btw_end_date'])) $query->groupStart()->where("'".$requestdata['btw_start_date']."' BETWEEN e.start_date AND e.end_date")->orWhere("'".$requestdata['btw_end_date']."' BETWEEN e.start_date AND e.end_date")->groupEnd();
		if(isset($requestdata['no_of_stalls'])) 		$query->having('stallavailable >=', $requestdata['no_of_stalls']);
		
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$query->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page']) && $requestdata['page']=='events'){
				$column = ['e.name', 'e.image', 'e.start_date', 'e.location', 'e.mobile', 'e.id'];
				$query->orderBy($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
						
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];
				
				$query->groupStart();
					if($page=='events'){				
						$query->like('e.name', $searchvalue);
						$query->orLike('e.location', $searchvalue);
						$query->orLike('e.mobile', $searchvalue);
					}
				$query->groupEnd();
			}			
		}
		
		if(isset($extras['groupby'])) 					$query->groupBy($extras['groupby']);		
		if(isset($extras['orderby'])) 					$query->orderBy($extras['orderby']);
		if(isset($extras['limit'])) 					$query->limit($extras['limit']);

		if($type=='count'){
			$result = $query->countAllResults();
		}else{
			$query = $query->get(); 
			if($type=='all'){
				$result = $query->getResultArray();

				if(count($result) > 0){
					foreach ($result as $key => $eventdata) {
						if(in_array('barn', $querydata)){
						$barndatas = $this->db->table('barn b')->where('b.status', '1')->where('b.event_id', $eventdata['id'])->get()->getResultArray();
						
						$result[$key]['barn'] = $barndatas;

						if(in_array('stall', $querydata)){ 
							if(count($barndatas) > 0){
								foreach($barndatas as $barnkey => $barndata){
									$stalldatas = $this->db->table('stall s')->where('s.status', '1')->where('s.barn_id', $barndata['id'])->get()->getResultArray();

									$result[$key]['barn'][$barnkey]['stall'] = $stalldatas;
									
									if(in_array('bookedstall', $querydata)){
										foreach($stalldatas as $stallkey => $stalldata){
											if(in_array('bookedstall', $querydata)){ 
												$bookedstall = 	$this->db->table('booking_details bd')
																->join('booking bk', 'bd.booking_id = bk.id', 'left')
																->join('payment_method pm','bk.paymentmethod_id = pm.id' )
																->select('concat(bk.firstname, " ", bk.lastname) name, bk.check_in, bk.check_out, bk.status, (pm.name) paymentmethod')
																->where(['bd.stall_id' => $stalldata['id'], 'bd.barn_id' => $barndata['id'], 'bk.event_id' => $eventdata['id']])
																->get()
																->getResultArray();

												$result[$key]['barn'][$barnkey]['stall'][$stallkey]['bookedstall'] = $bookedstall;
											}
										}
									}
								}
							}
						}
						
					}
					}
				}
			}elseif($type=='row'){
				$result = $query->getRowArray();
				
				if($result){
					if(in_array('barn', $querydata)){
						$barndatas = $this->db->table('barn b')->where('b.status', '1')->where('b.event_id', $result['id'])->get()->getResultArray();
						$result['barn'] = $barndatas;
		
						if(in_array('stall', $querydata)){ 
							if(count($barndatas) > 0){
								foreach($barndatas as $barnkey => $barndata){
									$stalldatas = $this->db->table('stall s')->where('s.status', '1')->where('s.barn_id', $barndata['id'])->get()->getResultArray();	

									if(isset($requestdata['fenddate'])) $query->where('s.end_date <=', date('Y-m-d', strtotime($requestdata['fenddate'])));

									$result['barn'][$barnkey]['stall'] = $stalldatas;
									
									if(in_array('bookedstall', $querydata)){
										foreach($stalldatas as $stallkey => $stalldata){
											if(in_array('bookedstall', $querydata)){ 
												$bookedstall = 	$this->db->table('booking_details bd')
																->join('booking bk', 'bd.booking_id = bk.id', 'left')
																->join('payment_method pm','bk.paymentmethod_id = pm.id' )
																->select('concat(bk.firstname, " ", bk.lastname) name, bk.status, bk.check_in, bk.check_out, (pm.name) paymentmethod')
																->where(['bd.stall_id' => $stalldata['id'], 'bd.barn_id' => $barndata['id'], 'bk.event_id' => $result['id']])
																->get()
																->getResultArray();
																
												$result['barn'][$barnkey]['stall'][$stallkey]['bookedstall'] = $bookedstall;
											}
										}
									}
								}
							}
						}
						
					}

				}
			}
		}
	
		return $result;
    }
	
	public function action($data)
	{ 	//echo "<pre>";print_r($data);die;
		$this->db->transStart();

		$datetime			= date('Y-m-d H:i:s');
		$actionid 			= (isset($data['actionid'])) ? $data['actionid'] : '';
		$userid				= $data['userid'];
		
		$request['user_id'] = $userid;
		$request['status'] 	= '1';
		if(isset($data['name']) && $data['name']!='')      		        		$request['name'] 			= $data['name'];
		if(isset($data['description']) && $data['description']!='')     		$request['description']     = $data['description'];
		if(isset($data['location']) && $data['location']!='')           		$request['location'] 		= $data['location'];
		if(isset($data['mobile']) && $data['mobile']!='')      	        		$request['mobile'] 			= $data['mobile'];
		if(isset($data['start_date']) && $data['start_date']!='')       		$request['start_date']		= date('Y-m-d', strtotime($data['start_date']));
		if(isset($data['end_date']) && $data['end_date']!='')           		$request['end_date'] 		= date('Y-m-d', strtotime($data['end_date']));
		if(isset($data['start_time']) && $data['start_time']!='')       		$request['start_time'] 		= $data['start_time'];
		if(isset($data['end_time']) && $data['end_time']!='')           		$request['end_time'] 		= $data['end_time'];
		if(isset($data['stalls_price']) && $data['stalls_price']!='')   		$request['stalls_price']	= $data['stalls_price'];
		if(isset($data['rvspots_price']) && $data['rvspots_price']!='') 		$request['rvspots_price'] 	= $data['rvspots_price'];
		if(isset($data['status']) && $data['status']!='')      		    		$request['status'] 			= $data['status'];
		if(isset($data['type']) && $data['type']!='')    		 				$request['type'] 			= $data['type'];
		
		if(isset($data['image']) && $data['image']!=''){
 			$request['image'] = $data['image'];		
			filemove($data['image'], './assets/uploads/event');		
		}
		
		if(isset($data['eventflyer']) && $data['eventflyer']!=''){
 			$request['eventflyer'] = $data['eventflyer'];		
			filemove($data['eventflyer'], './assets/uploads/eventflyer');		
		}

		if(isset($data['profile_image']) && $data['profile_image']!=''){
 			$request['profile_image'] = $data['profile_image'];		
			filemove($data['profile_image'], './assets/uploads/profile');		
		}
		
		if(isset($data['stallmap']) && $data['stallmap']!=''){
 			$request['stallmap'] = $data['stallmap'];		
			filemove($data['stallmap'], './assets/uploads/stallmap');		
		}
		
		if(isset($request)){				
			$request['updated_at'] 	= $datetime;
			$request['updated_by'] 	= $userid;						
			
			if($actionid==''){
				$request['created_at'] 		= 	$datetime;
				$request['created_by'] 		= 	$userid;
				
				$event = $this->db->table('event')->insert($request);
				$eventinsertid = $this->db->insertID();
			}else{
				$event = $this->db->table('event')->update($request, ['id' => $actionid]);
				$eventinsertid = $actionid;
			}
		}
		 
		if(isset($data['barn']) && count($data['barn']) > '0'){
			$barnidcolumn = array_filter(array_column($data['barn'], 'id'));
			if(count($barnidcolumn)){
				$this->db->table('barn')->whereNotIn('id', $barnidcolumn)->update(['status' => '0'], ['event_id' => $eventinsertid]);
			}
			
			foreach($data['barn'] as $barndata){
				$barnid       		= $barndata['id']!='' ? $barndata['id'] : '';
				$barn['event_id'] 	= $eventinsertid;
				$barn['name']     	= $barndata['name'];
				$barn['status']     = '1';
				
				if($barnid==''){
					$this->db->table('barn')->insert($barn);
					$barninsertid = $this->db->insertID();
				}else {
				   $this->db->table('barn')->update($barn, ['id' => $barnid]);
				   $barninsertid = $barnid;
				}	
				
				if(isset($barndata['stall']) && count($barndata['stall']) > 0){ 
        			$stallidcolumn = array_filter(array_column($barndata['stall'], 'id'));
        			if(count($stallidcolumn)){
        				$this->db->table('stall')->whereNotIn('id', $stallidcolumn)->update(['status' => '0'], ['barn_id' => $barninsertid]);
        			}
					
        			foreach($barndata['stall'] as $stalldata){
        				$stallid        	 = $stalldata['id']!='' ? $stalldata['id'] : '';
        				$stall['event_id'] 	 = $eventinsertid;
        				$stall['barn_id']    = $barninsertid;
        				$stall['name']       = $stalldata['name'];
        				$stall['price']      = $stalldata['price'];
        				$stall['status']     = $stalldata['status'];
        				

        				if(isset($stalldata['image']) && $stalldata['image']!=''){
				 			$stall['image'] = $stalldata['image'];		
							filemove($stalldata['image'], './assets/uploads/stall');		
						}
        				
        				if($stallid==''){
        					if($data['type'] == '2'){ 
								$stall['start_date']  	= date('Y-m-d');
								$stall['end_date'] 	  	= date('Y-m-d', strtotime('+1 year', strtotime($stall['start_date'])));
							}
        					
        					$this->db->table('stall')->insert($stall);
        				}else {
        				   $this->db->table('stall')->update($stall, ['id' => $stallid]);
        				}	
        			}
        		}
			}
		}
		
		if(isset($eventinsertid) && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return $eventinsertid;
		}
	}

	public function delete($data)
	{
		$this->db->transStart();
		
		$datetime		= date('Y-m-d H:i:s');
		$userid			= $data['userid'];
		$id 			= $data['id'];
		
		$event 			= $this->db->table('event')->update(['updated_at' => $datetime, 'updated_by' => $userid, 'status' => '0'], ['id' => $id]);
		$this->db->table('barn')->update(['status' => '0'], ['event_id' => $id]);
		$this->db->table('stall')->update(['status' => '0'], ['event_id' => $id]);
		
		if($event && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return true;
		}
	}
}