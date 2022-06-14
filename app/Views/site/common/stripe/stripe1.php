<?php
	$settings 			= getSettings();
	$userdetails 		= getSiteUserDetails();	
	
	$stripemode 		= $settings['stripemode'];
	$stripepublickey 	= $settings['stripepublickey'];
	$name 				= $stripemode=='2' ? 'test' : '';
	$cardno 			= $stripemode=='2' ? '4242424242424242' : '';
	$cvc 				= $stripemode=='2' ? '123' : '';
	$expirymonth 		= $stripemode=='2' ? '12' : '';
	$expiryyear 		= $stripemode=='2' ? '2027' : '';
?>
<style>
* { margin : 0; }
</style>
<div class="modal fade" id="stripeFormModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="stripeFormModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Stripe Payment <span class="stripetotal"></span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
		    <div class="modal-body">
		    	<form role="form" action="" method="post" class="stripeform">
					<div class="mb-3">
						<label class='control-label'>Name on Card:</label> 
						<input autocomplete='off' class='form-control' size='4' placeholder='Name on Card' type='text' name='name' value="<?php echo $name; ?>">
					</div>
					<div class="mb-3">
						<label class='control-label'>Card Number:</label> 
						<input autocomplete='off' class='form-control card-number' placeholder='Your Card Number' size='20' type='text' name='card_number' value="<?php echo $cardno; ?>">
					</div>
					<div class="mb-3">
						<label class='control-label'>CVC:</label> 
						<input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text' name='card_cvc' value="<?php echo $cvc; ?>">
					</div>
					<div class='mb-3'>
						<label class='control-label'>Expiration Month:</label> 
						<input class='form-control card-expiry-month' placeholder='MM' size='2' type='text' name='card_exp_month' value="<?php echo $expirymonth; ?>">
					</div>
					<div class='mb-3'>
						<label class='control-label'>Expiration Year:</label> 
						<input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text' name='card_exp_year' value="<?php echo $expiryyear; ?>">
					</div>
					<div class='error hide'><div class='alert' style="color: red;"></div></div> 
				   	<div class="mb-3 stripepaybutton">
						<input type="hidden" value="<?php echo $userdetails['id']; ?>" name="userid">
						<input type="hidden" value="<?php echo $userdetails['email']; ?>" name="email">
						<button class="btn btn-primary btn-lg btn-block" type="submit" >Pay Now</button>
				   	</div>
		        </form>
		    </div>
	    </div>
  	</div>
</div>

<form action="" method="post" class="stripeconfirm">
	<div class="mb-3 stripepaybutton">
		<input type="hidden" value="<?php echo $userdetails['id']; ?>" name="id">
		<input type="hidden" value="1" name="stripepay">
		<input type="hidden" value="" name="stripepayid" class="stripepayid">
	</div>
</form>

<div class="stripeiframe displaynone">
	<div></div>
</div>

<script>
$(function(){
	validation(
		'.stripeform',
		{
			name 	    : {
				required	: 	true
			},
			card_number     : {	
				required	: 	true
			},
			card_cvc 	    : {
				required	: 	true
			},					
			card_exp_month  : {
				required	: 	true
			},
			card_exp_year   : {
				required	: 	true
			}
		},
		{   
			name      : {
				required    : "Name field is required."
			},
			card_number     : {
				required    : "Card number field is required."
			},
			card_cvc        : {
				required    : "Card CVC field is required."
			},
			card_exp_month  : {
				required    : "Card Expiry Month field is required."
			},
		   card_exp_year   : {
				required    : "Card Expiry Year field is required."
			},
		}
	);

	var $form = $(".stripeform");
	$form.bind('submit', function(e) {
		if(!$form.valid()){
			return false;
		}
		
		e.preventDefault();
		Stripe.setPublishableKey('<?php echo $stripepublickey; ?>');
		Stripe.createToken({
			number: $('.card-number').val(),
			cvc: $('.card-cvc').val(),
			exp_month: $('.card-expiry-month').val(),
			exp_year: $('.card-expiry-year').val()
		}, stripeResponseHandler);
	});

	function stripeResponseHandler(status, response) {
		if (response.error) {
			$('.error').removeClass('hide').find('.alert').text(response.error.message);
		} else {
			$('.loader_wrapper').remove();
			
			ajax('<?php echo base_url()."/ajax/ajaxstripepayment"; ?>', $form.serializeArray(), {
				beforesend: function() {
					$('.modal-content').append('<div class="loader_wrapper"><img src="<?php echo base_url()."/assets/site/img/loading.svg"; ?>"></div>');
				},
				success: function(data){
					if(data.success.status=='1'){
						$('.stripepayid').val(data.success.id);
						if(data.success.url==''){
							$('.loader_wrapper').remove();
							$(".stripeconfirm").submit();
						}else{
							$('.stripeiframe').removeClass('displaynone');
							$('.stripeiframe div').html('<iframe src="'+data.success.url+'" width="400" height="400"></iframe>');
							$('.loader_wrapper').remove();
						}
					}else{
						$('.loader_wrapper').remove();
						$('.stripeiframe').find('iframe').remove();
						$(".stripeform").find('input[type=text]').empty();
						$('.stripeiframe').addClass('displaynone');
						$("#stripeFormModal").modal('hide');
					}
				},
				error: function(data){
					$('.loader_wrapper').remove();
					$('.error').removeClass('hide').find('.alert').text(data.responseJSON.message);
				}
			});
		}
	}
	
	window.addEventListener('message', function(ev) {
		if (ev.data === '3DS-authentication-complete') {
			$(".stripeconfirm").submit();
		}
	}, false);
	
});
</script>