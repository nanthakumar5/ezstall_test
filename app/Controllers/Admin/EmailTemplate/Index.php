<?php

namespace App\Controllers\Admin\EmailTemplate;

use App\Controllers\BaseController;

use App\Models\Emailtemplate;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->emailtemplate  = new Emailtemplate();
    }
	
	public function index() 
	{
		$pager 		= service('pager'); 
		$page 		= (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage 	=  5; 
		$offset 	= $page * $perpage;

		$templatecount 		= $this->emailtemplate->getEmailTemplate('count', ['emailtemplate']);
		$data['templates'] 	= $this->emailtemplate->getEmailTemplate('all', ['emailtemplate'], ['start' => $offset, 'length' => $perpage]);
	   	$data['pager'] 		= $pager->makeLinks($page, $perpage, $templatecount);

		return view('admin/emailtemplate/index',$data);
	}
	
	public function action($id='')
	{
		$data = [];

		if($id!=''){
			$result = $this->emailtemplate->getEmailTemplate('row', ['emailtemplate'], ['id' => $id]);
			if($result){
				$data['result'] = $result;
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(getAdminUrl().'/emailtemplate'); 
			}
		}
		
		if ($this->request->getMethod()=='post')
        {
			$requestdata 	= $this->request->getPost();
            $result 		= $this->emailtemplate->action($requestdata);
			
			if($result){
				$this->session->setFlashdata('success', 'Template Updated successfully.');
				return redirect()->to(getAdminUrl().'/emailtemplate'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(getAdminUrl().'/emailtemplate'); 
			}
        }
		
		return view('admin/emailtemplate/action', $data);
	}	
}
