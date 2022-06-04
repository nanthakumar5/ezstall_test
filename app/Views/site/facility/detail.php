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
						<img src="<?php echo base_url() ?>/assets/site/img/stallhead.png">
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
							$tabbtn .= '<button class="nav-link'.$barnactive.'" data-bs-toggle="tab" data-bs-target="#barn'.$barnid.'" type="button" role="tab" aria-controls="barn'.$barnid.'" aria-selected="true">'.$barnname.'</button>';

							$tabcontent .= '<div class="tab-pane fade'.$barnactive.'" id="barn'.$barnid.'" role="tabpanel" aria-labelledby="nav-home-tab">
												<ul class="list-group">';
							foreach($barndata['stall'] as $stalldata){ 
								$stallenddate = $stalldata['end_date'];
								
								$tabcontent .= 	'<li class="list-group-item">
													<input class="form-check-input stallid me-1" data-stallenddate="'.$stallenddate.'" data-price="'.$stalldata['price'].'" data-barnid="'.$stalldata['barn_id'].'" value="'.$stalldata['id'].'" name="checkbox"  type="checkbox">
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
	
	uidatepicker(
		'#startdate', 
		{ 
			'mindate' 	: '0',
			'close' 	: function(selecteddate){
				var date = new Date(selecteddate)
				date.setDate(date.getDate() + 1);
				$("#enddate").datepicker( "option", "minDate", date );
			}
		}
	);

	uidatepicker('#enddate', { 'mindate' : '0' });
	
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
				
				validatestalldates(enddate);
				occupiedreserved(startdate, enddate);
			}
		}, 100);
	})

	function validatestalldates(enddate){ 
		$('.stallid').each(function(){
			var stallenddate		= $(this).attr('data-stallenddate');
			var stallid	 			= $(this).val();

			$('.stallid[value='+stallid+']').removeAttr('disabled', 'disabled');
				$('.stallavailability[data-stallid='+stallid+']').removeClass("brown-box").addClass("green-box");
			if(Date.parse(stallenddate) < Date.parse(enddate)){ 
				$('.stallid[value='+stallid+']').attr('disabled', 'disabled');
				$('.stallavailability[data-stallid='+stallid+']').removeClass("green-box").addClass("brown-box");
			}
		});
	}
	
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

	$(".form-check-input").on("click", function() {
		checkdate();

		var startdate 			= $("#startdate").val(); 
		var enddate   			= $("#enddate").val(); 
		var barnid    			= $(this).attr('data-barnid');
		var stallid				= $(this).val(); 
		var price 				= $(this).attr('data-price');
		
		if($(this).is(':checked')){ 
			cart({stall_id : stallid, event_id : eventid, barn_id : barnid, price : price, startdate : startdate, enddate : enddate, type : '2',  checked : 1, actionid : ''});
		}else{
			$('.stallavailability[data-stallid='+stallid+']').removeClass("yellow-box").addClass("green-box");
			cart({stall_id : stallid, type : '2', checked : 0}); 
		}
	});

	function checkdate(){ 
		var startdate 	= $("#startdate").val(); 
		var enddate   	= $("#enddate").val(); 

		if($(".form-check-input:checked").length > 0){			
			if(startdate=='' || enddate==''){
				if(startdate==''){
					$("#startdate").focus();
					toastr.warning('Please select the Check-In Date.', {timeOut: 5000});
				}else if(enddate==''){
					$("#enddate").focus();
					toastr.warning('Please select the Check-Out Date.', {timeOut: 5000});
				}

				$(".form-check-input:not(:disabled)").prop('checked', false);
				return false;
			}
		}
	}

	function cart(data={cart:1, type:2}){	 
		ajax(
			'<?php echo base_url()."/cart"; ?>',
			data,
			{ 
				asynchronous : 1,
				success  : function(result){
					if(Object.keys(result).length){  
						$("#startdate").val(result.check_in); 
						$("#enddate").val(result.check_out); 
						
						validatestalldates($("#enddate").val());
						occupiedreserved($("#startdate").val(), $("#enddate").val());
						
						$(result.barnstall).each(function(i,v){ 
							$('.stallid[value='+v.stall_id+']').removeAttr('disabled');
						});

						$('#stallcount').val(result.barnstall.length);
						var total = (parseFloat(result.price)+parseFloat((transactionfee / 100) * result.price)).toFixed(2);
						var result ='\
						<div class="w-100">\
						<div class="border rounded pt-4 ps-3 pe-3 mb-5">\
						<div class="row mb-2">\
						<div class="col-md-8 ">\
						<span>'+result.barnstall.length+'</span> Stalls x \
						<span>'+result.interval+'</span> Nights \
						</div>\
						<div class="col-4">\
						'+currencysymbol+result.price+'\
						</div>\
						</div>\
						<div class="row mb-2">\
						<div class="col-8 ">Transaction Fees</div>\
						<div class="col-4">'+currencysymbol+((transactionfee/100) * result.price).toFixed(2)+'\</div>\
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