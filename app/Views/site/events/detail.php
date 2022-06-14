<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<?php 
$userid 	= getSiteUserID() ? getSiteUserID() : 0;
$getcart 	= getCart('1');
$cartevent 	= ($getcart && $getcart['event_id'] != $detail['id']) ? 1 : 0;
?>
<body style="overflow: initial;">
	<section class="maxWidth">
		<div class="pageInfo">
			<span class="marFive">
				<a href="<?php echo base_url(); ?>">Home /</a>
				<a href="<?php echo base_url().'/events'; ?>"> Events /</a>
				<a href="javascript:void(0);"><?php echo $detail['name'] ?></a>
			</span>
		</div>
		<?php if($cartevent==1){?>
			<div class="alert alert-success alert-dismissible fade show m-2" role="alert">
				For booking this event remove other event from the cart <a href="<?php echo base_url().'/events/detail/'.$getcart['event_id']; ?>">Go To Event</a>
				<!--<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>-->
			</div>
		<?php } ?>
		<div class="marFive dFlexComBetween eventTP pb-3 pt-4">
			<div class="pageInfo m-0 bg-transparent">
				<span class="eventHead">
					<a href="<?php echo base_url().'/events'; ?>" class="d-block"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
						<path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
					</svg> Back To All Events</a>
				</span>
			</div>
		</div>
	</section>
	<section class="container-lg">
		<div class="row" style="display: initial !important;">
			<div class="col-md-12">
				<div class="border rounded pt-5 ps-3 pe-3 myaccupevent">
					<div class="row myaccupevent1">
						<div class="col-6">
							<span class="edimg">
								<img src="<?php echo base_url() ?>/assets/uploads/event/<?php echo $detail['image']?>" width="350px" height="auto">
							</span>
						</div>
						<div class="col-6">
							<h4 class="checkout-fw-6"><?php echo $detail['name'] ?></h4>
							<ul class="edaddr">
								<li class="mb-3 mt-3">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
										<path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
									</svg> 
									<?php echo $detail['location'] ?>
								</li>
							<!-- <li class="mb-3">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar4" viewBox="0 0 16 16">
									<path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1H2zm13 3H1v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5z"/>
								</svg> 
								<?php //echo date('m-d-Y', strtotime($detail['start_date'])); ?>
							</li> -->
							<li class="mb-3">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
									<path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
								</svg> 
								<?php echo $detail['mobile'] ?>
							</li>
							<div class="row">
								<span class="col-6">
									<p class="mb-1 fw-bold"><img class="eventFirstIcon" src="<?php echo base_url()?>/assets/site/img/stall.jpg">Stalls</p>
									<h6 class="ucprice"> from $<?php echo $detail['stalls_price'] ?> / night</h6>
								</span>
								<!-- <span class="col-6">
									<p class="mb-1 fw-bold"><img class="eventSecondIcon" src="<?php //echo base_url()?>/assets/site/img/rv.jpg">RV Spots</p>
									<h6 class="ucprice">from $<?php //echo $detail['rvspots_price'] ?> / night</h6>
								</span> -->
							</div>
							<?php echo $detail['description'] ?>
						</ul>
					</div>
					<div class="col-12 mb-5 mt-2">
						<p>Contact the stall manager at <?php echo $detail['mobile'] ?> for more information and stall maps.</p>
						<?php if($detail['eventflyer']!=""){ ?>
							<button class="ucEventdetBtn"><a href="<?php echo base_url();?>/event/pdf/<?php echo $detail['eventflyer'] ?>" class="text-decoration-none text-white"><img src="<?php echo base_url() ?>/assets/site/img/flyer.png"> Download Event Flyer</a></button>
						<?php } ?>
					</div>
				</div>
				<div class="row row border-top pt-4 pb-4 eventdate">
					<span class="col-3">
						<p class="mb-1 fw-bold"><img class="eventDIcon" src="<?php echo base_url() ?>/assets/site/img/date.png"> Start Date: </p>
						<p class="ucDAte mb-0">
							<?php echo formatdate($detail['start_date'], 1);?></p>
						</span>
						<span class="col-3 border-end">
							<p class="mb-1 fw-bold"><img class="eventDIcon" src="<?php echo base_url() ?>/assets/site/img/date.png"> End Date: </p>
							<p class="ucDAte mb-0"><?php echo formatdate($detail['end_date'], 1); ?></p>
						</span>
						<span class="col-3">
							<p class="mb-1 fw-bold"><img class="eventDIcon" src="<?php echo base_url() ?>/assets/site/img/time.png"> Start Time: </p>
							<p class="ucDAte mb-0"> after <?php echo formattime($detail['start_time']) ?></p>
						</span>
						<span class="col-3">
							<p class="mb-1 fw-bold"><img class="eventDIcon" src="<?php echo base_url() ?>/assets/site/img/time.png"> End Time:</p>
							<p class="ucDAte mb-0">by <?php echo formattime($detail['end_time']) ?></p>
						</span>
					</div> 
				</div>
			</div>
			<div class="row m-0 p-0">
				<div class="col-md-9 tabook">
					<div class="border rounded pt-4 ps-3 pe-3 mt-4 mb-5 ">
						<h3 class="fw-bold mb-4">Book Your Stalls</h3>
						<div class="infoPanel form_check bookyourstalls">
							<span class="infoSection bookborder flex-wrap">
								<span class="iconProperty col-md-12 w-auto pad_100">
									<input type="text" readonly id="stallcount"  value="0" placeholder="Number of Stalls" class="borderyt">
									<span class="num_btn stallcount"></span>
								</span>
								<span class="iconProperty col-md-12 w-auto pad_100">			
									<input type="text" name="startdate" id="startdate" class="land_width checkdate checkin borderyt" autocomplete="off" placeholder="Check-In"/>
									<img src="<?php echo base_url() ?>/assets/site/img/calendar.svg" class="iconPlace" alt="Calender Icon">
								</span>
								<span class="iconProperty col-md-12 w-auto pad_100">
									<input type="text" name="enddate" id="enddate" class="land_width checkdate checkout borderyt" autocomplete="off"placeholder="Check-Out"/>
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
								$boxcolor  = 'green-box';
								$checkboxstatus = '';

								if($cartevent=='1' || $checkevent['status']=='0'){
									$checkboxstatus = 'disabled';
								}

								$tabcontent .= 	'<li class="list-group-item">
								<input class="form-check-input stallid me-1" data-price="'.$stalldata['price'].'" data-barnid="'.$stalldata['barn_id'].'" value="'.$stalldata['id'].'" name="checkbox"  type="checkbox" '.$checkboxstatus.'>
								'.$stalldata['name'].'
								<span class="'.$boxcolor.' stallavailability" data-stallid="'.$stalldata['id'].'" ></span>
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
</body>
<?php $this->endSection() ?>

<?php $this->section('js') ?>
<script> 
	var transactionfee		= '<?php echo $settings['transactionfee'];?>';  
	var currencysymbol 		= '<?php echo $currencysymbol; ?>';
	var eventid 			= '<?php echo $detail["id"]; ?>';
	var cartevent 			= '<?php echo $cartevent; ?>';
	var checkevent 			= '<?php echo $checkevent["status"]; ?>';
	var eventstartdate  	= '<?php echo $detail["start_date"] > date("Y-m-d") ? formatdate($detail["start_date"], 1) : 0; ?>';
	var eventenddate 		= '<?php echo formatdate($detail["end_date"], 1); ?>';
	var eventenddateadd 	= '<?php echo formatdate(date("Y-m-d", strtotime($detail["end_date"]." +1 day")), 1); ?>';

	$(document).ready(function (){
		if(cartevent == 0 ){
			cart();
		}else{
			$("#startdate, #enddate").attr('disabled', 'disabled');
		}

		if(checkevent == 0){
			$("#startdate, #enddate").attr('disabled', 'disabled');
		}

		uidatepicker(
			'#startdate', 
			{ 
				'mindate' 	: eventstartdate,
				'maxdate' 	: eventenddate,
				'close' 	: function(selecteddate){
					var date = new Date(selecteddate)
					date.setDate(date.getDate() + 1);
					$("#enddate").datepicker( "option", "minDate", date );
				}
			}
		);

		uidatepicker('#enddate', { 'mindate' : eventstartdate, 'maxdate' : eventenddateadd });
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
				cart({type : '1', checked : 0}); 
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
	
	$(".form-check-input").on("click", function() {
		checkdate();

		var startdate 	= $("#startdate").val(); 
		var enddate   	= $("#enddate").val(); 
		var barnid    	= $(this).attr('data-barnid');
		var stallid		= $(this).val(); 
		var price 		= $(this).attr('data-price');

		if($(this).is(':checked')){
			cart({stall_id : stallid, event_id : eventid, barn_id : barnid, price : price, startdate : startdate, enddate : enddate, type : '1',  checked : 1, actionid : ''});
		}else{
			$('.stallavailability[data-stallid='+stallid+']').removeClass("yellow-box").addClass("green-box");
			cart({stall_id : stallid, type : '1', checked : 0}); 
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
						
						$(result.barnstall).each(function(i,v){ 
							$('.stallid[value='+v.stall_id+']').removeAttr('disabled');
						});

						$('#stallcount').val(result.barnstall.length);
						var total = (parseFloat(result.price)+parseFloat((transactionfee/100) * result.price)).toFixed(2);
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