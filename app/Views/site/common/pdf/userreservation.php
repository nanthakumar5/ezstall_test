<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Event Ticket</title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ;?>/assets/site/css/bootstrap.min.css">
<body>
	<style type="text/css">
		
		@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap');
		
		*{
			font-family: 'Montserrat', sans-serif;
		}

		::-webkit-scrollbar{
			display: none;
		}
		
		.ticket_title_tag {
			font-size: 11px;
			margin-bottom: 0;
		}

		.ticket_values {
			font-weight: bold;
			font-size: 13px;
			margin-bottom: 0;
		}

		.ticket_status {
			background-color: #5ACC5F;
			color: #fff;
			text-transform: uppercase;
			width: 100%;
			padding: 10px;
			border-radius: 5px;
			text-align: center;
		}

		.ticket_row{
			height: fit-content;
			position: relative;
		}

		.base_stripe {
			padding: 15px;
			border-radius: 5px;
		}

		.base_stripe:nth-child(odd) {
			background-color: #F9F9F9;
		}

		@media(max-width: 767px){
			.res_mt_3{
				margin-top: 10px;
			}
		}

		.fw-bold{
			font-weight: bold;
		}
	</style>
	<?php 
	$bookingid          = isset($reservationpdf['id']) ? $reservationpdf['id'] : '';
	$firstname          = isset($reservationpdf['firstname']) ? $reservationpdf['firstname'] : '';
	$lastname           = isset($reservationpdf['lastname']) ? $reservationpdf['lastname'] : '';
	$mobile             = isset($reservationpdf['mobile']) ? $reservationpdf['mobile'] : '';
	$eventname          = isset($reservationpdf['eventname']) ? $reservationpdf['eventname'] : '';
	$checkin            = isset($reservationpdf['check_in']) ? formatdate($reservationpdf['check_in'], 1) : '';
	$checkout           = isset($reservationpdf['check_out']) ? formatdate($reservationpdf['check_out'], 1) : '';
	$createdat       	= isset($reservationpdf['created_at']) ? formatdate($reservationpdf['created_at'], 2) : '';
	$barnstalls         = isset($reservationpdf['barnstall']) ? $reservationpdf['barnstall'] : '';
	$rvbarnstalls       = isset($reservationpdf['rvbarnstall']) ? $reservationpdf['rvbarnstall'] : '';
	$feeds              = isset($reservationpdf['feed']) ? $reservationpdf['feed'] : '';
	$shavings           = isset($reservationpdf['shaving']) ? $reservationpdf['shaving'] : '';
	$paymentmethod      = isset($reservationpdf['paymentmethod_name']) ? $reservationpdf['paymentmethod_name'] : '';
	?>
	<div class="container event__ticket mx-auto p-3 my-5">
		<img src="<?php echo base_url().'/assets/uploads/settings/'.$settings['logo'] ?>" class="logo" alt="Logo">
		<p class="text-center h5 my-4 fw-bold">View Reservation</p>
		<div class="row mx-5 px-4">
			<div class="row base_stripe">
				<table class="table">
					<thead>
						<tr style="background-color: #F9F9F9">
							<td scope="col" class="ticket_title_tag" style="padding-top: 10px; padding-left: 40px;">Booking ID</td>
							<td scope="col" class="ticket_title_tag" style="padding-top: 10px;">Name</td>
						</tr>
					</thead>
					<tbody>
						<tr style="background-color: #F9F9F9;">
							<th class="ticket_values" style="padding-bottom: 10px; padding-left: 40px;"><?php echo $bookingid;?></th>
							<th class="ticket_values" style="padding-bottom: 10px; "><?php echo $firstname; ?> <?php echo $lastname;?></th>
						</tr>
						<tr><td style="padding-bottom: 20px;"></td></tr>
					</tbody>
					<thead>
						<tr>
							<td scope="col" class="ticket_title_tag" style="padding-left: 40px;">Mobile</td>
							<td scope="col" class="ticket_title_tag" style="">Booked Event</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th class="ticket_values" style="padding-left: 40px;"><?php echo $mobile;?></th>
							<th class="ticket_values" style="">Event ( <?php echo $eventname;?> )</th>
							<tr><td style="padding-bottom: 20px;"></td></tr>
						</tr>
					</tbody>
					<thead>
						<tr style="background-color: #F9F9F9">
							<td scope="col" class="ticket_title_tag" style="padding-top: 10px; padding-left: 40px;">Barn & Stall Name</td>
							<td scope="col" class="ticket_title_tag" style="padding-top: 10px; ">Rv & Stall Name</td>
						</tr>
					</thead>
					<tbody>
						<tr style="background-color: #F9F9F9;">
							<?php 	
							if(!empty($barnstalls)) {  
								foreach ($barnstalls as $barnstall) { 
									echo '<th style="padding-left: 40px;"><p class="mb-0 fw-bold h6">'.$barnstall["barnname"].'</p><p class="mb-0 fw-bold"style="font-size:12px;">'.$barnstall["stallname"].'</p></th>';
								} 
							} else{
									echo '<th style="padding-bottom: 10px; padding-left: 40px;"><p class="mb-0 fw-bold h6">No Data</p></th>';
							}
							?>
							<?php 
							if(!empty($rvbarnstalls)) {
								foreach ($rvbarnstalls as $rvbarnstall) {
									echo '<th style="padding-bottom: 10px;  font-size: 12px;"><p class="mb-0 fw-bold">'.$rvbarnstall['barnname'].'</p>
									<p class="mb-0 fw-bold">'.$rvbarnstall['stallname'].'</p>
									</th>';
								} 
							}
							else{
									echo '<th style="padding-bottom: 10px;  font-size: 12px;"><p class="mb-0 fw-bold h6">No Data</p></th>';
							} 
							?>
							<tr><td style="padding-bottom: 20px;"></td></tr>
						</tr>
					</tbody>
					<thead>
						<tr>
							<td scope="col" class="ticket_title_tag" style="padding-left: 40px;">Feed Name</td>
							<td scope="col" class="ticket_title_tag" style="">Shaving Name</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<?php 
							if(!empty($feeds)) {  
								foreach ($feeds as $feed) { 
									echo '<th class="ticket_values" style="padding-left: 40px;">'.$feed['productname'].'</th>';
								} 
							} else { 
									echo '<th class="ticket_values" style="padding-left: 40px;">No Data</th>';
							} 
							?>
							<?php 
							if(!empty($shavings)) { 
								foreach ($shavings as $shaving) {
									echo '<th class="ticket_values" style="">'.$shaving['productname'].'</th>';
								} 
							} else { 
								echo '<th class="ticket_values" style="">No Data</th>';
							} 
							?>
						</tr>
						<tr><td style="padding-bottom: 20px;"></td></tr>
					</tbody>
					<thead>
						<tr style="background-color: #F9F9F9">
							<td scope="col" class="ticket_title_tag" style="padding-top: 10px; padding-left: 40px;">Check In</td>
							<td scope="col" class="ticket_title_tag" style="padding-top: 10px; ">Check Out</td>
						</tr>
					</thead>
					<tbody>
						<tr style="background-color: #F9F9F9">
							<th class="ticket_values" style="padding-bottom: 10px; padding-left: 40px;"><?php echo $checkin;?></th>
							<th class="ticket_values" style="padding-bottom: 10px; "><?php echo $checkout;?></th>
						</tr>
						<tr><td style="padding-bottom: 20px;"></td></tr>
					</tbody>
					<thead>
						<tr>
							<td scope="col" class="ticket_title_tag" style="padding-left: 40px;">Booked By</td>
							<td scope="col" class="ticket_title_tag" style="">Date of booking</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th class="ticket_values" style="padding-left: 40px;"><?php echo $usertype[$reservationpdf['usertype']];?></th>
							<th class="ticket_values" style=""><?php echo $createdat;?></th>
						</tr>
						<tr><td style="padding-bottom: 20px;"></td></tr>
					</tbody>
					<thead>
						<tr style="background-color: #F9F9F9">
							<td scope="col" class="ticket_title_tag" style="width: 50%; padding-top: 10px; padding-left: 40px; ">Payment Method</td>
							<td scope="col" class="ticket_title_tag" style="padding-top: 10px; ">Status</td>
						</tr>
					</thead>
					<tbody>
						<tr style="background-color: #F9F9F9;">
							<th class="ticket_values" style="width: 50%; padding-bottom: 10px; padding-left: 40px;"><?php echo $paymentmethod;?></th>
							<th>Booked</th>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</body>
	</html>