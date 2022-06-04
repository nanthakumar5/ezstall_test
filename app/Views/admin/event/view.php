<?= $this->extend("admin/common/layout/layout2") ?>

<?php $this->section('content') ?>
	<?php
		$id 					= isset($result['id']) ? $result['id'] : '';
		$userid 				= isset($result['user_id']) ? $result['user_id'] : '';
		$name 					= isset($result['name']) ? $result['name'] : '';
		$description 		    = isset($result['description']) ? $result['description'] : '';
		$location 				= isset($result['location']) ? $result['location'] : '';
		$mobile 				= isset($result['mobile']) ? $result['mobile'] : '';
		$start_date 		    = isset($result['start_date']) ? dateformat($result['start_date']) : '';
		$end_date 				= isset($result['end_date']) ? dateformat($result['end_date']) : '';
		$start_time 			= isset($result['start_time']) ? formattime($result['start_time']) : '';
		$end_time 			    = isset($result['end_time']) ? formattime($result['end_time']) : '';
		$stalls_price 			= isset($result['stalls_price']) ? $result['stalls_price'] : '';
		$rvspots_price 			= isset($result['rvspots_price']) ? $result['rvspots_price'] : '';
		$image      			= isset($result['image']) ? $result['image'] : '';
		$image 				    = filedata($image, base_url().'/assets/uploads/event/');
		$status 				= isset($result['status']) ? $result['status'] : '';
		$eventflyer      		= isset($result['eventflyer']) ? $result['eventflyer'] : '';
		$eventflyer 			= filedata($eventflyer, base_url().'/assets/uploads/eventflyer/');
		$stallmap      			= isset($result['stallmap']) ? $result['stallmap'] : '';
		$stallmap 				= filedata($stallmap, base_url().'/assets/uploads/stallmap/');
		$barn        			= isset($result['barn']) ? $result['barn'] : [];
	?>
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>View Event</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right"> 
						<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
						<li class="breadcrumb-item"><a href="<?php echo getAdminUrl(); ?>/event">Events</a></li>
						<li class="breadcrumb-item active">View Event</li>
					</ol>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="page-action">
			<a href="<?php echo getAdminUrl(); ?>/event" class="btn btn-primary">Back</a>
		</div>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title"> Event Details </h3>
			</div>
			<div class="card-body">
			    <h3 class="event_heading"> <?php echo $name;?> </h3>
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<img class='img-fluid w-100' src="<?php echo $image[1];?>" alt="Event Image" />
						</div>
					</div>
				    <div class="row ">
						<div class="col-6">
							<div class="barn-text borderright">
								<div class="col">
									<strong> <i class='far fa-calendar'></i> Start Date</strong> 
									<p class="card-text"><?php echo $start_date; ?></p> 
								</div>
								<div class="col">
									<strong> <i class='far fa-calendar'></i> End Date </strong> 
									<p class="card-text"><?php echo $end_date; ?></p>
								</div> 
							</div>
						</div>
						<div class="col-6">
							<div class="barn-text">
								<div class="col"> 
									<strong> <i class='far fa-clock'></i> Start Time: </strong> 
									<p class="card-text">after <?php echo $start_time; ?></p>    
								</div>
								<div class="col">
									<strong> <i class='far fa-clock'></i> End Time :  </strong> 
									<p class="card-text">by <?php echo $end_time; ?></p>
								</div>
							</div>
						</div>
					</div>
					<h3 class="text-bold mt-3"> Barn and stalls </h3>
					<?php 
					$tabbtn = '';
					$tabcontent = '';
					foreach ($barn as $barnkey => $barndata) {
						$barnid = $barndata['id'];
						$barnname = $barndata['name'];
						$barnactive = $barnkey=='0' ? ' show active' : '';
						$tabbtn .= '<button class="nav-link'.$barnactive.'" data-bs-toggle="tab" data-bs-target="#barn'.$barnid.'" type="button" role="tab" aria-controls="barn'.$barnid.'" aria-selected="true">'.$barnname.'</button>';
					
						$tabcontent .= '<div class="tab-pane fade'.$barnactive.'" id="barn'.$barnid.'" role="tabpanel" aria-labelledby="nav-home-tab">
											<ul class="list-group">';

						foreach($barndata['stall'] as $stalldata){
							$bookedstalldata = [];
							if (!empty($stalldata['bookedstall'])) {
								foreach($stalldata['bookedstall'] as $bookedstall){
									$bookedstalldata[] = 	'<div class="col-custom-3 p-2 border rounded ad-stall-base">
																	<table>
																		<tr>
																			<td><p class="mb-0 text-bold px-2">Name</p></td>
																			<td>'.$bookedstall['name'].'</td>
																		</tr>
																		<tr>
																			<td><p class="mb-0 text-bold px-2">Date</p></td>
																			<td>'.formatdate($bookedstall['check_in'], 1).' to '.formatdate($bookedstall['check_out'], 1).'</td>
																		</tr>
																		<tr>
																			<td><p class="mb-0 text-bold px-2">Payment Method</p></td>
																			<td>'.$bookedstall['paymentmethod'].'</td>
																		</tr>
																	</table>
																</div>
															';
								}
							}
							
							$tabcontent .= 	'<li class="list-group-item px-4 py-3">
												<p class="px-2 text-bold">
												'.$stalldata['name'].'<div class="row px-3">'.implode('', $bookedstalldata).'</div>
												</p>
											</li>';
						}
						
						$tabcontent .= '</ul></div>';
					}
				?>
				<div class="barn-nav mt-4">
					<nav>
						<div class="nav nav-tabs" id="nav-tab" role="tablist">
							<?php echo $tabbtn; ?>
						</div>
					</nav>
					<div class="tab-content" id="nav-tabContent">
						<?php echo $tabcontent; ?>
					</div>    
				</div>
			</div>
		</div>
	</section>
<?php $this->endSection(); ?>

