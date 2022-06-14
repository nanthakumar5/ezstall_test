<?php 
namespace App\Controllers\Site\Myaccount\Facility;

use App\Controllers\BaseController;
use App\Models\Users;
use App\Models\Event;
use App\Models\Booking;
use App\Models\Stripe;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Index extends BaseController
{
	public function __construct()
	{	
		$this->users 	= new Users();
		$this->event 	= new Event();
		$this->booking 	= new Booking();
		$this->stripe 	= new Stripe();
	}
    
    public function index()
    { 			
		$userdetail 	= getSiteUserDetails();
		$userid 		= $userdetail['id'];
		$usertype 		= $userdetail['type'];

		if($usertype == '4') $userid = $userdetail['parent_id'];
		
		if ($this->request->getMethod()=='post')
        {
			$result = $this->event->delete($this->request->getPost());
			if($result){
				$this->session->setFlashdata('success', 'Facility deleted successfully.');
				return redirect()->to(base_url().'/myaccount/facility'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later');
				return redirect()->to(base_url().'/myaccount/facility'); 
			}
        }
		
    	$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  10; 
		$offset = $page * $perpage;
		
		$eventcount = $this->event->getEvent('count', ['event'], ['status' => ['1'], 'userid' => $userid, 'type' => '2']);
		$event = $this->event->getEvent('all', ['event'], ['status' => ['1'], 'userid' => $userid, 'type' => '2', 'start' => $offset, 'length' => $perpage], ['orderby' => 'e.id desc']);

        $data['list'] 		= $event;
        $data['pager'] 		= $pager->makeLinks($page, $perpage, $eventcount);
		$data['userid'] 	= $userid;
		$data['usertype'] 	= $usertype;
		
		return view('site/myaccount/facility/index', $data);
    }

    public function action($id='')
	{   
		$settings 						= getSettings();
		$userdetails 					= getSiteUserDetails();
		$userid         				= $userdetails['id'];
		$usertype       				= $userdetails['type'];
		$checksubscription 				= checkSubscription();
		$checksubscriptiontype 			= $checksubscription['type'];
		$checksubscriptionfacility 		= $checksubscription['facility'];
		$checksubscriptionstallmanager 	= $checksubscription['stallmanager'];

		$eventcount = $this->event->getEvent('count', ['event'], ['status' => ['1'], 'userid' => $userid, 'type' => '2']);
		
		if($checksubscriptiontype=='2' && $checksubscriptionfacility!='1'){
			$this->session->setFlashdata('danger', 'Please subscribe the account.');
			return redirect()->to(base_url().'/myaccount/subscription'); 
		}
		elseif($checksubscriptiontype=='4' && $checksubscriptionstallmanager!='4'){ 
			$this->session->setFlashdata('danger', 'Please subscribe the account.');
			return redirect()->to(base_url().'/myaccount/subscription'); 
		}
		
		if($id!=''){
			$result = $this->event->getEvent('row', ['event', 'barn', 'stall'],['id' => $id, 'status' => ['1'], 'userid' => $userid, 'type' => '2']);
			if($result){				
				$data['occupied'] 	= getOccupied($id);
				$data['reserved'] 	= getReserved($id);
				$data['result'] 	= $result;
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(base_url().'/myaccount/facility'); 
			}
		}
		
		if ($this->request->getMethod()=='post')
		{
			$requestData 			= $this->request->getPost();
			if(isset($requestData['stripepayid'])) $payment = $this->stripe->action(['id' => $requestData['stripepayid']]);
			
			if(!isset($requestData['stripepayid']) || (isset($requestData['stripepayid']) && isset($payment))){
				$requestData['type'] 	= '2';
				$requestData['name'] 	= $requestData['facility_name'];
				
				$result = $this->event->action($requestData);			
				if($result){
					$this->session->setFlashdata('success', 'Facility submitted successfully.');
					return redirect()->to(base_url().'/myaccount/facility'); 
				}else{
					$this->session->setFlashdata('danger', 'Try Later.');
					return redirect()->to(base_url().'/myaccount/facility'); 
				}
			}else{
				$this->session->setFlashdata('danger', 'Your payment is not processed successfully.');
				return redirect()->to(base_url().'/myaccount/facility'); 
			}
        } 
		
		$data['userid'] 		= $userid;
		$data['statuslist'] 	= $this->config->status1;
		$data['currencysymbol'] = $this->config->currencysymbol;
		$data['stripe'] 		= view('site/common/stripe/stripe1');
		$data['settings'] 		= $settings;
		
		return view('site/myaccount/facility/action', $data);
	}
	
	public function view($id)
    {  
		$data['detail']  	= $this->event->getEvent('row', ['event', 'barn', 'stall','bookedstall'],['id' => $id, 'type' => '2']);
		$data['occupied'] 	= getOccupied($id); 
		$data['reserved'] 	= getReserved($id);
		
		return view('site/myaccount/facility/view',$data);
    }
	
    public function export($id)
    {	
    	$data 		= $this->event->getEvent('row', ['event', 'barn', 'stall', 'bookedstall'],['id' => $id, 'type' => '2']); 

		$spreadsheet = new Spreadsheet();
		$sheet 		 = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'Event Name');
		$sheet->setCellValue('B1', 'Description');

        $row = 2;

			$sheet->setCellValue('A' . $row, $data['name']);
			$sheet->setCellValue('B' . $row, strip_tags($data['description']));
			
			foreach ($data['barn'] as $key => $barn) { 
				$sheet->setCellValue('A'.$row, $barn['name']);
				$row++;
				
				foreach($barn['stall'] as $key=> $stall){  
					$stallname  = $stall['name'];
					
					$bookedstall = '';
					foreach($stall['bookedstall'] as $keys=> $booking){
						$bookedstall	.=   "\nName : ".$booking['name']."\nDate  : ".formatdate($booking['check_in'])." to ".formatdate($booking['check_out']);
					}
					
					$sheet->setCellValue('A'.$row, $stallname.$bookedstall);
					$sheet->getCell('A'.$row)->getStyle()->getAlignment()->setWrapText(true);
					$sheet->getCell('A'.$row)->getStyle()->getFont()->setBold(true);
					$row++;
				} 
			}
			
			$row++;


		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$data['name'].'.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		die;
    }
	
	public function importbarnstall()
    {	
		$phpspreadsheet = new Spreadsheet();

      	$reader 		= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
      	$spreadsheet 	= $reader->load($_FILES['file']['tmp_name']);
		$sheetdata 		= $spreadsheet->getActiveSheet()->toArray();
		$array 			= [];
		
		foreach($sheetdata as $key1 => $data1){
			if($key1=='0') continue;
			
			foreach($data1 as $key2 => $data2){
				if($key1=='1' && ($key2%2)=='0'){
					$array[$key2]['name'] = $data2;
				}
				
				if($key1 > '1'  && ($key2%2)=='0'){
					$array[$key2]['stall'][] = ['name' => $data2, 'price' => (isset($data1[$key2+1]) ? $data1[$key2+1] : '')];
				}
			}
		}
		
		$array = array_values($array);
		echo json_encode($array);
    }
}
