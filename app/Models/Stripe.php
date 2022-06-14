<?php
namespace App\Models;
use App\Models\BaseModel;

class Stripe extends BaseModel
{	
	public function action($data)
	{ 
		$this->db->transStart();
		
		$id = $data['id'];
		
		$payment = $this->db->table('payment')->where('id', $id)->get()->getRowArray();


		if($payment['type']=='1'){
			$data = $this->retrievePaymentIntents($payment['stripe_paymentintent_id']);

			if($data->status=='succeeded'){
				$this->db->table('payment')->update(['status' => '1'], ['id' => $id]);
				$insertid = $id;
			}
		}elseif($payment['type']=='2'){
			$data = $this->retrieveSubscription($payment['stripe_subscription_id']);
			if($data->status=='active'){
				$this->db->table('payment')->update(['status' => '1'], ['id' => $id]);
				$this->db->table('users')->where(['id' => $payment['user_id']])->update(['subscription_id' => $id]);
				$insertid = $id;
			}
		}
			
		if(!isset($insertid) || $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return $id;
		}
	}

	function stripepayment($requestData)
	{
		$userid 		= $requestData['userid'];
		$name 			= $requestData['name'];
		$email 			= $requestData['email'];
		$cardno 		= $requestData['card_number'];
		$cardexpmonth 	= $requestData['card_exp_month'];
		$cardexpyear 	= $requestData['card_exp_year'];
		$cardcvc 		= $requestData['card_cvc'];
		$price 			= $requestData['price'] * 100;
        $currency 		= "inr";
		
		$paymentmethods = $this->createPaymentMethods($cardno, $cardexpmonth, $cardexpyear, $cardcvc);
		if($paymentmethods)
		{
			$customer = $this->createCustomer($name, $email, $paymentmethods->id);
			if($customer)
			{
				$paymentintents = $this->createPaymentIntents($customer->id, $price, $currency);				
				if($paymentintents)
				{
					$confirm = $this->confirmPaymentIntents($paymentintents->id, $paymentmethods);
					
					$paymentData = array(
						'user_id' 					=> $userid,
						'name' 						=> $name,
						'email' 					=> $email,
						'amount' 					=> $price/100,
						'currency' 					=> $currency,
						'stripe_customer_id' 		=> $confirm->customer,
						'stripe_paymentintent_id' 	=> $confirm->id,
						'type' 						=> '1',
						'status' 					=> '0',
						'created' 					=> date("Y-m-d H:i:s")
					);
					
					$this->db->table('payment')->insert($paymentData);
					$paymentinsertid = $this->db->insertID();
					
					$paymentstatus = $confirm->status;
					if($paymentstatus=='requires_action' && $confirm->next_action->type == 'redirect_to_url' && $confirm->next_action->redirect_to_url->url){
						return ['status' => '1', 'url' => $confirm->next_action->redirect_to_url->url, 'id' => $paymentinsertid];
					}else if ($confirm->status == 'succeeded') {
						return ['status' => '1', 'url' => '', 'id' => $paymentinsertid];
					}else{
						return ['status' => '0', 'url' => '', 'id' => $paymentinsertid];
					}
				}
			}else{
				return false;
			}
		}
	}
	
	
	function striperecurringpayment($requestData)
	{
		$userid 		= $requestData['userid'];
		$name 			= $requestData['name'];
		$email 			= $requestData['email'];
		$cardno 		= $requestData['card_number'];
		$cardexpmonth 	= $requestData['card_exp_month'];
		$cardexpyear 	= $requestData['card_exp_year'];
		$cardcvc 		= $requestData['card_cvc'];
		$planid 		= $requestData['plan_id'];
		$planname 		= $requestData['plan_name'];
		$planprice 		= $requestData['plan_price'];
		$planinterval 	= $requestData['plan_interval'];
		
		$paymentmethods = $this->createPaymentMethods($cardno, $cardexpmonth, $cardexpyear, $cardcvc);			
		if($paymentmethods)
		{	
			$customer = $this->createCustomer($name, $email, $paymentmethods->id);
			if($customer)
			{
				$product = $this->createProduct($planname);
				if($product)
				{
					$price = $this->createPrice($product->id, $planprice, $planinterval);
					if ($price)
					{
						$subscription = $this->createSubscription($customer->id, $price->id);
						if($subscription){
							$paymentintentsid = $subscription->latest_invoice->payment_intent->id;
							$confirm = $this->confirmPaymentIntents($paymentintentsid, $paymentmethods);
							
							$paymentData = array(
								'user_id' 					=> $userid,
								'name' 						=> $name,
								'email' 					=> $email,
								'amount' 					=> $subscription->plan->amount/100,
								'currency' 					=> $subscription->plan->currency,
								'stripe_customer_id' 		=> $subscription->customer,
								'stripe_paymentintent_id' 	=> $paymentintentsid,
								'stripe_subscription_id' 	=> $subscription->id,
								'stripe_plan_id' 			=> $subscription->plan->id,
								'plan_id'					=> $planid,
								'plan_interval' 			=> $subscription->plan->interval,
								'plan_period_start' 		=> date("Y-m-d H:i:s", $subscription->current_period_start),
								'plan_period_end' 			=> date("Y-m-d H:i:s", $subscription->current_period_end),
								'type' 						=> '2',
								'status' 					=> '0',
								'created' 					=> date("Y-m-d H:i:s")
							);

							$this->db->table('payment')->insert($paymentData);
							$paymentinsertid = $this->db->insertID();
					   
							$paymentstatus = $confirm->status;
							if($paymentstatus=='requires_action' && $confirm->next_action->type == 'redirect_to_url' && $confirm->next_action->redirect_to_url->url){
								return ['status' => '1', 'url' => $confirm->next_action->redirect_to_url->url, 'id' => $paymentinsertid];
							}else if ($confirm->status == 'succeeded') {
								return ['status' => '1', 'url' => '', 'id' => $paymentinsertid];
							}else{
								return ['status' => '0', 'url' => '', 'id' => $paymentinsertid];
							}
						}
					}
				}
			}
			else{
				return false;
			}
		}
		
	}

	function striperefunds($data){
		$this->db->table('booking')->update(['status' => '2'], ['id' => $data['id']]);
		return true;
	}
	
	function createPaymentMethods($cardno, $cardexpmonth, $cardexpyear, $cardcvc)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->paymentMethods->create([
				'type' => 'card',
				'card' => [
					'number' 	=> $cardno,
					'exp_month' => $cardexpmonth,
					'exp_year' 	=> $cardexpyear,
					'cvc' 		=> $cardcvc
				]
			]);

            return $data;
        }catch(Exception $e){
            print_r($e->getMessage());die;
			return false;
        }
    }
	
    function createCustomer($name, $email, $paymentmethodid)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->customers->create([
				'name' 				=> $name,
				'email' 			=> $email,
				'payment_method'	=> $paymentmethodid
			]);
			
			return $data;
		}catch(Exception $e){
            print_r($e->getMessage());die;
			return false;
        }
    }
	
	function createPaymentIntents($customerid, $price, $currency)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->paymentIntents->create([
				"customer" => $customerid,
				'amount' => $price,
				'currency' => $currency,
				'payment_method_types' => ['card'],
			]);

            return $data;
        }catch(Exception $e){
            print_r($e->getMessage());die;
			return false;
        }
    }
	
    function retrievePaymentIntents($paymentintentsid)
    {
        try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->paymentIntents->retrieve(
				$paymentintentsid,
				[]
			);
			
			return $data;
        }catch(Exception $e){
            print_r($e->getMessage());
            die;
        }
    } 
	
	function confirmPaymentIntents($paymentintentsID, $paymentmethod)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->paymentIntents->confirm(
				$paymentintentsID,
				[
					'payment_method' => $paymentmethod,
					'return_url' => base_url().'/stripe3d'
				]
			);

            return $data;
        }catch(Exception $e){
            print_r($e->getMessage());die;
			return false;
        }
    }
	
    function createProduct($planname)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->products->create([
				'name' => $planname
			]);
			
			return $data;
		}catch(Exception $e){
            print_r($e->getMessage());die;
			return false;
        }
    }

    function createPrice($productid, $planprice, $planinterval)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$amount = ($planprice * 100);
			$currency = "inr";
			
			$data = $stripe->prices->create([
				'unit_amount' => $amount,
				'currency' => $currency,
				'recurring' => ['interval' => $planinterval],
				'product' => $productid
			]);
			
			return $data;
		}catch(Exception $e){
            print_r($e->getMessage());die;
			return false;
        }
    }

    function createSubscription($customerid, $priceid)
    {
        try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
            $data = $stripe->subscriptions->create([
                "customer" => $customerid,
				"items" => [
					["price" => $priceid]
				],
				'payment_behavior' => 'default_incomplete', 
                'expand' => ['latest_invoice.payment_intent']
            ]);
			
			return $data;
        }catch(Exception $e){
            print_r($e->getMessage());
            die;
        }
    } 
	
    function retrieveSubscription($subscriptionid)
    {
        try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->subscriptions->retrieve(
				$subscriptionid,
				[]
			);
			
			return $data;
        }catch(Exception $e){
            print_r($e->getMessage());
            die;
        }
    } 
    
    function createRefunds($paymentintentid, $amount)
    {
        try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
            $data = $stripe->refunds->create([
                'payment_intent' => $paymentintentid,
                'amount' => $amount
            ]);

			return $data;
        }catch(Exception $e){
            print_r($e->getMessage());
            die;
        }
    }  
}