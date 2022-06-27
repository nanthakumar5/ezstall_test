<?php

namespace App\Models;

use App\Models\BaseModel;

class Comments extends BaseModel
{	
	public function getComments($type, $querydata=[], $requestdata=[], $extras=[])
    { 
    	$select 			= [];
		
		if(in_array('comments', $querydata)){
			$data		= 	['c.*'];							
			$select[] 	= 	implode(',', $data);
		}

		if(in_array('users', $querydata)){
			$data		= 	['u.name username','u.type usertype'];							
			$select[] 	= 	implode(',', $data);
		}
	
		$query = $this->db->table('comments c');
		if(in_array('users', $querydata)) $query->join('users u', 'u.id=c.user_id', 'left');

		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('c.id', $requestdata['id']);
		if(isset($requestdata['eventid'])) 				$query->where('c.event_id', $requestdata['eventid']);
		if(isset($requestdata['commentid'])) 			$query->where('c.comment_id ', $requestdata['commentid']);

		if($type=='count'){
			$result = $query->countAllResults();
		}else{
			$query = $query->get();
			
			if($type=='all'){
				$result = $query->getResultArray();	
				if(in_array('replycomments', $querydata)){		
					if(count($result) > 0){
						foreach ($result as $key => $comments) {							
							$replycomment = $this->db->table('comments r')
											->join('users u','u.id  = r.user_id', 'left')
											->select('r.comment reply, u.name username')
											->where('r.comment_id', $comments['id'])
											->get()
											->getResultArray();
											
							$result[$key]['replycomments'] = $replycomment;
						}
					}
				}
			}elseif($type=='row'){
				$result = $query->getRowArray();
				
				if(in_array('replycomments', $querydata)){		
					if($result){
						$replycomment = $this->db->table('comments r')
										->join('users u','u.id  = r.user_id', 'left')
										->select('r.comment reply, u.name username')
										->where('r.comment_id', $result['id'])
										->get()
										->getResultArray();
										
						$result['replycomments'] = $replycomment;
					}
				}
			}
		}
		
		return $result;
    }
	
	public function action($data)
	{

		$this->db->transStart();
		
		$datetime		= date('Y-m-d H:i:s');
		$commentid 		= (isset($data['comment_id'])) ? $data['comment_id'] : 0;
		
		if(isset($data['eventid']) && $data['eventid']!='')      			$request['event_id'] 						= $data['eventid'];
		if(isset($data['userid']) && $data['userid']!='') 	 				$request['user_id'] 						= $data['userid'];
		if(isset($data['comment']) && $data['comment']!='')					$request['comment'] 						= $data['comment'];
		
		$request['communication']	= (isset($data['communication']) && $data['communication']!='') ? $data['communication'] : 0; 
		$request['cleanliness']		= (isset($data['cleanliness']) && $data['cleanliness']!='') ? $data['cleanliness'] : 0; 
		$request['friendliness']	= (isset($data['friendliness']) && $data['friendliness']!='') ? $data['friendliness'] : 0; 
		
		if(isset($request)){
			$request['status'] 			= 	'1';		
			$request['created_at'] 		= 	$datetime;
			$request['created_by'] 		= 	$data['userid'];				
			$request['updated_at'] 		= 	$datetime;
			$request['updated_by'] 		= 	$data['userid'];						
			$request['comment_id'] 		= 	$commentid;

			$comments = $this->db->table('comments')->insert($request);
			$commentsinsertid = $this->db->insertID();			
		}
		
		if(isset($commentsinsertid) && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return $commentsinsertid;
		}
	}
}