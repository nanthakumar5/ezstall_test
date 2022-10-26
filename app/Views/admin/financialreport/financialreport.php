<?= $this->extend("admin/common/layout/layout2") ?>

<?php $this->section('content') ?>
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Financial Report</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
						<li class="breadcrumb-item active">Financial Report</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	
	<section class="content">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Financial Report</h3>
			</div>
			<div class="card-body">
				<form method="post" id="form">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>From Date</label>
									<input type="text" name="financialcheckin" autocomplete="off" class="form-control"  id="financialcheckin">	
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>To Date</label>
									<input type="text" name="financialcheckout" autocomplete="off" class="form-control"  id="financialcheckout">		
								</div>
							</div>
							<div class="col-md-12 exportup" align="right">
								<input type="submit" value="search" class="btn btn-danger">
							</div>
						</div>
					</div>
				</form>
			</div>
			<?php 
			if(!empty($finacialamount)){?>
				<div class="container table-responsive py-5"> 
					<table class="table table-bordered table-hover">
						<thead class="thead-dark"> 
							<tr>
								<th scope="col">Event Name</th>
								<!-- <th scope="col">Amount</th> -->
								<!-- <th scope="col">Total Stalls</th> -->
								<th scope="col">Total Stalls Rented & Revenue</th>
								<th scope="col">Total Available Stalls</th>
								<th scope="col">Total COD Stall</th>
								<th scope="col">Total Stall Paid & Unpaid</th>
								<th scope="col">Total Stall Stripe</th>

								<!-- <th scope="col">Total RV Lots</th> -->
								<th scope="col">Total Lots Rented & Revenue</th>
								<th scope="col">Total Available Lots</th>
								<th scope="col">Total COD Lots </th>
								<th scope="col">Total Lots Paid & Unpaid</th>
								<th scope="col">Total Lots Stripe</th>

								<th scope="col">Total Feed Revenue</th>
								<th scope="col">Remaining Feed Inventory</th>
								<th scope="col">Total shavings Revenue</th>
								<th scope="col">Remaining shavings Inventory</th>
							</tr>
						</thead>
					<?php
					foreach ($finacialamount as $event) { 
						$testbkslstrcountcs = [];
						$testbkslstrcount = [];
						$totalstallcount = [];
						$bookingstallcount = [];
						$availablestallcount = [];
						$testbkslstrcountcsrv = [];
						$testbkslstrcountrv = [];
						$totalrvstallcount = [];
						$bookingrvstallcount = [];
						$availablervstallcount = [];
						$bookingstallprice = [];
						$bookingrvstallprice = [];
						$paid = [];
						$unpaid = [];
						$rvpaid = [];
						$rvunpaid = [];
						$productfeedtotal =[];
						$productshavingstotal = [];
						$remainingfeed = []; 
						$remainingshavings = [];

						foreach ($event['barn'] as $barn) { 
							foreach ($barn['stall'] as $stall) {
								$totalstallcount[] = $stall['id'];
								if(!empty($stall['bookedstall'])){ 
									$bookingstallcount[] = $stall['id'];
									$bookingstallprice[] = (count($stall['bookedstall']) * $stall['price']);
								}else if(empty($stall['bookedstall'])){
									$availablestallcount[] = $stall['id'];
								}
								foreach ($stall['bookedstall'] as $bksl) {
									if($bksl['paymentid']==1){
										$testbkslstrcountcs[] = $bksl['paymentid'];
									}else if($bksl['paymentid']==2){
										$testbkslstrcount[]=$bksl['paymentid'];
									}
									if($bksl['paymentmethod']=='Cash on Delivery' && $bksl['paidunpaid']=='1'){
										$paid[] = $bksl['paymentid'];
									}else if($bksl['paidunpaid']==0 || $bksl['paymentmethod']=='Cash on Delivery'){
										$unpaid[]= $bksl['paymentid'];
									}
								}
							}
						}

						foreach ($event['rvbarn'] as $rvbarn) { 
							foreach ($rvbarn['rvstall'] as $rvstall) { 
								$totalrvstallcount[] = $rvstall['id'];
								if(!empty($rvstall['rvbookedstall'])){
									$bookingrvstallcount[] = $rvstall['id'];
									$bookingrvstallprice[] = (count($rvstall['rvbookedstall']) * $rvstall['price']);
								}else{
									$availablestallcount[] = $rvstall['id'];
								}
								foreach ($rvstall['rvbookedstall'] as $rvbksl) {
									if($rvbksl['paymentid']==1){
										$testbkslstrcountcsrv[] = $rvbksl['paymentid'];
									}else if($bksl['paymentid']==2){
										$testbkslstrcountrv[]=$rvbksl['paymentid'];
									}
									if($rvbksl['paymentmethod']=='Cash on Delivery' && $rvbksl['paidunpaid']=='1'){
										$rvpaid[] = $rvbksl['paymentid'];
									}else if($rvbksl['paidunpaid']==0 || $rvbksl['paymentmethod']=='Cash on Delivery'){
										$rvunpaid[]= $rvbksl['paymentid'];
									}
								}
							}
						}

						foreach($event['feed'] as $feed){
							$productfeedtotal[] = $feed['total']; 
							//$remainingfeed[] = $feed['totalquantity']-$feed['productquantity'];
							$remainingfeed[] = $feed['totalquantity'];
						}

						foreach($event['shaving'] as $shaving){
							$productshavingstotal[] = $shaving['total'];
							//$remainingshavings[] = $shaving['totalquantity']-$shaving['productquantity'];
							$remainingshavings[] = $shaving['totalquantity'];
						}
					?>
						<tbody>
							<tr>
								<td><?php echo $event['eventname']?></td>
								<!-- <td><?php //echo $event['bookingamount']?></td>
								
								<td><?php //echo count($totalstallcount)?></td> -->
								<td><?php echo count($bookingstallcount).'&'.array_sum($bookingstallprice)?></td>
								<td><?php echo (count($totalstallcount)-count($bookingstallcount))?></td>
								<td><?php echo count($testbkslstrcountcs)?></td>
								<td><?php echo count($paid).'&'.count($unpaid)?></td>
								<td><?php echo count($testbkslstrcount)?></td>

								<!-- <td><?php //echo count($totalrvstallcount)?></td> -->
								<td><?php echo count($bookingrvstallcount).'&'.array_sum($bookingrvstallprice)?></td>
								<td><?php echo count($totalrvstallcount)-count($bookingrvstallcount)?></td>
								<td><?php echo count($testbkslstrcountcsrv)?></td>
								<td><?php echo count($rvpaid).'&'.count($rvunpaid)?></td>
								<td><?php echo count($testbkslstrcountrv)?></td> 

								<td><?php echo array_sum($productfeedtotal)?></td> 
								<td><?php echo array_sum($remainingfeed)?></td> 
								<td><?php echo array_sum($productshavingstotal)?></td>
								<td><?php echo array_sum($remainingshavings)?></td>   
							</tr>
						</tbody>
					<?php } ?>
					</table>
				</div>
			<?php } ?>
		</div>
	</section>
<?php $this->endSection(); ?>
<?php $this->Section('js'); ?>
<script>
$(function(){
    validation(
    	'#form',
    	{
    		financialcheckin 	     : {
    			required	: 	true
    		},
    		financialcheckout  : {	
    			required	: 	true
    		}
    	},
    	{},
    	{
    		ignore : []
    	}
    );
});
	dateformat('#financialcheckin, #financialcheckout');
</script>

<?php $this->endSection(); ?>