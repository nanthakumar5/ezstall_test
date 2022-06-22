<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
	<?php
		$userid 		= getSiteUserID() ? getSiteUserID() : 0;
	    $currentdate 	= date("m-d-Y");
		$getcart 	 	= getCart('2');
		$cartevent 	 	= ($getcart && $getcart['event_id'] != $detail['id']) ? 1 : 0;
		$name 		 	= $detail['name'];
		$description 	= $detail['description'];
		$image 		 	= base_url().'/assets/uploads/event/'.$detail['image'];
		$profileimage 	= base_url().'/assets/uploads/profile/'.$detail['profile_image'];
	?>
	
	<?php if($cartevent==1){?>
		<div class="alert alert-success alert-dismissible fade show m-2" role="alert">
			For booking this stall remove other stalls from the cart <a href="<?php echo base_url().'/facility/detail/'.$getcart['event_id']; ?>">Go To Facility</a>
			<!--<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>-->
		</div>
	<?php } ?>
	
	<div class="container-lg"> 
		<div class="row">
			<div class="stalldetail-banner mt-4 mb-5">
				<img src="<?php echo $image; ?>">
			</div>
		</div>
	</div>
	
	<section class="container-lg">
		<div class="row">
			<div class="col-lg-8">
				<div class="stall-head">
					<div class="float-start">
						<img class="profile_pic" src="<?php echo $profileimage; ?>">
					</div>
					<div class="float-next">
						<h4 class="fw-bold"><?php echo $name; ?></h4>
					</div>
				</div>
				<?php echo ucfirst($description);?>
			</div>
			<div class="row m-0 p-0">
				<div class="col-md-9">
					<div class="border rounded pt-4 ps-3 pe-3 mt-4 mb-5">
						<h3 class="fw-bold mb-4">Book Your Stalls</h3>
						<div class="infoPanel form_check">
							<span class="infoSection flex-wrap">
								<span class="iconProperty col-md-12 w-auto pad_100 ">
									<input type="text" readonly id="stallcount"  value="0" placeholder="Number of Stalls">
									<span class="num_btn stallcount"></span>
								</span>
								<span class="iconProperty col-md-12 w-auto pad_100">			
									<input type="text" name="startdate" id="startdate" class ="checkdate checkin" autocomplete="off" placeholder="Check-In"/>
									<img src="<?php echo base_url() ?>/assets/site/img/calendar.svg" class="iconPlace" alt="Calender Icon">
								</span>
								<span class="iconProperty col-md-12 w-auto pad_100">
									<input type="text" name="enddate" id="enddate" class = "checkdate checkout" autocomplete="off"placeholder="Check-Out"/>
									<img src="<?php echo base_url() ?>/assets/site/img/calendar.svg" class="iconPlace" alt="Calender Icon">
								</span>
							</span>
						</div>

						<?php 
						$tabbtn = '';
						$tabcontent = '';
						foreach ($detail['barn'] as $barnkey => $barndata) {
							$barnid = $barndata['id'];
							$barnname = $barndata['name'];
							$barnactive = $barnkey=='0' ? ' show active' : '';
							$tabbtn .= '<button class="nav-link m-0'.$barnactive.'" data-bs-toggle="tab" data-bs-target="#barn'.$barnid.'" type="button" role="tab" aria-controls="barn'.$barnid.'" aria-selected="true">'.$barnname.'</button>';

							$tabcontent .= '<div class="tab-pane fade'.$barnactive.'" id="barn'.$barnid.'" role="tabpanel" aria-labelledby="nav-home-tab">
												<ul class="list-group">';
							foreach($barndata['stall'] as $stalldata){ 
								$stallenddate = $stalldata['end_date'];
								
								$tabcontent .= 	'<li class="list-group-item">
													<input class="form-check-input eventbarnstall stallid me-1" data-stallenddate="'.$stallenddate.'" data-price="'.$stalldata['price'].'" data-barnid="'.$stalldata['barn_id'].'" value="'.$stalldata['id'].'" name="checkbox"  type="checkbox">
													'.$stalldata['name'].'
													<span class="green-box stallavailability" data-stallid="'.$stalldata['id'].'" ></span>
												</li>';
							}
							$tabcontent .= '</ul></div>';
						}
						?>

						<div class="barn-nav mt-4">
							<nav>
								<div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
									<?php echo $tabbtn; ?>
								</div>
							</nav>
							<div class="tab-content" id="nav-tabContent">
								<?php echo $tabcontent; ?>
								<div class="row">
									<div class="btm-color">
										<p><span class="green-circle"></span>Available</p>
										<p><span class="yellow-circle"></span>Reserved</p>
										<p><span class="red-circle"></span>Occupied</p>
										<p><span class="brown-circle"></span>Expired</p>
									</div>
								</div>
							</div>    
						</div>
					</div>
					<?php if($detail['rv_flag'] =='1') { ?>
						<div class="border rounded pt-4 ps-3 pe-3 mt-4 mb-3">
							<h3 class="fw-bold mb-4">Book Your Rvhookups</h3>
							<?php 
							$tabbtn = '';
							$tabcontent = ''; 
							foreach ($detail['rvbarn'] as $rvkey => $rvdata) { 
								$rvid = $rvdata['id'];
								$rvname = $rvdata['name'];
								$rvactive = $rvkey=='0' ? ' show active' : '';
								$tabbtn .= '<button class="nav-link m-0'.$rvactive.'" data-bs-toggle="tab" data-bs-target="#barn'.$rvid.'" type="button" role="tab" aria-controls="barn'.$rvid.'" aria-selected="true">'.$rvname.'</button>';

								$tabcontent .= '<div class="tab-pane fade'.$rvactive.'" id="barn'.$rvid.'" role="tabpanel" aria-labelledby="nav-home-tab">
								<ul class="list-group">';
								foreach($rvdata['rvstall'] as $rvstalldata){
									$boxcolor  = 'green-box';
									$checkboxstatus = '';

									if($cartevent=='1' || $checkevent['status']=='0'){
										$checkboxstatus = 'disabled';
									}

									$tabcontent .= 	'<li class="list-group-item rvhookups">
									<input class="form-check-input rvbarnstall stallid me-1" data-price="'.$rvstalldata['price'].'" data-barnid="'.$rvstalldata['barn_id'].'" data-flag="2" value="'.$rvstalldata['id'].'" name="checkbox"  type="checkbox" '.$checkboxstatus.'>
									'.$rvstalldata['name'].'
									<span class="'.$boxcolor.' stallavailability" data-stallid="'.$rvstalldata['id'].'" ></span>
									</li>';
								}
								$tabcontent .= '</ul></div>';
							}
							?>
							<div class="barn-nav mt-4">
								<nav>
									<div class="nav nav-tabs" id="nav-tab" role="tablist">
										<?php echo $tabbtn; ?>
									</div>
								</nav>
								<div class="tab-content" id="nav-tabContent">
									<?php echo $tabcontent; ?>
									<div class="row">
										<div class="btm-color">
											<p><span class="green-circle"></span>Available</p>
											<p><span class="yellow-circle"></span>Reserved</p>
											<p><span class="red-circle"></span>Occupied</p>
										</div>
									</div>
								</div>    
							</div>
						</div>
					<?php } ?>
					<?php if($detail['feed_flag'] =='1') { ?>
						<div class="border rounded py-4 ps-3 pe-3 mt-4 mb-3">
							<h3 class="fw-bold mb-4">Book Your Feed</h3>
							<table class="table table-bordered table-hover mb-0">
								<thead class="table-dark">
									<tr>
										<td class="text-light">Product Name</td>
										<td class="text-light">Product Price</td>
										<td class="text-light">Product Quantity</td>
										<td class="text-light">Action</td>
									</tr>
								</thead>
								<?php foreach ($detail['feed'] as $feed) { ?>
									<tr>
										<td style="border: 1px solid #e4e4e4;"><?php echo $feed['name'];?></td>
										<td style="border: 1px solid #e4e4e4;"><?php echo $feed['price'];?></td>
										<td style="border: 1px solid #e4e4e4;">
											<input type="number" class="form-control quantity" data-productid="<?php echo $feed['id']?>" data-flag="3">
										</td>
										<td style="border: 1px solid #e4e4e4;">
											<button class="btn btn-primary feedcart form-control" data-price="<?php echo $feed['price']?>" data-productid="<?php echo $feed['id']?>" data-originalquantity="<?php echo $feed['quantity']?>">Add to Cart</button>
											<!--<button class="btn btn-danger">Remove</button>-->
										</td>
									</tr>
								<?php } ?>
							</table>
						</div>
					<?php } ?>
					<?php if($detail['shaving_flag'] =='1') { ?>
						<div class="border rounded py-4 ps-3 pe-3 mt-4 mb-3">
							<h3 class="fw-bold mb-4">Book Your Shaving</h3>
							<table class="table table-bordered table-hover mb-0">
								<thead class="table-dark">
									<tr>
										<td class="text-light">Product Name</td>
										<td class="text-light">Product Price</td>
										<td class="text-light">Product Quantity</td>
										<td class="text-light">Action</td>
									</tr>
								</thead>
								<?php foreach ($detail['shaving'] as $shaving) { ?>
									<tr>
										<td style="border: 1px solid #e4e4e4;"><?php echo $shaving['name'];?></td>
										<td style="border: 1px solid #e4e4e4;"><?php echo $shaving['price'];?></td>
										<td style="border: 1px solid #e4e4e4;">
											<input type="number" class="form-control quantity" data-productid="<?php echo $shaving['id']?>" data-flag="4">
										</td>
										<td style="border: 1px solid #e4e4e4;">
											<button class="btn btn-primary shavingcart form-control" data-price="<?php echo $shaving['price']?>" data-productid="<?php echo $shaving['id']?>" data-originalquantity="<?php echo $shaving['quantity']?>">Add to Cart</button>
											<!--<button class="btn btn-danger">Remove</button>-->
										</td>
									</tr>
								<?php } ?>
							</table>
						</div>
					<?php } ?>
				</div> 
				<div class="sticky-top checkout col-md-3 mt-4 h-100"></div>
			</div>
		</div>
	</section>
<?php $this->endSection() ?>

<?php $this->section('js') ?>
<script>
	var transactionfee 		= '<?php echo $settings['transactionfee']?>';
	var currencysymbol 		= '<?php echo $currencysymbol; ?>';
	var eventid 			= '<?php echo $detail["id"]; ?>';
	var stallid 			= '<?php echo $stalldata["id"]; ?>';
	var cartevent 			= '<?php echo $cartevent; ?>';
	var checkevent 			= '<?php echo $checkevent["status"]; ?>';
	var eventstartdate  	= '<?php echo $detail["start_date"] > date("Y-m-d") ? formatdate($detail["start_date"], 1) : 0; ?>';
	//var eventenddate 		= '<?php echo formatdate($detail["end_date"], 1); ?>';
	//var eventenddateadd 	= '<?php echo formatdate(date("Y-m-d", strtotime($detail["end_date"]." +1 day")), 1); ?>';
		
	uidatepicker(
		'#startdate', 
		{ 
			'mindate' 	: eventstartdate,
			'close' 	: function(selecteddate){
				var date = new Date(selecteddate)
				date.setDate(date.getDate() + 1);
				$("#enddate").datepicker( "option", "minDate", date );
			}
		}
	);

	uidatepicker('#enddate', { 'mindate' : eventstartdate });
	
	$(document).ready(function (){ 
		if(cartevent == 0 ){ 
			cart();
		}else{ 
			$("#startdate, #enddate").attr('disabled', 'disabled');
		}
	});
	
	$("#enddate").click(function(){
		var startdate 	= $("#startdate").val();
		if(startdate==''){
			$("#startdate").focus();
		}
	});
	
	$("#startdate, #enddate").change(function(){  
		setTimeout(function(){
			var startdate 	= $("#startdate").val(); 
			var enddate   	= $("#enddate").val(); 

			if(startdate!='' && enddate!=''){
				cart({type : '2', checked : 0}); 
				$('.stallid').prop('checked', false).removeAttr('disabled');
				$('.stallavailability').removeClass("yellow-box").removeClass("red-box").addClass("green-box");
				
				occupiedreserved(startdate, enddate);
			}
		}, 100);
	})
	
	function occupiedreserved(startdate, enddate){
		ajax(
			'<?php echo base_url()."/ajax/ajaxoccupied"; ?>',
			{ eventid : eventid, checkin : startdate, checkout : enddate },
			{
				success : function(data){
					$(data.success).each(function(i,v){ 
						$('.stallid[value='+v+']').prop('checked', true).attr('disabled', 'disabled');
						$('.stallavailability[data-stallid='+v+']').removeClass("green-box").addClass("red-box");
					});
				}
			}
		)
		
		ajax(
			'<?php echo base_url()."/ajax/ajaxreserved"; ?>',
			{ eventid : eventid, checkin : startdate, checkout : enddate },
			{
				asynchronous : 1,
				success : function(data){
					$.each(data.success, function (i, v) {
						$('.stallid[value='+i+']').prop('checked', true).attr('disabled', 'disabled');
						$('.stallavailability[data-stallid='+i+']').removeClass("green-box").addClass("yellow-box");
					});
				}
			}
		)
	} 

	$(".eventbarnstall").on("click", function() {
        cartaction($(this), 1);
    });
	
	$(".rvbarnstall").on("click", function() {
	 	cartaction($(this), 2);
	});

	$(".feedcart").on("click", function() {
	 	cartaction($(this), 3);
	});
	
	$(".shavingcart").on("click", function() {
	 	cartaction($(this), 4);
	});

	$(".quantity").keyup(function(){
		checkdate($(this).attr('data-flag'));
	})

	function cartaction(_this, flag){ 
		var datevalidation = checkdate(flag);
		if(!datevalidation) return false;
		
		var startdate 	= $("#startdate").val(); 
		var enddate   	= $("#enddate").val(); 
			
		if(flag==1 || flag==2){
			var barnid    	= _this.attr('data-barnid');
			var stallid		= _this.val(); 
			var price 		= _this.attr('data-price');


			if($(_this).is(':checked')){  
				cart({event_id : eventid, barn_id : barnid, stall_id : stallid, price : price, quantity : 1, startdate : startdate, enddate : enddate, type : '1', checked : 1, flag : flag, actionid : ''});
			}else{ 
				$('.stallavailability[data-stallid='+stallid+']').removeClass("yellow-box").addClass("green-box");
				cart({stall_id : stallid, type : '1', checked : 0}); 
			}
		
		}else{
			var productid      		= _this.attr('data-productid');
			var price         		= _this.attr('data-price'); 
			var originalquantity	= _this.attr('data-originalquantity'); 
			var quantitywrapper		= _this.parent().parent().find('.quantity');
			var quantity 			= quantitywrapper.val();
			
			if(quantity==""){ 
				quantitywrapper.focus();
				toastr.warning('Please Enter Quantity .', {timeOut: 5000});
			}else if(parseInt(quantity) > parseInt(originalquantity)){
				quantitywrapper.focus();
				toastr.warning('Please Select Quantity Less Than.'+originalquantity, {timeOut: 5000});
			}else{ 
				cart({event_id : eventid, product_id : productid, price : price, quantity : quantity, startdate : startdate, enddate : enddate, type : '1', checked : 1, flag : flag, actionid : ''});
			}
		}
	}

	function checkdate(flag){
		var classarray = ['eventbarnstall', 'rvbarnstall', 'feedcart', 'shavingcart'];
		
		if(flag==1 || flag==2){
			if($("."+classarray[flag-1]+":checked").length > 0){			
				var datevalidation = datetoastr()
				if(!datevalidation){
					$("."+classarray[flag-1]+":not(:disabled)").prop('checked', false);
					return false;
				}
			}
		}else{
			var datevalidation = datetoastr()
			if(!datevalidation){
				$("."+classarray[flag-1]).each(function(){
					$(this).parent().parent().find('.quantity').val('');
				});
				
				return false;
			}
		}
		
		return true;
	}
	
	function datetoastr(){
		var startdate 	= $("#startdate").val(); 
		var enddate   	= $("#enddate").val(); 
		
		if(startdate=='' || enddate==''){
			if(startdate==''){
				$("#startdate").focus();
				toastr.warning('Please select the Check-In Date.', {timeOut: 5000});
			}else if(enddate==''){
				$("#enddate").focus();
				toastr.warning('Please select the Check-Out Date.', {timeOut: 5000});
			}

			return false;
		}
		
		return true;
	}

	function cart(data={cart:1, type:1}){	
		ajax(
			'<?php echo base_url()."/cart"; ?>',
			data,
			{ 
				asynchronous : 1,
				success  : function(result){
					if(Object.keys(result).length){  
						$("#startdate").val(result.check_in); 
						$("#enddate").val(result.check_out); 
						
						occupiedreserved($("#startdate").val(), $("#enddate").val());
						
						var barnstalldata = '';
						if(result.barnstall.length){
							var barnname = '';
							barnstalldata += '<div class="event_cart_title"><span class="col-12 fw-bold">STALL</span></div>';
							$(result.barnstall).each(function(i,v){
								if(barnname!=v.barn_name){
									barnstalldata += '<div ><span class="col-12 fw-bold">'+v.barn_name+'</span></div>';
								}
								
								barnstalldata += '<div class="row"><span class="col-7 event_c_text">'+v.stall_name+'</span><span class="col-5 text-end event_c_text">('+v.price+'x'+v.interval+') '+v.total+'</span></div>';
								$('.stallid[value='+v.stall_id+']').removeAttr('disabled');
								barnname = v.barn_name;
							});
						}
						
						var rvbarnstalldata = '';
						if(result.rvbarnstall.length){
							var barnname = '';
							rvbarnstalldata += '<div class="event_cart_title"><span class="col-12 fw-bold">RV HOOKUP</span></div>';
							$(result.rvbarnstall).each(function(i,v){
								if(barnname!=v.barn_name){
									rvbarnstalldata += '<div class="e_cart_subtitle"><span class="col-12 fw-bold">'+v.barn_name+'</span></div>';
								}
								
								rvbarnstalldata += '<div class="row"><span class="col-7 event_c_text">'+v.stall_name+'</span><span class="col-5 text-end event_c_text">('+v.price+'x'+v.interval+') '+v.total+'</span></div>';
								$('.stallid[value='+v.stall_id+']').removeAttr('disabled');
								barnname = v.barn_name;
							});
						}
						
						var feeddata = '';
						if(result.feed.length){
							feeddata += '<div class="event_cart_title"><span class="col-12 fw-bold">Feed</span></div>';
							$(result.feed).each(function(i,v){								
								feeddata += '<div class="row"><span class="col-7 event_c_text">'+v.product_name+'</span><span class="col-5 text-end event_c_text">('+v.price+'x'+v.quantity+') '+v.total+'</span></div>';
								$('.quantity[data-productid='+v.product_id+']').val(v.quantity);
							});
						}
						
						var shavingdata = '';
						if(result.shaving.length){
							shavingdata += '<div class="event_cart_title"><span class="col-12 fw-bold">Shaving</span></div>';
							$(result.shaving).each(function(i,v){
								shavingdata += '<div class="row"><span class="col-7 event_c_text">'+v.product_name+'</span><span class="col-5 text-end event_c_text">('+v.price+'x'+v.quantity+') '+v.total+'</span></div>';
								$('.quantity[data-productid='+v.product_id+']').val(v.quantity);
							});
						}
						
						$('#stallcount').val(result.barnstall.length);
						var total = (parseFloat(result.price)+parseFloat((transactionfee/100) * result.price)).toFixed(2);
						var result ='\
						<div class="w-100">\
							<div class="border rounded pt-4 ps-3 pe-3 mb-5">\
								<div class="row mb-2">\
									<div class="col-md-12">\
										<div class="row"> <span class="col-6 fw-bold">Total Day :</span><span class="col-6 fw-bold text-end">'+result.interval+'</span></div>\
										'+barnstalldata+'\
										'+rvbarnstalldata+'\
										'+feeddata+'\
										'+shavingdata+'\
									</div>\
								</div>\
								<div class="row mb-2 event_border_top pt-4">\
									<div class="col-8 event_c_text">Total</div>\
									<div class="col-4 event_c_text text-end">'+currencysymbol+result.price.toFixed(2)+'\</div>\
									<div class="col-8 event_c_text">Transaction Fees</div>\
									<div class="col-4 event_c_text text-end">'+currencysymbol+((transactionfee/100) * result.price).toFixed(2)+'\</div>\
								</div>\
								<div class="row mb-2 border-top mt-3 mb-3 pt-3">\
									<div class="col-8 fw-bold ">Total Due</div>\
									<div class="col-4 fw-bold">'+currencysymbol+total+'</div>\
								</div>\
								<div class="row mb-2 w-100">\
									<a href="<?php echo base_url()?>/checkout" class="w-100 text-center mx-2 ucEventdetBtn ps-3 mb-3 ">Continue to Checkout</a>\
								</div>\
							</div>\
						</div>\
						';

						$('.checkout').empty().append(result);
					}else{
						$('#stallcount').val(0);
						$('.checkout').empty();
					}
				}
			}
			);
	}
</script>
<?php echo $this->endSection() ?>