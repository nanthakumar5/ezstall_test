<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<?php
$checksubscription 				= checkSubscription();
$checksubscriptiontype 			= $checksubscription['type'];
$checksubscriptionproducer 		= $checksubscription['facility'];
$currentdate 					= date("Y-m-d");
?>
<section class="maxWidth eventPagePanel mt-2">
	<?php if($usertype !='4'){ ?>
		<a class="btn-custom-black" href="<?php echo base_url().'/myaccount/facility/add'; ?>">Add Facility</a>
	<?php } ?>

	<?php if(count($list) > 0){ ?>
		<?php foreach ($list as $data) {  ?>
			<div class="dashboard-box mt-4">
				<div class="row align-items-center px-2">
					<div class="col-md-2">
						<img src="<?php echo base_url() ?>/assets/uploads/event/<?php echo $data['image']?>" class="dash-event-image">
					</div>
					<div class="col-md-5">
						<a class="text-decoration-none" href="<?php echo base_url() ?>/events/detail/<?php echo $data['id']?>"><p class="fs-6 fw-bold"><?php echo $data['name']; ?><p></a></p>
						<a class="text-decoration-none" href="<?php echo base_url() ?>/events/detail/<?php echo $data['id']?>"><p class="fs-6 fw-bold"><?php echo substr($data['description'], 0,50); ?><p></a></p>
					</div>
				</div>
				<div class="dash-event">
				<?php if($usertype !='4'){ ?>
					<a href="<?php echo base_url().'/myaccount/facility/export/'.$data['id']; ?>" 
						class="dash-export-event fs-7 mx-2">
						Export <i class="fas fa-file-export i-white-icon"></i>
					</a>
				<?php } ?>
				<a href="<?php echo base_url().'/myaccount/facility/view/'.$data['id']; ?>" 
					class="dash-view-event fs-7 mx-2">
					View <i class="far fa-eye i-white-icon"></i>
				</a>
				    <?php if($usertype !='4'){ ?>
						<a href="<?php echo base_url().'/myaccount/facility/edit/'.$data['id']; ?>" 
							class="dash-edit-event fs-7 mx-2">
							Edit <i class="far fa-edit i-white-icon"></i>
						</a>
						
						<?php $occupied = getOccupied($data['id']); ?>
						<?php if(count($occupied)==0){ ?>
							<a data-id="<?php echo $data['id']; ?>" href="javascript:void(0);" class="dash-delete-event fs-7 mx-2 delete">
								Delete <i class="far fa-trash-alt i-white-icon"></i>
							</a>
						<?php } ?>
				    <?php } ?>

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
<script>
	var userid = '<?php echo $userid; ?>';

	$(document).on('click','.delete',function(){
		var action 	= 	'<?php echo base_url()."/myaccount/facility"; ?>';
		var data   = '\
		<input type="hidden" value="'+$(this).data('id')+'" name="id">\
		<input type="hidden" value="'+userid+'" name="userid">\
		<input type="hidden" value="0" name="status">\
		';
		sweetalert2(action,data);
	});	
</script>
<?php $this->endSection(); ?>
