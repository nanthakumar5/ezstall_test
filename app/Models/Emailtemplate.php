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
		if(isset($requestdata['type'])) 				$query->where('et.type', $requestdata['type']);
		
		if($type=='count'){
			$result = $query->countAllResults();
		}else{
			$query = $query->get();
			if($type=='all') 		$result = $query->getResultArray();
			elseif($type=='row') 	$result = $query->getRowArray();
		}
	
		return $result;
    }
}