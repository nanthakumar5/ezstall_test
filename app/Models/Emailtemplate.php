<?php

namespace App\Models;

use App\Models\BaseModel;

class Emailtemplate extends BaseModel
{	
	public function getEmailTemplate($type, $querydata=[], $requestdata=[], $extras=[])
    { 
    	$select 			= [];
		
		if(in_array('emailtemplate', $querydata)){
			$data		= 	['et.*'];							
			$select[] 	= 	implode(',', $data);
		}

		$query = $this->db->table('email_template et');  
				
		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('et.id', $requestdata['id']);
		
		if($type=='count'){
			$result = $query->countAllResults();
		}else{
			$query = $query->get();
			if($type=='all') 		$result = $query->getResultArray();
			elseif($type=='row') 	$result = $query->getRowArray();
		}
	
		return $result;
    }

	public function action($data)
	{
		$this->db->transStart();
		
		$actionid 			= (isset($data['actionid'])) ? $data['actionid'] : '';
		
		if(isset($data['name']) && $data['name']!='')      					$request['name'] 						= $data['name'];
		if(isset($data['subject']) && $data['subject']!='') 	 			$request['subject'] 					= $data['subject'];
		if(isset($data['message']) && $data['message']!='') 	 			$request['message'] 					= $data['message'];

		if(isset($request)){

			if($actionid==''){
				$templates = $this->db->table('email_template')->insert($request);
				$templateinsertid = $this->db->insertID();
			}else{
				$templates = $this->db->table('email_template')->update($request, ['id' => $actionid]);
				$templateinsertid = $actionid;
			}
		}
		
		if(isset($templateinsertid) && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return $templateinsertid;
		}
	}
}