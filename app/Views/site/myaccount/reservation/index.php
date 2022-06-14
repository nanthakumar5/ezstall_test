<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>

<div class="dFlexComBetween eventTP flex-wrap">
	<h2 class="fw-bold mb-4">Current Reservation</h2>
	<?php if(!empty($bookings)) {  ?>
		<div class="flex-row-reverse bd-highlight"> 
			<input type="text" placeholder="Search By Name" class="searchEvent bookedby" id="bookedby" value="" />
			<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="searchIcon searchiconreserve" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M456.69 421.39L362.6 327.3a173.81 173.81 0 0034.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 00327.3 362.6l94.09 94.09a25 25 0 0035.3-35.3zM97.92 222.72a124.8 124.8 0 11124.8 124.8 124.95 124.95 0 01-124.8-124.8z"></path>
                                        </svg>
		</div>
	<?php } ?>
</div>

<section class="maxWidth eventPagePanel">
	<?php if(!empty($bookings)) {  ?>
		<?php foreach ($bookings as $data) { ?>
			<div class="dashboard-box">
				<div class="EventFlex leftdata">
					<div class="wi-30 row w-100 align-items-center">
						<div class="row row m-0 p-0 dash-booking">
							<div class="col-md-3 col-lg-3 mb-2">
								<div>
									<p class="mb-0 text-sm fs-7 fw-600 width100">Payment Method</p>
									<p class="mb-0 fs-7 width100"><?php echo $data['paymentmethod_name'];?></p>
								</div>
							</div>
							<div class="col-md-3 col-lg-2 mb-2">
								<div>
									<p class="mb-0 text-sm fs-7 fw-600 width100">Booking ID</p>
									<p class="mb-0 fs-7 width100"><?php echo $data['id'];?></p>
								</div>
							</div>
							<div class="col-md-3 col-lg-3 mb-2">
								<div>
									<p class="mb-0 fs-7 fw-600 width100">Booked By</p>
									<p class="mb-0 fs-7 width100"><?php echo $usertype[$data['usertype']]; ?></p>
								</div>
							</div>
							<div class="col-md-3 col-lg-3 mb-2">
								<div>
									<p class="mb-0 fs-7 fw-600 width100">Date of booking</p>
									<p class="mb-0 fs-7 width100"><?php echo date("m-d-Y h:i A", strtotime($data['created_at']));?></p>
								</div>

							</div>
					
						<div class="col-md-3 col-lg-3">
							<div>
								<p class="mb-0 text-sm fs-7 fw-600 width100">Name</p>
								<p class="mb-0 fs-7 width100"><?php echo $data['firstname'].$data['lastname'];?></p>
							</div>
						</div>
						<div class="col-md-3 col-lg-2">
								<div>
									<p class="mb-0 fs-7 fw-600 width100">Booked Event</p>
									<p class="mb-0 fs-7 width100"><?php echo $data['eventname'];?> (
										<?php 
										$stallname = [];
										foreach ($data['barnstall'] as $stalls) {
											$stallname[] = $stalls['stallname'];
										}
										echo implode(',', $stallname);
									?>)
								</p>
							</div>
						</div>
						<div class="col-md-3 col-lg-3">
							<div>
								<p class="mb-0 fs-7 fw-600 width100">CheckIn - CheckOut</p>
								<p class="mb-0 fs-7 width100"><?php echo formatdate($data['check_in'], 1);?> - <?php echo formatdate($data['check_out'], 1);?></p>
							</div>
						</div>
						<div class="col-md-3 col-lg-2">
							<div>
								<p class="mb-0 fs-7 fw-600 width100">Cost</p>
								<p class="mb-0 fs-7 width100"><?php echo $currencysymbol.$data['amount'];?></p>
							</div>
						</div>
						<div class="row col-md-10 base-style">
					        <div class="col fw-600">
					          <p class="my-2">Status</p>
					        </div>
					        <div class="col" align="left">
					        	<?php $statuscolor = ($data['status']=='2') ? "cancelcolor" : "activecolor"; ?>
					          		<p class="my-2 <?php echo  $statuscolor;?>" ><?php echo $bookingstatus[$data['status']];?></p>
					        </div>
					    </div>
						<div class="col-md-3 col-lg-2">
							<div class="d-flex justify-content-end align-items-center viewstart">
								<a href="<?php echo base_url().'/myaccount/bookings/view/'.$data['id']; ?>" class="mt-0 mx-3 view-res">View</a>
								<?php if($data['status']=='1'){ ?>
									<?php $amount = $data['amount']-($data['amount'] * 10/100); ?>
									<a href="javascript:void(0);" style='align: right;' data-id='<?php echo $data['id']; ?>' data-paymentid='<?php echo $data['paymentid']; ?>' data-paymentintentid='<?php echo $data['stripe_paymentintent_id']; ?>' data-amount='<?php echo $amount; ?>' class="striperefunds">
										<i class="fas fa-times-circle" style="font-size: 30px;"></i> 
									</a>
								<?php } ?>
							</div>
								</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	<?php }else{ ?>
		<p>No Reservation Found.</p>
	<?php } ?>
</section>
<?php echo $pager; ?>
<?php $this->endSection(); ?>

<?php $this->section('js') ?>
<script>
	var baseurl = "<?php echo base_url(); ?>";

	$(document).ready(function() {
		$("#bookedby").autocomplete({
			source: function(request, response) {
				ajax(baseurl+'/myaccount/bookings/searchbookeduser', {search: request.term}, {
					success: function(result) {
						response(result);
					}
				});
			},
			html: true, 
			select: function(event, ui) {
				var name = ui.item.firstname+ui.item.lastname
				$('#bookedby').val(name); 
				window.location.href = baseurl+'/myaccount/bookings/view/'+ui.item.id;
				return false;
			},
			focus: function(event, ui) {
				$("#bookedby").val(name);
				return false;
			}
		})
		.autocomplete("instance")
		._renderItem = function( ul, item ) {
			var name = item.firstname+item.lastname
			return $( "<li><div>"+name+"</div></li>" ).appendTo( ul );
		};
	});
	
	$(document).on('click','.striperefunds', function(){
		var action 	= 	'<?php echo base_url()."/myaccount/bookings"; ?>';
		var data   = '\
		<input type="hidden" name="id" value="'+$(this).attr("data-id")+'">\
		<input type="hidden" name="paymentid" value="'+$(this).attr("data-paymentid")+'">\
		<input type="hidden" name="paymentintentid" value="'+$(this).attr("data-paymentintentid")+'">\
		<input type="hidden" name="amount" value="'+$(this).attr("data-amount")+'">\
		';
		sweetalert2(action,data);
	});	
</script>
<?php $this->endSection(); ?>
