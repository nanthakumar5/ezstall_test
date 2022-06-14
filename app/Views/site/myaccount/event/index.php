<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<?php
$checksubscription 			= checkSubscription();
$checksubscriptiontype 		= $checksubscription['type'];
$checksubscriptionproducer 	= $checksubscription['producer'];
$currentdate 	= date("Y-m-d");
?>
<section class="maxWidth eventPagePanel mt-2">
	<?php if($usertype !='4'){ ?>
		<a class="btn-custom-black addevent" href="<?php echo base_url().'/myaccount/events/add'; ?>">Add Event</a>
	<?php } ?>
	<?php  if($checksubscriptiontype=='3' && $checksubscriptionproducer <= $eventcount){ ?>
		<button class="btn btn-primary paynow"  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stripeFormModal" data-bs-whatever="@getbootstrap">Pay Now to Add Event</button>
	<?php } ?>
	<?php if(count($list) > 0){ ?>
		<?php foreach ($list as $data) {  ?>
			<div class="dashboard-box mt-4">
				<div class="row align-items-center px-2">
					<div class="col-md-2 myaccevent1">
						<img src="<?php echo base_url() ?>/assets/uploads/event/<?php echo $data['image']?>" class="dash-event-image">
					</div>
					<div class="col-md-5 myaccevent2">
						<p class="topdate fs-7 mb-2"> <?php echo date('m-d-Y', strtotime($data['start_date'])); ?> - <?php echo date('m-d-Y', strtotime($data['end_date'])); ?> -  <?php echo $data['location']; ?></p>
						<a class="text-decoration-none" href="<?php echo base_url() ?>/events/detail/<?php echo $data['id']?>"><p class="fs-6 fw-bold"><?php echo $data['name']; ?><p></a></p>
					</div>
					<div class="col-md-5 d-flex myaccevent3">
						<div class="m-left w-100 md-left">
							<p class="fs-7 mb-2"><img class="eventFirstIcon" src="<?php echo base_url()?>/assets/site/img/horseShoe.svg">Stalls</p>
							<p class="ucprice fs-7 fw-bold"> from $<?php echo $data['stalls_price'] ?> / night</p>
						</div>
						<!-- <div class="m-left w-100">
							<p class="fs-7 mb-2"><img class="eventSecondIcon" src="<?php //echo base_url()?>/assets/site/img/rvSpot.svg">RV Spots</p>
							<p class="ucprice fs-7 fw-bold">from $<?php //echo $data['rvspots_price'] ?> / night</p>
						</div> -->
					</div>
				</div>
				<div class="dash-event">
					<a href="<?php echo base_url().'/myaccount/events/eventreport/'.$data['id']; ?>" 
						class="dash-export-event fs-7 mx-2">
						Report <i class="fas fa-file-export i-white-icon"></i>
					</a>
				<?php if($usertype !='4'){ ?>
					<a href="<?php echo base_url().'/myaccount/events/export/'.$data['id']; ?>" 
						class="dash-export-event fs-7 mx-2">
						Export <i class="fas fa-file-export i-white-icon"></i>
					</a>
				<?php } ?>
				<a href="<?php echo base_url().'/myaccount/events/view/'.$data['id']; ?>" 
					class="dash-view-event fs-7 mx-2">
					View <i class="far fa-eye i-white-icon"></i>
				</a>
				<?php if($currentdate <= $data['end_date']){ ?>
				    <?php if($usertype !='4'){ ?>
						<a href="<?php echo base_url().'/myaccount/events/edit/'.$data['id']; ?>" 
							class="dash-edit-event fs-7 mx-2">
							Edit <i class="far fa-edit i-white-icon"></i>
						</a>
						
						<?php $occupied = getOccupied($data['id']); ?>
						<?php if(count($occupied)==0){ ?>
							<a data-id="<?php echo $data['id']; ?>" href="javascript:void(0);" class="dash-delete-event fs-7 mx-2 delete">
								Delete <i class="far fa-trash-alt i-white-icon"></i>
							</a>
						<?php }?>
				    <?php }?>
				 <?php }?>

				</div>
			</div>
			<?php } ?>
	<?php } else{ ?>
		<p class="mt-3">No Record Found</p>
	<?php } ?>
	<?php echo $pager; ?>
</section>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
<?php echo $stripe; ?>
<script>
	var userid = '<?php echo $userid; ?>';
	var eventcost = parseFloat('<?php echo $settings["producereventfee"]; ?>');
	var currencysymbol = '<?php echo $currencysymbol; ?>';

	$(document).on('click','.delete',function(){
		var action 	= 	'<?php echo base_url()."/myaccount/events"; ?>';
		var data   = '\
		<input type="hidden" value="'+$(this).data('id')+'" name="id">\
		<input type="hidden" value="'+userid+'" name="userid">\
		<input type="hidden" value="0" name="status">\
		';
		sweetalert2(action,data);
	});	

	$('#stripeFormModal').on('shown.bs.modal', function () {
		$('.stripeextra').remove();

		var data = 	'<div class="stripeextra">\
						<input type="hidden" value="'+eventcost+'" name="price">\
						<input type="hidden" value="1" name="type">\
					</div>';

		$('.stripetotal').text(' (Total - '+currencysymbol+eventcost+')');
		$('.stripepaybutton').append(data);
	})
</script>
<?php $this->endSection(); ?>
