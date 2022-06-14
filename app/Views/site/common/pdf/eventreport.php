<style>
table{
	width : 100%;
}
table, th, td {
	border: 1px solid black;
	border-collapse: collapse;
}

table tr th, 
table tr td {
	padding : 10px;
	text-align : left;
}

.sub_heading{
	margin-bottom : 15px;
	font-size : 22px;
}
</style>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Event Report</title>
	</head>
	<body>
	    <div> 
	        <?php
	        $eventname  = $result['name'];
	        $startdate 	= $result['start_date'];
         	$enddate 	= $result['end_date'];
         	?>
	        <h1><?php echo $eventname.' ('.date("m-d-y", strtotime($startdate)).' to '.date("m-d-y", strtotime($enddate)).')';?></h1>
         	<?php 
        	while (strtotime($startdate) <= strtotime($enddate)) {
	        	$arriving = getBooking(['eventid' => $result['id'],'check_in'=> $startdate]);
	        	$outgoing = getBooking(['eventid' => $result['id'],'check_out'=> $startdate]);
     		?>
		        <div>	        	
					<h4 class="sub_heading">Arriving (<?php echo date("m-d-y", strtotime($startdate));?>)</h4>
					<table>
					  <thead>
							<tr>
								<th width="40%">Horse Owner</th>
								<th width="30%">Stalls Reserved</th>
								<th width="30%">Contact Phone Number</th>
							</tr>
					  </thead>
					  <tbody>
							<?php if (!empty(array_filter($arriving))) { ?>
								<?php foreach ($arriving as $data) { ?>
									<tr>
										<td><?php echo $data['firstname'].' '.$data['lastname'];?></td>
										<td><?php echo implode(', ', array_column($data['barnstall'], 'stallname')); ?></td>
										<td><?php echo $data['mobile'];?></td>		
									</tr>
								<?php } ?>
							<?php }else{ ?>
								<tr><td colspan="3">No Arriving on <?php echo date("m-d-y", strtotime($startdate));?></td></tr>
							<?php } ?>
					  </tbody>
					</table>
					
					<h4 class="sub_heading">Outgoing (<?php echo date("m-d-y", strtotime($startdate));?>)</h4>
					<table>
						<thead>
							<tr>
								<th width="40%">Horse Owner</th>
								<th width="30%">Stalls Reserved</th>
								<th width="30%">Contact Phone Number</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty(array_filter($outgoing))) { ?>
								<?php foreach ($outgoing as $data) { ?>
									<tr>
										<td><?php echo $data['firstname'].' '.$data['lastname'];?></td>
										<td><?php echo implode(', ', array_column($data['barnstall'], 'stallname')); ?></td>
										<td><?php echo $data['mobile'];?></td>		
									</tr>
								<?php } ?>
							<?php }else{ ?>
								<tr><td colspan="3">No Outgoing on <?php echo date("m-d-y", strtotime($startdate));?></td></tr>
							<?php } ?>
						</tbody>
					</table>
	    		</div>
    			<?php  $startdate = date("Y-m-d", strtotime("+1 day", strtotime($startdate))); ?>
    		<?php } ?>
	    </div>
	</body>
</html>