<?php

namespace App\Models;

use App\Models\BaseModel;

class Products extends BaseModel
{	
	public function getProduct($type, $querydata=[], $requestdata=[], $extras=[])
    { 
    	$select 			= [];
		
		if(in_array('product', $querydata)){
			$data		= 	['p.*'];							
			$select[] 	= 	implode(',', $data);
		}

		$query = $this->db->table('products p');  
				
		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('p.id', $requestdata['id']);
		if(isset($requestdata['type'])) 				$query->where('p.type', $requestdata['type']);
		
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$query->limit($requestdata['length'], $requestdata['start']);
		}

		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page']) && $requestdata['page']=='products'){
				$column = ['p.name', 'p.quantity'];
				$query->orderBy($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
						
			if(isset($requestdata['page'])){ 
				$page = $requestdata['page'];
				$query->groupStart();
					if($page=='products'){				
						$query->like('p.name', $searchvalue);
					}
				$query->groupEnd();
			}			
		}
		
		if(isset($extras['groupby'])) 	$query->groupBy($extras['groupby']);
		else $query->groupBy('p.id');
		
		if(isset($extras['orderby'])) 	$query->orderBy($extras['orderby'], $extras['sort']);
		
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