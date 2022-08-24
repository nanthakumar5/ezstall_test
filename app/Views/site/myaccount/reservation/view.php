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
	$createdat       	= isset($result['created_at']) ? formatdate($result['created_at'], 2) : '';
	$barnstalls         = isset($result['barnstall']) ? $result['barnstall'] : '';
	$rvbarnstall       	= isset($result['rvbarnstall']) ? $result['rvbarnstall'] : '';
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
<section class="container-lg">
	<div class="row">
		<div class="col-12">
			<div class="border rounded pt-3 ps-3 pe-3">
				<div class="row">
					<h2 class="fw-bold">Cart Summary</h2>
						<?php if(!empty($barnstalls)){ ?>
							<table class="table-hover table-striped table-light table">
								<h5 class="fw-bold text-muted">Barn&Stall</h5>
								<?php 	
									$barnname = '';
									foreach ($barnstalls as $barnstall) {
										if($barnname!=$barnstall['barnname']){
										$barnname = $barnstall['barnname'];?>
									<thead>
											<tr>
											<th><?php echo $barnname;?></th>
											<th>Total</th>
										</tr>
									</thead>
								<?php }?>
									<tbody>
										<tr>
											<td><?php echo $barnstall['stallname']?></td>
											<td><?php echo '('.$currencysymbol.$barnstall['price'].'x'.$barnstall['quantity'].')'.$currencysymbol.$barnstall['total']?></td>
										</tr>
									</tbody>
								<?php } ?>
							</table>
						<?php } ?>

						<?php if(!empty($rvbarnstall)){ ?>
							<table class="table-hover table-striped table-light table">
								<h5 class="fw-bold text-muted">Campsites</h5>
								<?php 	
									$rvbarnstalls = '';
									foreach ($rvbarnstall as $rvbarnstall) {
										$rvstallname = $rvbarnstall['stallname'];
										$rvprice     = $rvbarnstall['price'];
										$rvtotal     = $rvbarnstall['total'];
										$rvquantity     = $rvbarnstall['quantity'];
										if($rvbarnstall!=$rvbarnstall['barnname']){
										$rvbarnstalls = $rvbarnstall['barnname'];?>
									<thead>
											<tr>
											<th><?php echo $rvbarnstalls;?></th>
											<th>Total</th>
										</tr>
									</thead>
								<?php }?>
									<tbody>
										<tr>
											<td><?php echo $rvstallname;?></td>
											<td><?php echo '('.$currencysymbol.$rvprice.'x'.$rvquantity.')'.$currencysymbol.$rvtotal ?></td>
										</tr>
									</tbody>
								<?php } ?>
							</table>
						<?php } ?>

						<?php if(!empty($feed)){?>
							<table class="table-hover table-striped table-light table">
								<h5 class="fw-bold text-muted">Feed</h5>
								<thead>
									<tr>
										<th>Feed Name</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>

								<?php foreach ($feed as $feed) {?>
									<tr>
										<td><?php echo $feed['productname'];?></td>
										<td><?php echo $currencysymbol.$feed['total'];?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						<?php } ?>

						<?php if(!empty($shaving)){ ?>
							<table class="table-hover table-striped table-light table">
								<h5 class="fw-bold text-muted">Shavings</h5>
								<thead>
									<tr>
										<th>Shavings Name</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
								<?php foreach ($shaving as $shaving) {?>
									<tr>
										<td><?php echo $shaving['productname']?></td>
										<td><?php echo $currencysymbol.$shaving['total']?></td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						<?php } ?>

						<p><b>Total</b></p> <p align="right"><?php echo $currencysymbol.$result['price'];?></p>
						<p><b>Transaction Fees</b></p><p align="right"><?php echo $currencysymbol.$result['transaction_fee'];?></p>
						<?php 
						if($result['cleaning_fee']!=""){?>
						<p><b>Cleaning Fee</b></p><p align="right"><?php echo $currencysymbol.$result['cleaning_fee'];?>
						<?php } ?>
						<p><b>Amount</b></p><p align="right"><?php echo $currencysymbol.$result['amount'];?></p>
				</div>
			</div>
		</div>
	</div>
</section>
<?php $this->endSection(); ?>