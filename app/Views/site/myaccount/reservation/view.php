<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content'); ?>
<?php
	$bookingid          = isset($result['id']) ? $result['id'] : '';
	$firstname          = isset($result['firstname']) ? $result['firstname'] : '';
	$lastname           = isset($result['lastname']) ? $result['lastname'] : '';
	$mobile             = isset($result['mobile']) ? $result['mobile'] : '';
	$eventname          = isset($result['eventname']) ? $result['eventname'] : '';
	$stall              = isset($result['stall']) ? $result['stall'] : '';
	$checkin            = isset($result['check_in']) ? formatdate($result['check_in'], 1) : '';
	$checkout           = isset($result['check_out']) ? formatdate($result['check_out'], 1) : '';
	$createdat       	  = isset($result['created_at']) ? formatdate($result['created_at'], 2) : '';
	$barnstalls         = isset($result['barnstall']) ? $result['barnstall'] : '';
	$rvbarnstall       = isset($result['rvbarnstall']) ? $result['rvbarnstall'] : '';
	$feed               = isset($result['feed']) ? $result['feed'] : '';
	$shaving            = isset($result['shaving']) ? $result['shaving'] : '';
	$paymentmethod      = isset($result['paymentmethod_name']) ? $result['paymentmethod_name'] : '';

?>
<div class="row">
	<div class="col">
		<h2 class="fw-bold mb-4">View Reservation</h2>
	</div>
	<div class="col" align="right">
		<a href="<?php echo base_url().'/myaccount/bookings';?>" class="btn back-btn">Back</a>
	</div>
</div>
<section class="maxWidp eventPagePanel">
	<div class="container event__ticket mx-auto p-3 my-5">
		<p class="text-center h5 my-4 fw-bold">View Reservation</p>
		<div class="row mx-5 px-4">
			<div class="row base_stripe">
				<div class="col-md-6">
					<p class="ticket_title_tag">Booking ID</p>
					<p class="ticket_values"><?php echo $bookingid;?></p>
				</div>
				<div class="res_mt_3 col-md-5">
					<p class="ticket_title_tag">Name</p>
					<p class="ticket_values"><?php echo $firstname;?> <?php echo $lastname;?></p>
				</div>
			</div>
			<div class="row base_stripe">
				<div class="col-md-6">
					<p class="ticket_title_tag">Mobile</p>
					<p class="ticket_values"><?php echo $mobile;?></p>
				</div>
				<div class="res_mt_3 col-md-5">
					<p class="ticket_title_tag">Booked Event</p>
					<p class="ticket_values">Event (<?php echo $eventname;?>)</p>
				</div>
			</div>
			<div class="row base_stripe">
				<div class="col-md-6">
					<p class="ticket_title_tag">Barn & Stall Name</p>
					<?php 	
						$barnname = '';
						foreach ($barnstalls as $barnstall) {
							if($barnname!=$barnstall['barnname']){
								$barnname = $barnstall['barnname'];
								echo '<p class="mb-0 fw-bold h6">'.$barnstall['barnname'].'</p>';
							}
							echo '<p class="ticket_values">'.$barnstall['stallname'].'</p>';
						} ?>
				</div>
				<div class="res_mt_3 col-md-5">
					<p class="ticket_title_tag">Rv & Stall Name</p>
					<?php 
						$rvbarnname = '';
						foreach ($rvbarnstall as $rvbarnstall) {
							if($rvbarnname!=$rvbarnstall['barnname']){
								$rvbarnname = $rvbarnstall['barnname'];
								echo ' <p class="mb-0 fw-bold h6">'.$rvbarnstall['barnname'].'</p>';
							}
							echo ' <p class="ticket_values">'.$rvbarnstall['stallname'].'</p>';
					} ?>
				</div>
			</div>
			<div class="row base_stripe">
				<div class="col-md-6">
					<p class="ticket_title_tag">Feed Name</p>
					<?php foreach ($feed as $feed) {
						echo ' <p class="ticket_values">'.$feed['productname'].'</p>';
					} ?>
				</div>
				<div class="res_mt_3 col-md-5">
					<p class="ticket_title_tag">Shaving Name</p>
					<?php foreach ($shaving as $shaving) {
						echo ' <p class="ticket_values">'.$shaving['productname'].'</p>';
					} ?>
				</div>
			</div>
			<div class="row base_stripe">
				<div class="col-md-6">
					<p class="ticket_title_tag">Check In</p>
					<p class="ticket_values"><?php echo $checkin;?></p>
				</div>
				<div class="res_mt_3 col-md-5">
					<p class="ticket_title_tag">Check Out</p>
					<p class="ticket_values"><?php echo $checkout;?></p>
				</div>
			</div>
			<div class="row base_stripe">
				<div class="col-md-6">
					<p class="ticket_title_tag">Booked By</p>
					<p class="ticket_values"><?php echo $usertype[$result['usertype']];?></p>
				</div>
				<div class="res_mt_3 col-md-5">
					<p class="ticket_title_tag">Date of booking</p>
					<p class="ticket_values"><?php echo $createdat;?></p>
				</div>
			</div>
			<div class="row base_stripe">
				<div class="col-md-6">
					<p class="ticket_title_tag">Payment Method</p>
					<p class="ticket_values"><?php echo $paymentmethod;?></p>
				</div>
				<div class="res_mt_3 col-md-2">
					<?php $statuscolor = ($result['status']=='2') ? "cancelcolor" : "activecolor"; ?>
					<p class="my-2 ticket_values ticket_status <?php echo  $statuscolor;?>"><?php echo $bookingstatus[$result['status']];?></p>
				</div>
			</div>
		</div>
	</div>
</section>
<?php $this->endSection(); ?>