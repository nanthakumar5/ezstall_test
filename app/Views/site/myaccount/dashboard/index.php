<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<div class="welcome-content mb-5">
	<h3 class="fw-bold d-flex flex-wrap">Welcome to EZ Stall, <p class="welcome-user"><?php echo $userdetail['name']; ?></p></h3>
	<p class="c-5">
		<?php echo "Thank you for being an EZ Stall"." ".$usertype[$userdetail['type']]?>
	</p> 
	<?php if($userdetail['type']=='2' || $userdetail['type']=='3' || $userdetail['type']=='4' ) { ?>
		<div class="col-md-12 mt-4 p-4 bg-white rounded-sm">
			<h5 class="font-w-600">Current Stall Reservations</h5>
			<div class="row mt-4 first">
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/stall.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $countcurrentstall;?></h2>
								<p>Total no of. Stalls</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img
								src="<?php echo base_url()?>/assets/site/img/currently_available.png"
								class="rounded d-block"
								/>
							</div>
							<div class="col-md-9"> 
								<h2><?php echo $countcurrentavailablestalls;?></h2>
								<p>Currently Available</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img
								src="<?php echo base_url()?>/assets/site/img/currently_booked.png"
								class="rounded d-block"
								/>
							</div>
							<div class="col-md-9">
								<h2><?php echo $countcurrentbookingstalls;?></h2>
								<p>Currently booked</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12 mt-4 p-4 bg-white rounded-sm">
			<h5 class="font-w-600">Current RV Reservations</h5>
			<div class="row mt-4 first">
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/stall.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $countcurrentrvlots;?></h2>
								<p>Total no of. Rv Lots</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img
								src="<?php echo base_url()?>/assets/site/img/currently_available.png"
								class="rounded d-block"
								/>
							</div>
							<div class="col-md-9"> 
								<h2><?php echo $countcurrentavailablervlots;?></h2>
								<p>Currently Available</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img
								src="<?php echo base_url()?>/assets/site/img/currently_booked.png"
								class="rounded d-block"
								/>
							</div>
							<div class="col-md-9">
								<h2><?php echo $countcurrentbookingrvlots;?></h2>
								<p>Currently booked</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 p-4 mt-5 bg-white rounded-sm">
			<h5 class="font-w-600">Past Month Activity</h5>
			<div class="row mt-4 second">
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/rented_stalls.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $countpaststall ?></h2>
								<p>Rented Stalls</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/total_revenue.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2>$<?php echo $countpastamount ?></h2>
								<p>Total Revenue</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/total_events.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $pastevent;?></h2>
								<p>Total Events</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if($userdetail['type']=='5') { ?>
		<div class="col-md-12 mt-4 p-4 bg-white rounded-sm">
			<h5 class="font-w-600">Current Reservation</h5>
			<div class="row mt-4 first">
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/stall.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $countcurrentstall;?></h2>
								<p>Total no of. Stalls</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/stall.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $countcurrentrvlots;?></h2>
								<p>Total no of. Rv Lots</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img
								src="<?php echo base_url()?>/assets/site/img/currently_available.png"
								class="rounded d-block"
								/>
							</div>
							<div class="col-md-9"> 
								<h2><?php echo $countpayedamount;?></h2>
								<p>Total Paid</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img
								src="<?php echo base_url()?>/assets/site/img/currently_booked.png"
								class="rounded d-block"
								/>
							</div>
							<div class="col-md-9">
								<h2><?php echo $countcurrentevent;?></h2>
								<p>Total Events</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 p-4 mt-5 bg-white rounded-sm">
			<h5 class="font-w-600">Past Month Activity</h5>
			<div class="row mt-4 second">
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/rented_stalls.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $countpaststall ?></h2>
								<p>Total no of. Stalls</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/total_revenue.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2>$<?php echo $countpastamount ?></h2>
								<p>Total Payed</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/total_events.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $pastevent;?></h2>
								<p>Total Events</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if($userdetail['type']=='2' || $userdetail['type']=='3' || $userdetail['type']=='4'){ ?>
	<div class="row tablesec mt-5 mb-5">
		<div class="col-md-6">
			<h5 class="font-w-600">Monthly Accrued Income</h5>
			<div class="table-responsive mt-3">
				<table class="table m-0" id="monthlyincome">
					<thead>
						<tr class="welcome-table table-active">
							<th scope="col">#</th>
							<th scope="col">Month</th>
							<th scope="col">Amount</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						foreach($monthlyincome as $key => $income){?>						
							<tr class="monthlyincome">
								<td><?php echo $key+1; ?></td>
								<td><?php echo $income['month']?></td>
								<td><?php echo $income['paymentamount'] ?></td>
								<td>
									<button class="View">
										<a href="<?php echo base_url();?>/myaccount/payments" >View</a>
									</button><br>
								</td>
							</tr>
						<?php } ?>
						
						<tr>
							<td colspan="4" class="text-center">
								<a href="<?php echo base_url();?>/myaccount/payments" id="loadincome" class="dash-view">VIEW ALL</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-md-6">
			<h5 class="font-w-600">Upcoming events</h5>
			<div class="table-responsive mt-3">
				<table class="table m-0" id="upcoming">
					<thead>
						<tr class="welcome-table table-active">
							<th scope="col">Date</th>
							<th scope="col">Event Name</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($upcomingevents as $value){ 
						$url = ($value['type']=='2') ? 'facility' : 'events'; ?>
							<tr class="upcoming"> 
								<td><?php echo  date('m-d-Y',strtotime($value['start_date'])); ?></td>
								<td><?php echo $value['name']; ?></td>
								<td>
									<button class="View">
										<a href="<?php echo base_url().'/myaccount/'.$url.'/view/'.$value['id']; ?>">View</a>
									</button>
								</td>
							</tr>
						<?php } ?>
						<tr>
							<td colspan="3" class="text-center">
								<a href="<?php echo base_url().'/myaccount/events'; ?>" id="loadMore" class="dash-view">VIEW ALL</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		</div>
		<?php } ?>
	</div>
	<div>
	<?php if($userdetail['type']=='6' || $userdetail['type']=='4'){
		echo '<h4><b>Today Checkin Event</b></h4>';
		 if(!empty($checkinstall)){
			foreach($checkinstall as $availablestall){   
				$eventname =  $availablestall['eventname'];
				foreach($availablestall['barnstall'] as $stall){ 
						echo '
								<div class="d-flex col-md-12 justify-content-between my-2 dash_border_ operator-list ">
									<div>
										<p class="mb-0 fw-bold">'.$availablestall['eventname'].'-'.$availablestall['username'].'</p>
										<p class="mb-0">'.dateformat($availablestall['check_in']).'-'.$stall['stallname'].'</p>
									</div>
								</div>
							';
					
				}
				foreach($availablestall['rvbarnstall'] as $rvbarnstall){ 
					echo '
							<div class="d-flex col-md-12 justify-content-between my-2 dash_border_ operator-list ">
								<div>
									<p class="mb-0 fw-bold">'.$availablestall['eventname'].'-'.$availablestall['username'].'</p>
									<p class="mb-0">'.dateformat($availablestall['check_in']).'-'.$rvbarnstall['stallname'].'</p>
								</div>
							</div>
						';
					
				}
			}
		}else{ echo "<p>No Stalls Checkin Today</p>";}
		echo "<br>";
		echo '<h4><b>Today Checkout Event</b></h4>';		 
		if(!empty($checkoutstall)){
			echo '<button class="btn_dash_lock delete_lockunlockbtn">Unlocked</button>';
			echo ' ';
			echo '<button class="btn_dash_lock delete_dirtyclean">Clean</button>';
			foreach($checkoutstall as $availablestall){   
				$eventname =  $availablestall['eventname'];
				foreach($availablestall['barnstall'] as $stall){ 

					$btnlockunlock ='<div class="bookselectbtn"><button class="btn_dash_lock lockunlock"  data-stallid="'.$stall['stall_id'].'">Locked</button></div>';

					$btndirtyclean ='<div class="bookselectbtn"><button class="btn_dash_dirty dirtyclean" data-stallid="'.$stall['stall_id'].'">Dirty</button></div>';

					if($stall['lockunlock']=='1'){
						$btnlockunlock = '<div class="bookselectbtn"><button class="btn btn-success">Unlocked</button></div>';
					}
					if($stall['dirtyclean']=='1'){
						$btndirtyclean = '<div class="bookselectbtn"><button class="btn btn-success">Cleaned</button></div>'; 
					}
						echo '
							<div class="d-flex col-md-12 justify-content-between my-2 dash_border_ operator-list ">
    							<div class="bookselect "><div class="form-check">
	                                <input class="form-check-input" type="checkbox" name="removestallid" value="'.$stall['stall_id'].'"></div>
	                                <div class="bookdetails">
										<p class="mb-0 fw-bold">'.$availablestall['eventname'].'-'.$availablestall['username'].'</p>
										<p class="mb-0">'.dateformat($availablestall['check_in']).'-'.$stall['stallname'].'</p>
									</div>
								</div>
								<div>'.$btnlockunlock.'
									'.$btndirtyclean.'
								</div>	
							</div>';
					
				}

				foreach($availablestall['rvbarnstall'] as $rvbarnstall){ 
					$btnlockunlocks ='<div class="bookselectbtn"><button class="btn_dash_lock lockunlock"  data-stallid="'.$rvbarnstall['stall_id'].'">Locked</button></div>';
					$btndirtycleans ='<div class="bookselectbtn"><button class="btn_dash_dirty dirtyclean" data-stallid="'.$rvbarnstall['stall_id'].'">Dirty</button></div>';

					if($rvbarnstall['lockunlock']=='1'){
						$btnlockunlocks = '<div class="bookselectbtn"><button class="btn btn-success">Unlocked</button></div>';
					}
					if($rvbarnstall['dirtyclean']=='1'){
						$btndirtycleans = '<div class="bookselectbtn"><button class="btn btn-success">Cleaned</button></div>'; 
					}
						echo '
							<div class="d-flex col-md-12 justify-content-between my-2 dash_border_ operator-list ">
    							<div class="bookselect "><div class="form-check">
									 <input class="form-check-input" type="checkbox" name="removestallid" value="'.$rvbarnstall['stall_id'].'"></div>
		                                <div class="bookdetails">
											<p class="mb-0 fw-bold">'.$availablestall['eventname'].'-'.$availablestall['username'].'</p>
											<p class="mb-0">'.dateformat($availablestall['check_in']).'-'.$rvbarnstall['stallname'].'</p>
										</div>
								</div>
								<div>'.$btnlockunlocks.'
									'.$btndirtycleans.'
								</div>	
							</div>';
					
				}
			}
		}else{ echo "<p>No Stalls Checkout Today</p>";}
		}?>
	
</div>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
<script>
	$(".bookselect input.form-check-input:checkbox").on('click', function(){
        $(this).parent().parent().parent().toggleClass("checked");
   });
	$(document).on('click','.lockunlock',function(){
		var action 	= 	'<?php echo base_url()."/myaccount/updatedata"; ?>';
		var data   = '\
		<input type="hidden" value="'+$(this).attr('data-stallid')+'" name="stallid">\
		<input type="hidden" value="1" name="lockunlock">\
		';
		sweetalert2(action,data);
	});	

	$(document).on('click','.dirtyclean',function(){
		var action 	= 	'<?php echo base_url()."/myaccount/updatedata"; ?>';
		var data   = '\
		<input type="hidden" value="'+$(this).attr('data-stallid')+'" name="stallid">\
		<input type="hidden" value="1" name="dirtyclean">\
		';
		sweetalert2(action,data);
	});	

	$(document).on('click','.delete_lockunlockbtn',function(){
	 	var stallid = [];
	    $.each($("input[name='removestallid']:checked"), function(){
	        stallid.push($(this).val());
	    });
		var action 	= 	'<?php echo base_url()."/myaccount/updatedata"; ?>';
		var data   = '\
		<input type="hidden" value="'+stallid+'" name="stallid">\
		<input type="hidden" value="1" name="lockunlock">\
		';
		sweetalert2(action,data);
	});	

	$(document).on('click','.delete_dirtyclean',function(){
	 	var stallid = [];
	    $.each($("input[name='removestallid']:checked"), function(){
	        stallid.push($(this).val());
	    });
		var action 	= 	'<?php echo base_url()."/myaccount/updatedata"; ?>';
		var data   = '\
		<input type="hidden" value="'+stallid+'" name="stallid">\
		<input type="hidden" value="1" name="dirtyclean">\
		';
		sweetalert2(action,data);
	});
</script>
<?php $this->endSection();?>