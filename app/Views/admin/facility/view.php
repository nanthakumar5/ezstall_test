<?= $this->extend("admin/common/layout/layout2") ?>

<?php $this->section('content') ?>
	<?php
		$id 					= isset($result['id']) ? $result['id'] : '';
		$userid 				= isset($result['user_id']) ? $result['user_id'] : '';
		$name 					= isset($result['name']) ? $result['name'] : '';
		$description 		    = isset($result['description']) ? $result['description'] : '';
		$image      			= isset($result['image']) ? $result['image'] : '';
		$image 				    = filedata($image, base_url().'/assets/uploads/event/');
		$barn        			= isset($result['barn']) ? $result['barn'] : [];
	?>
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>View Facility</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right"> 
						<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
						<li class="breadcrumb-item"><a href="<?php echo getAdminUrl(); ?>/facility">Facility</a></li>
						<li class="breadcrumb-item active">View Facility</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="page-action">
			<a href="<?php echo getAdminUrl(); ?>/facility" class="btn btn-primary">Back</a>
		</div>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title"> Facility Details </h3>
			</div>
			<div class="card-body">
			    <h3 class="event_heading"> <?php echo $name;?> </h3>
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<img class='img-fluid w-100' src="<?php echo $image[1];?>" alt="Facility Image" />
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 mt-3">
							<h3 class="text-bold">Description:</h3>
							<p class="card-text mb-0"><?php echo $description; ?></p>    
						</div>
					</div>
				</div>
				<h3 class="text-bold mx-2 mt-3"> Barn and stalls </h3>
				<?php 
					$tabbtn = '';
					$tabcontent = '';
					foreach ($barn as $barnkey => $barndata) {
						$barnid = $barndata['id'];
						$barnname = $barndata['name'];
						$barnactive = $barnkey=='0' ? ' show active' : '';
						$tabbtn .= '<button class="nav-link'.$barnactive.'" data-bs-toggle="tab" data-bs-target="#barn'.$barnid.'" type="button" role="tab" aria-controls="barn'.$barnid.'" aria-selected="true">'.$barnname.'</button>';
						
						$tabcontent .= '<div class="tab-pane container'.$barnactive.'" id="barn'.$barnid.'">
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
						<div class="nav nav-tabs mx-07" id="nav-tab" role="tablist">
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
