<?php 

namespace App\Controllers\Site\Checkout;

use App\Controllers\BaseController;
use App\Models\Booking;
use App\Models\Stripe;
use App\Models\Cart;
use App\Models\Paymentmethod;

class Index extends BaseController
{
	public function __construct()
	{
		$this->booking 			= new Booking();	
		$this->stripe  			= new Stripe();
        $this->cart    			= new Cart(); 
        $this->paymentmethod    = new Paymentmethod(); 
	}
    
    public function index()
    {
        if(!getCart()){
            return redirect()->to(base_url().'/'); 
        }
        
        $userdetail  	= getSiteUserDetails();
		$cartdetail  	= getCart();		
		$settings		= getSettings();
		$paymentmethod	= $this->paymentmethod->getPaymentmethod('all', ['paymentmethod']);
		
    	if ($this->request->getMethod()=='post')
    	{    
            $requestData 				= $this->request->getPost();
            $userid             		= $userdetail['id'];
			$paymentmethodid			= $requestData['paymentmethodid'];
			
			if($paymentmethodid!='1') $payment = $this->stripe->action(['id' => $requestData['stripepayid']]);
			else $payment = 1;
			
			if($payment){
				$requestData['paymentid'] 	= ($paymentmethodid!='1') ? $payment : 0;	
				$requestData['amount'] 		= $requestData['price'];	
		
				$booking = $this->booking->action($requestData);
				if($booking){
					$this->cart->delete(['user_id' => $userid, 'type' => $requestData['type']]);
					return redirect()->to(base_url().'/paymentsuccess'); 
				}else{
					$this->session->setFlashdata('danger', 'Try Later.');
					return redirect()->to(base_url().'/checkout'); 
				}
			}else{
				$this->session->setFlashdata('danger', 'Your payment is not processed successfully.');
				return redirect()->to(base_url().'/checkout'); 
			}
        }
       
        return view('site/checkout/index', [
			'currencysymbol' 	=> $this->config->currencysymbol, 
			'settings' 			=> $settings, 
			'userdetail' 		=> $userdetail, 
			'cartdetail' 		=> $cartdetail,
			'paymentmethod' 	=> $paymentmethod,
			'stripe'			=> view('site/common/stripe/stripe1')
		]);
    }

    public function success(){
        return view('site/checkout/success');
    }
}