<?php
namespace App\Controllers\Site\Myaccount\AccountInfo;

use App\Controllers\BaseController;
use App\Models\Users;

class Index extends BaseController
{
	public function __construct()
	{
		$this->users = new Users();	
	}

	public function index()
    {
    	if ($this->request->getMethod()=='post')
		{ 
			$requestData = $this->request->getPost();
			$userid = $this->users->action($requestData); 
			if($userid){ 
				$this->session->setFlashdata('success', 'Your Account Updated Successfully'); 
			}else{ 
				$this->session->setFlashdata('danger', 'Try again Later.');
			}
		}

		$data['userdetail'] = getSiteUserDetails();
    	return view('site/myaccount/accountinfo/index',$data);
    }
}