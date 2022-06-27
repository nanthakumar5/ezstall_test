<?php 
namespace App\Models;

use App\Models\BaseModel;

class Booking extends BaseModel
{
	public function getBooking($type, $querydata=[], $requestdata=[], $extras=[])
    {  
    	$select 		= [];
		
		if(in_array('booking', $querydata)){
			$data		= 	['b.*'];							
			$select[] 	= 	implode(',', $data);
		}
		
		if(in_array('event', $querydata)){
			$data		= 	['e.name eventname'];							
			$select[] 	= 	implode(',', $data);
		}

		if(in_array('users', $querydata)){
			$data		= 	['u.name username','u.type usertype'];							
			$select[] 	= 	implode(',', $data);
		}

		if(in_array('payment', $querydata)){
			$data		= 	['p.id paymentid', 'p.stripe_paymentintent_id'];							
			$select[] 	= 	implode(',', $data);
		}
		if(in_array('paymentmethod', $querydata)){
			$data		= 	['pm.name paymentmethod_name'];							
			$select[] 	= 	implode(',', $data);
		}

		$query = $this->db->table('booking b');

		if(in_array('event', $querydata)) 				$query->join('event e', 'e.id=b.event_id', 'left');
		if(in_array('users', $querydata)) 				$query->join('users u', 'u.id=b.user_id', 'left');		
		if(in_array('payment',$querydata))				$query->join('payment p', 'p.id=b.payment_id', 'left');
		if(in_array('paymentmethod',$querydata))		$query->join('payment_method pm', 'pm.id=b.paymentmethod_id', 'left');

		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('b.id', $requestdata['id']);
		if(isset($requestdata['eventid'])) 				$query->where('b.event_id', $requestdata['eventid']);		
		if(isset($requestdata['check_in'])) 			$query->where('b.check_in', $requestdata['check_in']);		
		if(isset($requestdata['check_out'])) 		    $query->where('b.check_out', $requestdata['check_out']);

		if(isset($requestdata['gtcheck_in'])) 			$query->where('b.check_in >=', $requestdata['gtcheck_in']);
		if(isset($requestdata['ltcheck_out'])) 		    $query->where('b.check_out <=', $requestdata['ltcheck_out']);	
		if(isset($requestdata['status'])) 				$query->where('b.status', $requestdata['status']);	

		if(isset($requestdata['userid'])) 				
		{
			$query->groupStart();
				$query->whereIn('b.user_id', $requestdata['userid']);
				$query->orWhereIn('e.user_id', $requestdata['userid']);
			$query->groupEnd();
		} 		
		
		if(isset($requestdata['gtenddate'])){
			$query->groupStart();
				$query->groupStart()->where(['b.type' => '1', 'e.type' => '1', 'e.end_date >=' => date('Y-m-d', strtotime($requestdata['gtenddate']))])->groupEnd();
				$query->orGroupStart()->where(['b.type' => '2', 'e.type' => '2', 'b.check_out >=' => date('Y-m-d', strtotime($requestdata['gtenddate']))])->groupEnd();
			$query->groupEnd();
		}
		
		if(isset($requestdata['ltenddate'])){
			$query->groupStart();
				$query->groupStart()->where(['b.type' => '1', 'e.type' => '1', 'e.end_date <' => date('Y-m-d', strtotime($requestdata['ltenddate']))])->groupEnd();
				$query->orGroupStart()->where(['b.type' => '2', 'e.type' => '2', 'b.check_out <' => date('Y-m-d', strtotime($requestdata['ltenddate']))])->groupEnd();
			$query->groupEnd();
		}
		
		if(isset($requestdata['checkin']) && isset($requestdata['checkout'])){
			if(isset($requestdata['btw_start_date']) && isset($requestdata['btw_end_date'])) $query->groupStart()->orWhere("'".$requestdata['btw_end_date']."' BETWEEN e.start_date AND e.end_date")->groupEnd();

			$query->groupStart();
				$query->where("'".date('Y-m-d', strtotime($requestdata['checkin']))."' BETWEEN b.check_in AND DATE_ADD(b.check_out, INTERVAL -1 DAY)");
				$query->orWhere("'".date('Y-m-d', strtotime($requestdata['checkout']))."' BETWEEN b.check_in AND DATE_ADD(b.check_out, INTERVAL -1 DAY)");
			$query->groupEnd();
		}
		
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$query->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page']) && $requestdata['page']=='adminreservations'){
				$column = ['b.id','paymentmethod_name', 'b.firstname', 'b.lastname','b.mobile','b.status'];
				$query->orderBy($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
						
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];
				
				$query->groupStart();
					if($page=='adminreservations'){
						$query->like('b.firstname', $searchvalue);
						$query->orLike('b.lastname', $searchvalue);
						$query->orLike('b.mobile', $searchvalue);
						$query->orLike('b.status', $searchvalue);
					}
					
					if($page=='reservations'){				
						$query->like('b.firstname', $searchvalue);
						$query->orLike('b.lastname', $searchvalue);
						$query->orLike('b.mobile', $searchvalue);
						$query->orLike('b.status', $searchvalue);
					}
				$query->groupEnd();
			}			
		}
		
		if(isset($extras['groupby'])) 	$query->groupBy($extras['groupby']);
		if(isset($extras['orderby'])) 	$query->orderBy($extras['orderby']);

		if($type=='count'){
			$result = $query->countAllResults(); 
		}else{
			$query = $query->get();
		
			if($type=='all'){
				$result = $query->getResultArray();
			}elseif($type=='row'){
				$result = $query->getRowArray();
			}

			$result = $this->getBookingDetails($type, $querydata, ['result' => $result, 'flag' => 1, 'bookingname' =>'barnstall']);
			$result = $this->getBookingDetails($type, $querydata, ['result' => $result, 'flag' => 2, 'bookingname' =>'rvbarnstall']);
			$result = $this->getBookingDetails($type, $querydata, ['result' => $result, 'flag' => 3, 'bookingname' =>'feed']);
			$result = $this->getBookingDetails($type, $querydata, ['result' => $result, 'flag' => 4, 'bookingname' =>'shaving']);
		}
		return $result;
    }

    public function getBookingDetails($type, $querydata, $extras)
    { 
    	$result 			= $extras['result'];
		$bookingname 		= $extras['bookingname'];
		$flag 				= $extras['flag'];

    	if($type=='all'){ 
    		if(count($result) > 0){ 
				if(in_array($bookingname, $querydata)){
					foreach ($result as $key => $booking) {
						$bookingdetails = $this->db->table('booking_details bd');
						if($flag==1 || $flag==2){
							$bookingdetails = $bookingdetails
							->join('barn b', 'b.id = bd.barn_id', 'left')
							->join('stall s','s.id  = bd.stall_id', 'left')
							->select('bd.*, b.name barnname, s.name stallname, s.lock_unlock lockunlock, s.dirty_clean dirtyclean');
						}elseif($flag==3 || $flag==4){
							$bookingdetails = $bookingdetails
							->join('products p', 'p.id = bd.product_id', 'left')
							->select('bd.*, p.name productname, p.quantity productquantity');
						}
						
						$bookingdetails = $bookingdetails
						->where(['bd.booking_id'=> $booking['id'], 'bd.flag' => $flag])
						->get()
						->getResultArray();
										
						$result[$key][$bookingname] = $bookingdetails;
					}
				}
			}
    	}else if($type=='row'){
    		if($result){
				if(in_array($bookingname, $querydata)){
					$bookingdetails = $this->db->table('booking_details bd');
					if($flag==1 || $flag==2){
						$bookingdetails = $bookingdetails
						->join('barn b', 'b.id = bd.barn_id', 'left')
						->join('stall s','s.id  = bd.stall_id', 'left')
						->select('bd.*, b.name barnname, s.name stallname, s.lock_unlock lockunlock, s.dirty_clean dirtyclean');
					}elseif($flag==3 || $flag==4){
						$bookingdetails = $bookingdetails
						->join('products p', 'p.id = bd.product_id', 'left')
						->select('bd.*, p.name productname, p.quantity productquantity');
					}
					
					$bookingdetails = $bookingdetails
					->where(['bd.booking_id'=> $result['id'], 'bd.flag' => $flag])
					->get()
					->getResultArray();
									
					$result[$bookingname] = $bookingdetails;
				}
			}
    	}
		
    	return $result;
    }

	public function action($data)
	{ 
		$this->db->transStart();
		$datetime = date('Y-m-d H:i:s');
		
		if(isset($data['firstname']) && $data['firstname']!='')            		$request['firstname']     		= $data['firstname'];
		if(isset($data['lastname']) && $data['lastname']!='')              		$request['lastname']      		= $data['lastname'];
		if(isset($data['mobile']) && $data['mobile']!='')      	           		$request['mobile'] 	      		= $data['mobile'];
		if(isset($data['checkin']) && $data['checkin']!='')                		$request['check_in'] 	  		= date('Y-m-d', strtotime($data['checkin']));
		if(isset($data['checkout']) && $data['checkout']!='')      	  	   		$request['check_out'] 	  		= date('Y-m-d', strtotime($data['checkout']));
		if(isset($data['eventid']) && $data['eventid']!='')      	      		$request['event_id'] 	  		= $data['eventid'];
		if(isset($data['paymentid']) && $data['paymentid']!='')      	   		$request['payment_id'] 			= $data['paymentid'];
		if(isset($data['paymentmethodid']) && $data['paymentmethodid']!='')     $request['paymentmethod_id'] 	= $data['paymentmethodid'];
		if(isset($data['userid']) && $data['userid']!='')      	           		$request['user_id'] 	  		= $data['userid'];
		if(isset($data['type']) && $data['type']!='')      	           	 		$request['type'] 	      		= $data['type'];
		if(isset($data['price']) && $data['price']!='')      	           	 	$request['price'] 	      		= $data['price'];
		if(isset($data['transactionfee']) && $data['transactionfee']!='')      	$request['transaction_fee'] 	= $data['transactionfee'];
		if(isset($data['amount']) && $data['amount']!='')      	           	 	$request['amount'] 	      		= $data['amount'];
 		$request['status'] 	      = '1';

		if(isset($request)){				
			$request['updated_at'] 	= $datetime;
			$request['updated_by'] 	= $data['userid'];						
			$request['created_at'] 	= $datetime;
			$request['created_by'] 	= $data['userid'];

			$this->db->table('booking')->insert($request);
			$insertid = $this->db->insertID();
		}

		$this->bookingdetailaction(json_decode($data['barnstall'], true), ['event_id' => $data['eventid'], 'booking_id' => $insertid, 'flag' => '1']);
        $this->bookingdetailaction(json_decode($data['rvbarnstall'], true), ['event_id' => $data['eventid'], 'booking_id' => $insertid, 'flag' => '2']);
        $this->bookingdetailaction(json_decode($data['feed'], true), ['event_id' => $data['eventid'], 'booking_id' => $insertid, 'flag' => '3']);
        $this->bookingdetailaction(json_decode($data['shaving'], true), ['event_id' => $data['eventid'], 'booking_id' => $insertid, 'flag' => '4']);

		if(isset($insertid) && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return $insertid;
		}
	}
	
	public function bookingdetailaction($results, $extras){

        foreach ($results as $result){
            $bookingdetails = array(
                'booking_id' 	=> isset($extras['booking_id']) ? $extras['booking_id'] : '',
                'barn_id'      	=> isset($result['barn_id']) ? $result['barn_id'] : '',
                'stall_id'      => isset($result['stall_id']) ? $result['stall_id'] : '',
                'product_id'    => isset($result['product_id']) ? $result['product_id'] : '',
                'price'      	=> isset($result['price']) ? $result['price'] : '',
                'quantity'      => isset($result['quantity']) ? $result['quantity'] : (isset($result['interval']) ? $result['interval'] : ''),
                'total'      	=> isset($result['total']) ? $result['total'] : '',
                'flag'      	=> isset($extras['flag']) ? $extras['flag'] : '',
                'status'      	=> 1
            );
			
            $this->db->table('booking_details')->insert($bookingdetails);

			if(isset($result['product_id']) && isset($result['quantity']) && isset($extras['flag']) && ($extras['flag']==3 || $extras['flag']==4)){
				$datass = $this->db->table('products')->where('id', $result['product_id'])->set('quantity', 'quantity-'.$result['quantity'], FALSE)->update();
				
				$products = $this->db->table('products')->where('id', $result['product_id'])->get()->getRowArray();
				$event = $this->db->table('event')->where('id', $extras['event_id'])->get()->getRowArray();
				if($products['quantity']=='0') send_message_template('2', ['productid' => $result['product_id'], 'userid' => $event['user_id']]);
			}
        }
    }
    
    public function delete($data)
	{
		$this->db->transStart();

		$stallid = $data['stallid'];
		$lockunlock  	= isset($data['lockunlock']) ? $data['lockunlock'] : '';
		$dirtyclean   	= isset($data['dirtyclean']) ? $data['dirtyclean'] : '';
		
		if($lockunlock=='1'){
			$stall = $this->db->table('stall')->update(['lock_unlock' => '1'], ['id' => $stallid]);
		}else if($dirtyclean=='1'){
			$stall = $this->db->table('stall')->update(['dirty_clean' => '1' ], ['id' => $stallid]);
		}
		
		if($stall && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return true;
		}
	}
}