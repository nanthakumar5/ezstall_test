<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<?php
	$barnstall				= $cartdetail['barnstall'];
	$transactionfee 		= (($settings['transactionfee'] / 100) * $cartdetail['price']);
	$stripemode 			= $settings['stripemode'];
	$stripepublickey 		= $settings['stripepublickey'];
	$firstname 				= $stripemode=='2' ? 'First Name Test' : '';
	$lastname 				= $stripemode=='2' ? 'Last Name Test' : '';
	$mobile 				= $stripemode=='2' ? '987654321' : '';
	$name 					= $stripemode=='2' ? 'test' : '';
	$cardno 				= $stripemode=='2' ? '4242424242424242' : '';
	$cvc 					= $stripemode=='2' ? '123' : '';
	$expirymonth 			= $stripemode=='2' ? '12' : '';
	$expiryyear 			= $stripemode=='2' ? '2027' : '';

?>
<section class="maxWidth">
  <div class="pageInfo">
    <span class="marFive">
      <a href="<?php echo base_url(); ?>">Home /</a>
      <a href="javascript:void(0);"> Checkout</a>
    </span>
  </div>

  <div class="marFive dFlexComBetween eventTP">
    <div class="pageInfo m-0 bg-transparent">
      <span class="eventHead">
        <a href="<?php echo base_url().'/events'; ?>" class="mb-4 d-block"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
        </svg> Back To Details</a>
        <h1 class="eventPageTitle">Checkout</h1>
      </span>
    </div>
  </div>
</section>

<section class="container-lg">
  <div class="row">
    <div class="col-lg-9">
      <form action="" method="post" class="checkoutform">
        <div class="col-lg-12">
          <div class="checkout-renter border rounded pt-4 ps-4 pe-4 mb-5">
            <h2 class="checkout-fw-6 mb-2">Renter Information</h2>
            <p>Changes to this information will be reflected on all of your existing reservations.</p>
            <div class="row">
              <div class="col-lg-6 mb-4">
                <input placeholder="First Name" name="firstname" autocomplete='off' value="<?php echo $firstname; ?>">
              </div>
              <div class="col-lg-6 mb-4">
                <input type="text" placeholder="Last Name" name="lastname" autocomplete='off' value="<?php echo $lastname; ?>">
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6  mb-4">
                <input placeholder="Mobile Number" name="mobile" autocomplete='off'  value="<?php echo $mobile; ?>">
              </div>
              <div class="col-lg-6 mb-4">
                <span class="info-box d-flex justify-content-between"><img class="dash-info-i" src="<?php echo base_url()?>/assets/site/img/chekout-info.png"><p>You may receive a text message with your stall assignment before your arrival.</p></span>
              </div>
            </div>
          </div> 

           <div class="checkout-payment border rounded pt-4 ps-4 pe-4 mb-5">
            <h2 class="checkout-fw-6 mb-4">Payment Details</h2>
            <div class="row ">
              <div class="col-lg-6 mb-4">
                <div>
					<?php foreach ($paymentmethod as $key => $method){ ?>
						<?php if(in_array($userdetail['type'], explode(',', $method['type']))){ ?>
							<div class="px-3">
								<input type="radio" id="paymentmethod<?php echo $key; ?>" data-error="firstparent" name="paymentmethodid" value="<?php echo $method['id'];?>" style="display: inline;width: auto; margin-right: 10px;"><label for="paymentmethod<?php echo $key; ?>"><?php echo $method['name']; ?></label>
							</div>
						<?php } ?>
					<?php } ?>
                </div>
            </div>

          </div>


          <div class='error hide'><div class='alert' style="color: red;"></div></div> 
        </div>
        <input type="hidden" name="userid" value="<?php echo $userdetail['id']; ?>">
        <input type="hidden" name="email" value="<?php echo $userdetail['email']; ?>" >
        <input type="hidden" name="checkin" value="<?php echo formatdate($cartdetail['check_in']); ?>" >
        <input type="hidden" name="checkout" value="<?php echo formatdate($cartdetail['check_out']); ?>" >
        <input type="hidden" name="price" value="<?php echo $cartdetail['price']+$transactionfee; ?>" >
        <input type="hidden" name="eventid" value="<?php echo $cartdetail['event_id']; ?>" >
        <input type="hidden" name="type" value="<?php echo $cartdetail['type']; ?>" >
        <input type="hidden" name="barnstall" value='<?php echo json_encode($barnstall); ?>'>
        <input type="hidden" name="page" value="checkout" >

        <div class="checkout-special border rounded pt-4 ps-4 pe-4 mb-5">
          <h2 class="checkout-fw-6">Special Requests</h2>
          <p>Optional</p>
          <p>Enter any requests, such as stall location or other renters you want to be placed near.
            <b>Please note: special requests are not guaranteed</b></p>
            <textarea placeholder="Message"></textarea>
          </div> 

          <div class="checkout-reservation border rounded pt-4 ps-4 pe-4 mb-5">
            <h2 class="checkout-fw-6">Reservation Summary</h2>
            <div class="row">
              <div class="col-lg-6 mb-4">
                <b>Event</b>
                <p><?php echo $cartdetail['event_name'];?></p>
                <b>Location</b>
                <p><?php echo $cartdetail['event_location'];?><br>
                </div>
                <div class="col-lg-6 mb-4">
                  <b>Venue</b>
                  <p><?php echo $cartdetail['event_description'];?></p>
                </div>

              </div>
              <div class="row">
                <h2 class="checkout-fw-6 stallsum-head">Stall Summary</h2>
                <div class="col-lg-6 mb-4">
                  <b>Check In</b>
                  <p class="mb-4"><?php echo $cartdetail['check_in'] ?></p>
                  <b>Check Out</b>
                  <p><?php echo $cartdetail['check_out'] ?></p>
                </div>

                <div class="col-lg-6 mb-4">
                  <b>Number Of Stalls</b>
                  <p><?php echo count($barnstall); ?> Stalls 
                   <?php 
                   foreach ($barnstall as $data) { 
                     echo '<p>'.$data['barn_name'].'-'.$data['stall_name'].'</p>'; 
                   }
                   ?>
                 </p>
               </div>
             </div>
           </div>
           <div class="checkout-complete-btn">
            <span>
              <input class="form-check-input me-1" type="checkbox" name="tc" data-error="firstparent">I have read and accepted the <span class="redcolor">Terms and Conditions.</span></span>
              <button class="payment-btn checkoutpayment" type="button">Complete Payment</button>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-3">
        <div class="border rounded pt-4 ps-3 pe-3 mb-5">
          <div class="row mb-2">
            <div class="col-lg-8 ">
              <?php echo count($barnstall); ?> Stalls x <?php echo $cartdetail['interval']; ?> Nights
            </div>
            <div class="col-lg-4">
              <?php echo $currencysymbol.$cartdetail['price']; ?>
            </div>
          </div> 
          <div class="row mb-2">
            <div class="col-lg-8 ">
              Transaction Fees
            </div>
            <div class="col-lg-4">
              <?php echo $currencysymbol.$transactionfee; ?>
            </div>
          </div> 
          <div class="row mb-2 border-top mt-3 mb-3 pt-3">
            <div class="col-lg-8 fw-bold ">
              Total Due
            </div>
            <div class="col-lg-4 fw-bold totaldue">
              <?php echo $currencysymbol.($cartdetail['price']+$transactionfee); ?>
            </div>  
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php $this->endSection(); ?>
  
<?php $this->section('js') ?>
<?php echo $stripe; ?>
	<script>
		$(function(){
			validation( 
				'.checkoutform',
				{
					firstname      : {
						required  :   true
					},
					lastname      : {
						required  :   true
					},
					mobile      : {
						required  :   true
					},
					paymentmethodid : {
						required	:	true 
					},
					tc   : {
						required  :   true
					}
				},
				{ 
					firstname      : {
						required    : "Please Enter Your Firstname."
					},
					lastname      : {
						required    : "Please Enter Your Lastname."
					},
					mobile      : {
						required    : "Please Enter Mobile Number."
					},
					paymentmethodid : {
						required    : "Please select one."
					},
					tc   : {
						required    : "Please check the checkbox."
					},
				}
			);
		});

		$('.checkoutpayment').click(function(){
			if($('.checkoutform').valid()){
				var paymentmethod = $('input[type=radio]:checked').val();
				if(paymentmethod=='1'){
					$('.checkoutform').submit();
				}else{
					$('#stripeFormModal').modal('show');
				}
			}
		});
		
		
		$('#stripeFormModal').on('shown.bs.modal', function () { 
			var result = [];
			var formdata = $('.checkoutform').serializeArray();
			console.log(formdata);
			$.each(formdata, function(i, field){
				if(field.name=='barnstall') result.push('<textarea style="display:none;" name="'+field.name+'">'+field.value+'</textarea>')
				else result.push('<input type="hidden" name="'+field.name+'" value="'+field.value+'">')
			});
			
			$('.stripeextra').remove();
			var data = 	'<div class="stripeextra">'+result.join("")+'</div>';
			$('.stripetotal').text('(Total - '+$('.totaldue').text()+')');

			$('.stripepaybutton').append(data);
		})
	</script>
<?php $this->endSection() ?>
