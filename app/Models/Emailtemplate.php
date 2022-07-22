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

		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$query->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page']) && $requestdata['page']=='emailtemplates'){
				$column = ['et.name','et.subject', 'et.message'];
				$query->orderBy($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
						
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];
				
				$query->groupStart();
					if($page=='emailtemplates'){				
						$query->like('et.name', $searchvalue);
						$query->orLike('et.subject', $searchvalue);
						$query->orLike('et.message', $searchvalue);
					}
				$query->groupEnd();
			}			
		}
		
		if(isset($extras['groupby'])) 	$query->groupBy($extras['groupby']);
		else $query->groupBy('et.id');
		
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