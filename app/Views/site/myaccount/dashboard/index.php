<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<div class="welcome-content mb-5">
	<h3 class="fw-bold d-flex flex-wrap">Welcome to EZStall, <p class="welcome-user"><?php echo $userdetail['name']; ?></p></h3>
	
	<p class="c-5">
		<?php echo "Thank you for being an EZ stall"." ".$usertype[$userdetail['type']]?>
	</p>
	<?php if($userdetail['type']=='2' || $userdetail['type']=='3' || $userdetail['type']=='4' ) { ?>
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
								<img
								src="<?php echo base_url()?>/assets/site/img/currently_available.png"
								class="rounded d-block"
								/>
							</div>
							<div class="col-md-9"> 
								<h2><?php echo $countcurrentavailable;?></h2>
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
								<h2><?php echo $countcurrentbooking;?></h2>
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
								<img
								src="<?php echo base_url()?>/assets/site/img/currently_available.png"
								class="rounded d-block"
								/>
							</div>
							<div class="col-md-9"> 
								<h2><?php echo $countpayedamount;?></h2>
								<p>Total Payed</p>
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
							<?php foreach ($upcomingevents as $value){ ?>
								<tr class="upcoming"> 
									<td><?php echo  date('m-d-Y',strtotime($value['start_date'])); ?></td>
									<td><?php echo $value['name']; ?></td>
									<td>
										<button class="View">
											<a href="<?php echo base_url().'/myaccount/events/view/'.$value['id']; ?>">View</a>
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
		<?php } ?>
	</div>
</div>
<?php $this->endSection(); ?>