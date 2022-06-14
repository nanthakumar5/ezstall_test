<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

<?php
	$userdetail 		= getSiteUserDetails();
	$role 					= $userdetail['type']; 
	$parentid 			= $userdetail['parent_id'];
	$parentdetails 	= getSiteUserDetails($parentid);
	$parenttype   	= $parentdetails ? $parentdetails['type'] : '';
?>

<div class="navbar-header">
  <button type="button" id="sidebarCollapse" class="btn btn-info side-navbar-btn"><i class="side-nav-i bi bi-list"></i></button>
</div>
<nav id="sidebar-nav" class="ml-5">
	<ul class="list-unstyled components">
		<li class="side-nav-active">
			<a class="side-nav-a" href="<?php echo base_url();?>/myaccount/dashboard">
				<i class="side-nav-i bi bi-speedometer"></i>
				<p>Dashboard</p>
			</a>
		</li>
		<li>
			<a class="side-nav-a" href="<?php echo base_url();?>/myaccount/account">
				<i class="side-nav-i bi bi-person"></i>
				<p>Account Information</p>
			</a>
		</li>
		<?php if($role=='3' || ($role=='4' && $parenttype == '3')){ ?>
			<li>
				<a class="side-nav-a" href="<?php echo base_url();?>/myaccount/events">
					<i class="side-nav-i bi bi-calendar2-event"></i>
					<p>Event</p>
				</a>
			</li>
		<?php } ?>
		<?php if($role=='2' || ($role=='4' && $parenttype == '2')){ ?>
			<li>
				<a class="side-nav-a" href="<?php echo base_url();?>/myaccount/facility">
					<i class="side-nav-i bi bi-calendar2-event"></i>
					<p>Facility</p>
				</a>
			</li>
		<?php } ?>

		<?php if($role=='2' || $role=='3'){ ?>
			<li>
				<a class="side-nav-a" href="<?php echo base_url();?>/myaccount/stallmanager">
					<i class="side-nav-i bi bi-person"></i>
					<p>Stall Manager</p>
				</a>
			</li>
		<?php } ?>
		<li>
			<a class="side-nav-a" href="<?php echo base_url();?>/myaccount/bookings">
				<i class="side-nav-i bi bi-calendar2-week"></i>
				<p>Current Reservation</p>
			</a>
		</li>
		<li>
			<a class="side-nav-a" href="<?php echo base_url();?>/myaccount/pastactivity">
				<i class="side-nav-i bi bi-calendar3"></i>
				<p>Past Reservation</p>
			</a>
		</li>
		<li>
			<a class="side-nav-a" href="<?php echo base_url();?>/myaccount/payments">
				<i class="side-nav-i bi bi-credit-card"></i>
				<p>Payments</p>
			</a>
		</li>
		<?php if($role=='2' || $role=='5'){ ?>
			<li>
				<a class="side-nav-a" href="<?php echo base_url();?>/myaccount/subscription">
					<i class="side-nav-i bi bi-box"></i>
					<p>Packages</p>
				</a>
			</li>
		<?php } ?>
		<li>
			<a class="side-nav-a" href="<?php echo base_url();?>/logout">
				<i class="side-nav-i bi bi-power"></i>
				<p>Logout</p>
			</a>
		</li>
	</ul>
</nav>
