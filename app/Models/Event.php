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

		if(in_array('latlong', $querydata)){
			$distance  = ['(((acos(sin(('.$requestdata['latitude'].'*pi()/180)) * sin((`e`.`latitude`*pi()/180))+cos(("'.$requestdata['latitude'].'"*pi()/180)) * cos((`e`.`latitude`*pi()/180)) * cos((("'.$requestdata['longitude'].'"-`e`.`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance'];
			$select[] 	= 	implode(',', $distance);
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
		
		if(isset($requestdata['radius'])) 				$query->having('distance <=', $requestdata['radius']);		
		if(isset($requestdata['id'])) 					$query->where('e.id', $requestdata['id']);
		if(isset($requestdata['location'])) 			$query->where('e.location', $requestdata['location']);
		if(isset($requestdata['status'])) 				$query->whereIn('e.status', $requestdata['status']);
		if(isset($requestdata['userid'])) 				$query->where('e.user_id', $requestdata['userid']);
		if(isset($requestdata['userids'])) 				$query->whereIn('e.user_id', $requestdata['userids']);
		if(isset($requestdata['llocation'])) 			$query->like('e.location', $requestdata['llocation']);
		if(isset($requestdata['lname'])) 				$query->like('e.name', $requestdata['lname']);
		if(isset($requestdata['type'])) 				$query->where('e.type', $requestdata['type']);
		if(isset($requestdata['latitude'])) 			$query->where('e.latitude <=', $requestdata['latitude']);
		if(isset($requestdata['longitude'])) 			$query->where('e.longitude >=', $requestdata['longitude']);

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
			}elseif($type=='row'){
				$result = $query->getRowArray();
			}
			
			$result = $this->getEventBarnStall($type, $querydata, $requestdata, ['result' => $result, 'type' => '1', 'barnname' => 'barn', 'stallname' => 'stall', 'bookedstall' => 'bookedstall']);
			$result = $this->getEventBarnStall($type, $querydata, $requestdata, ['result' => $result, 'type' => '2', 'barnname' => 'rvbarn', 'stallname' => 'rvstall', 'bookedstall' => 'rvbookedstall']);
			$result = $this->getEventProducts($type, $querydata, $requestdata, ['result' => $result, 'type' => '1', 'productname' => 'feed']);
			$result = $this->getEventProducts($type, $querydata, $requestdata, ['result' => $result, 'type' => '2', 'productname' => 'shaving']);
		}
		
		return $result;
    }

    public function getEventBarnStall($type, $querydata, $requestdata, $extras)
    {	
		$result = $extras['result'];
		$barnname = $extras['barnname'];
		$stallname = $extras['stallname'];
		$bookedstall = $extras['bookedstall'];
		
    	if($type=='all'){
    		if(count($result) > 0){
				if(in_array($barnname, $querydata)){
					foreach ($result as $key => $eventdata) {
						$barndatas = $this->db->table('barn b')->where(['b.status' => '1', 'b.event_id' => $eventdata['id'], 'b.type' => $extras['type']])->get()->getResultArray();						
						$result[$key][$barnname] = $barndatas;

						if(in_array($stallname, $querydata) && count($barndatas) > 0){ 
							foreach($barndatas as $barnkey => $barndata){
								$stalldatas = $this->db->table('stall s')->where(['s.status' => '1', 's.barn_id' => $barndata['id'], 's.type' => $extras['type']])->get()->getResultArray();
								$result[$key][$barnname][$barnkey][$stallname] = $stalldatas;
								
								if(in_array($bookedstall, $querydata)){
									foreach($stalldatas as $stallkey => $stalldata){
										$bookedstalls = 	$this->db->table('booking_details bd')
																->join('booking bk', 'bd.booking_id = bk.id', 'left')
																->join('payment_method pm','bk.paymentmethod_id = pm.id' )
																->select('concat(bk.firstname, " ", bk.lastname) name, bk.check_in, bk.check_out, bk.status, (pm.name) paymentmethod, bk.amount amount')
																->where(['bd.stall_id' => $stalldata['id'], 'bd.barn_id' => $barndata['id'], 'bk.event_id' => $eventdata['id']])
																->get()
																->getResultArray();

										$result[$key][$barnname][$barnkey][$stallname][$stallkey][$bookedstall] = $bookedstalls;
									}
								}
							}
						}
					}
				}
			}
    	}else if($type=='row'){ 
			if($result){
				if(in_array($barnname, $querydata)){
					$barndatasdata = $this->db->table('barn b')->where(['b.status' => '1', 'b.event_id' => $result['id'], 'b.type' => $extras['type']])->get()->getResultArray();
					$result[$barnname] = $barndatasdata;

					if(in_array($stallname, $querydata) && count($barndatasdata) > 0){ 
						foreach($barndatasdata as $barnkey => $barndata){
							$stalldatas = $this->db->table('stall s')->where(['s.status' => '1', 's.barn_id' => $barndata['id'], 's.type' => $extras['type']])->get()->getResultArray();
							if(isset($requestdata['fenddate'])) $query->where('s.end_date <=', date('Y-m-d', strtotime($requestdata['fenddate'])));
							$result[$barnname][$barnkey][$stallname] = $stalldatas;
							
							if(in_array($bookedstall, $querydata)){
								foreach($stalldatas as $stallkey => $stalldata){
									if(in_array('bookedstall', $querydata)){ 
										$bookedstalls = 	$this->db->table('booking_details bd')
															->join('booking bk', 'bd.booking_id = bk.id', 'left')
															->join('payment_method pm','bk.paymentmethod_id = pm.id' )
															->select('concat(bk.firstname, " ", bk.lastname) name, bk.status, bk.check_in, bk.check_out, (pm.name) paymentmethod, bk.amount amount')
															->where(['bd.stall_id' => $stalldata['id'], 'bd.barn_id' => $barndata['id'], 'bk.event_id' => $result['id']])
															->get()
															->getResultArray();
														
										$result[$barnname][$barnkey][$stallname][$stallkey][$bookedstall] = $bookedstalls;
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

    public function getEventProducts($type, $querydata, $requestdata, $extras)
    {	
		$result 		= $extras['result'];
		$productname 	= $extras['productname'];
		
    	if($type=='all'){
			if(in_array($productname, $querydata) && count($result) > 0){
				foreach ($result as $key => $eventdata) {
					$productsdata = $this->db->table('products p')->where(['p.status' => '1', 'p.event_id' => $eventdata['id'], 'p.type' => $extras['type']])->get()->getResultArray();
					$result[$key][$productname] = $productsdata;
				}
			}
    	}else if($type=='row'){
			if(in_array($productname, $querydata) && $result){
				$productsdata = $this->db->table('products p')->where(['p.status' => '1', 'p.event_id' => $result['id'], 'p.type' => $extras['type']])->get()->getResultArray();
				$result[$productname] = $productsdata;
			}
		}
		
		return $result;
    }
	
	public function action($data)
	{ 	
		$this->db->transStart();

		$datetime			= date('Y-m-d H:i:s');
		$actionid 			= (isset($data['actionid'])) ? $data['actionid'] : '';
		$userid				= $data['userid'];
		
		$request['user_id'] = $userid;
		$request['status'] 	= '1';
		if(isset($data['name']) && $data['name']!='')      		        		$request['name'] 				= $data['name'];
		if(isset($data['description']) && $data['description']!='')     		$request['description']    	 	= $data['description'];
		if(isset($data['location']) && $data['location']!='')           				$request['location'] 			= $data['location'];
		if(isset($data['city']) && $data['city']!='')           				$request['city'] 			= $data['city'];
		if(isset($data['state']) && $data['state']!='')           				$request['state'] 			= $data['state'];
		if(isset($data['zipcode']) && $data['zipcode']!='')           			$request['zipcode'] 			= $data['zipcode'];
		if(isset($data['latitude']) && $data['latitude']!='')           		$request['latitude'] 			= $data['latitude'];
		if(isset($data['longitude']) && $data['longitude']!='')           		$request['longitude'] 			= $data['longitude'];
		if(isset($data['mobile']) && $data['mobile']!='')      	        		$request['mobile'] 				= $data['mobile'];
		if(isset($data['start_date']) && $data['start_date']!='')       		$request['start_date']			= date('Y-m-d', strtotime($data['start_date']));
		if(isset($data['end_date']) && $data['end_date']!='')           		$request['end_date'] 			= date('Y-m-d', strtotime($data['end_date']));
		if(isset($data['start_time']) && $data['start_time']!='')       		$request['start_time'] 			= $data['start_time'];
		if(isset($data['end_time']) && $data['end_time']!='')           		$request['end_time'] 			= $data['end_time'];
		if(isset($data['stalls_price']) && $data['stalls_price']!='')   		$request['stalls_price']		= $data['stalls_price'];
		if(isset($data['rvspots_price']) && $data['rvspots_price']!='') 		$request['rvspots_price'] 		= $data['rvspots_price'];
		if(isset($data['feed_flag']) && $data['feed_flag']!='') 				$request['feed_flag'] 			= $data['feed_flag'];
		if(isset($data['shaving_flag']) && $data['shaving_flag']!='') 			$request['shaving_flag'] 		= $data['shaving_flag'];
		if(isset($data['rv_flag']) && $data['rv_flag']!='') 					$request['rv_flag'] 			= $data['rv_flag'];
		if(isset($data['charging_flag']) && $data['charging_flag']!='') 		$request['charging_flag'] 		= $data['charging_flag'];
		if(isset($data['notification_flag']) && $data['notification_flag']!='') $request['notification_flag'] 	= $data['notification_flag'];
		if(isset($data['cleaning_flag']) && $data['cleaning_flag']!='') 		$request['cleaning_flag'] 	= $data['cleaning_flag'];
		if(isset($data['cleaning_fee']) && $data['cleaning_fee']!='') 			$request['cleaning_fee'] 	= $data['cleaning_fee'];
		if(isset($data['type']) && $data['type']!='')    		 				$request['type'] 				= $data['type'];
		
		if(isset($data['image']) && $data['image']!=''){
 			$request['image'] = $data['image'];	
 			if(filemove($data['image'], './assets/uploads/temp')== $data['image']){	
				filemove($data['image'], './assets/uploads/event');	
			}	
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
		 
		if(isset($data['barn']) && count($data['barn']) > '0') 				$this->barnstallaction($data['barn'], [$eventinsertid, $data['type'], '1']);
		if(isset($data['rvhookups']) && count($data['rvhookups']) > '0')	$this->barnstallaction($data['rvhookups'], [$eventinsertid, $data['type'], '2']);
		if(isset($data['feed']) && count($data['feed']) > '0') 				$this->productsaction($data['feed'], [$eventinsertid, '1']);
		if(isset($data['shavings']) && count($data['shavings']) > '0') 		$this->productsaction($data['shavings'], [$eventinsertid, '2']);
		
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
	
	public function barnstallaction($data, $extras)
	{
		$barnidcolumn = array_filter(array_column($data, 'id'));
		if(count($barnidcolumn)){
			$this->db->table('barn')->whereNotIn('id', $barnidcolumn)->update(['status' => '0'], ['event_id' => $extras[0], 'type' => $extras[2]]);
		}
		
		foreach($data as $barndata){
			$barnid       		= $barndata['id']!='' ? $barndata['id'] : '';
			$barn['event_id'] 	= $extras[0];
			$barn['name']     	= $barndata['name'];
			$barn['status']     = '1';
			$barn['type']		= $extras[2];
			
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
					$this->db->table('stall')->whereNotIn('id', $stallidcolumn)->update(['status' => '0'], ['barn_id' => $barninsertid, 'type' => $extras[2]]);
				}
				
				foreach($barndata['stall'] as $stalldata){  
					$stallid        	 	= $stalldata['id']!='' ? $stalldata['id'] : '';
					$stall['event_id'] 	 	= $extras[0];
					$stall['barn_id']    	= $barninsertid;
					$stall['charging_id']  	= isset($stalldata['chargingflag']) ? $stalldata['chargingflag'] : '' ;
					$stall['name']       	= $stalldata['name'];
					$stall['price']      	= $stalldata['price'];
					$stall['block_unblock'] = isset($stalldata['block_unblock']) ? $stalldata['block_unblock'] : 0;
					$stall['status']     	= $stalldata['status'];
					$stall['type']		 	= $extras[2];
					
					if(isset($stalldata['image']) && $stalldata['image']!=''){
						$stall['image'] = $stalldata['image'];		
						filemove($stalldata['image'], './assets/uploads/stall');		
					}
					
					if($stallid==''){
						if($extras[1] == '2'){ 
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
	
	public function productsaction($data, $extras)
	{
		$productsidcolumn = array_filter(array_column($data, 'id'));
		if(count($productsidcolumn)){
			$this->db->table('products')->whereNotIn('id', $productsidcolumn)->update(['status' => '0'], ['event_id' => $extras[0], 'type' => $extras[1]]);
		}

		foreach($data as $productsdata){
			$productsid        	 			= $productsdata['id']!='' ? $productsdata['id'] : '';
			$productsdata['event_id'] 		= $extras[0];
			$productsdata['name']       	= $productsdata['name'];
			$productsdata['price']      	= $productsdata['price'];
			$productsdata['status']     	= $productsdata['status'];
			$productsdata['type']     		= $extras[1];
			
			
			if($productsid==''){
				$this->db->table('products')->insert($productsdata);
			}else {
			   $this->db->table('products')->update($productsdata, ['id' => $productsid]);
			}	
		}
	}
}