<?php

namespace App\Controllers\Admin\Comments;

use App\Controllers\BaseController;

use App\Models\Comments;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->comments  = new Comments();
    }
	
	public function index($id='')
	{	
  		$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  5; 
		$offset = $page * $perpage;

		if($id!=''){
			$commentcount 	= $this->comments->getComments('count', ['comments','users','event','replycomments'], ['commentid' => '0','eventid' => $id,'status' => ['1']]);
			$comments 	= $this->comments->getComments('all', ['comments','users','event','replycomments'],['commentid' => '0', 'eventid' => $id,'status'=> ['1'],'start' => $offset, 'length' => $perpage]);
		}
		else{
			$commentcount 	= $this->comments->getComments('count', ['comments','users','event','replycomments'], ['commentid' => '0','status' => [ '1']]);
			$comments 	= $this->comments->getComments('all', ['comments','users','event','replycomments'],['commentid' => '0', 'status'=> ['1'],'start' => $offset, 'length' => $perpage]);
		}

		if ($this->request->getMethod()=='post')
        {	
        	$requestData 				= $this->request->getPost();
        	$requestData['userid'] 		= getSiteUserID();

            $result = $this->comments->delete($requestData);
			
			if($result){
				$this->session->setFlashdata('success', 'Comment deleted successfully.');
				return redirect()->to(getAdminUrl().'/comments'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later');
				return redirect()->to(getAdminUrl().'/comments'); 
			}
        }

	    $data['pager'] 	= $pager->makeLinks($page, $perpage, $commentcount);
	    $data['comments'] 	= $comments;
		return view('admin/comments/index',$data);
	}

	public function action($id='')
	{
		if($id!=''){
			$result = $this->comments->getComments('row', ['comments','users','event','replycomments'], ['id' => $id, 'status' => ['1']]);
			if($result){
				$data['result'] = $result;
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(getAdminUrl().'/comments'); 
			}
		}
		
		if ($this->request->getMethod()=='post')
        { 
        	$requestData = $this->request->getPost();
            $result = $this->comments->action($requestData);
			
			if($result){
				$this->session->setFlashdata('success', 'Comments saved successfully.');
				return redirect()->to(getAdminUrl().'/comments'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(getAdminUrl().'/comments'); 
			}
        }
		
		return view('admin/comments/action', $data);
	}

	public function view($id)
	{
		$result = $this->comments->getComments('row', ['comments','users','event','replycomments'], ['id' => $id, 'status' => ['1']]);
		if($result){
			$data['result'] = $result;
		}else{
			$this->session->setFlashdata('danger', 'No Record Found.');
			return redirect()->to(getAdminUrl().'/comments'); 
		}
		return view('admin/comments/view', $data);
	}
	
}
