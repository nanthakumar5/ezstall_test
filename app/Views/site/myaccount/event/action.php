<?php $this->extend("site/common/layout/layout1") ?>

<?php $this->section('content') ?>
<?php
$id 					= isset($result['id']) ? $result['id'] : '';
$name 					= isset($result['name']) ? $result['name'] : '';
$description 		    = isset($result['description']) ? $result['description'] : '';
$location 				= isset($result['location']) ? $result['location'] : '';
$mobile 				= isset($result['mobile']) ? $result['mobile'] : '';
$start_date 		    = isset($result['start_date']) ? dateformat($result['start_date']) : '';
$end_date 				= isset($result['end_date']) ? dateformat($result['end_date']) : '';
$start_time 			= isset($result['start_time']) ? $result['start_time'] : '';
$end_time 			    = isset($result['end_time']) ? $result['end_time'] : '';
$stalls_price 			= isset($result['stalls_price']) ? $result['stalls_price'] : '';
//$rvspots_price 			= isset($result['rvspots_price']) ? $result['rvspots_price'] : '';
$image      			= isset($result['image']) ? $result['image'] : '';
$image 				    = filedata($image, base_url().'/assets/uploads/event/');
$status 				= isset($result['status']) ? $result['status'] : '';
$eventflyer      		= isset($result['eventflyer']) ? $result['eventflyer'] : '';
$eventflyer 			= filedata($eventflyer, base_url().'/assets/uploads/eventflyer/');
$stallmap      			= isset($result['stallmap']) ? $result['stallmap'] : '';
$stallmap 				= filedata($stallmap, base_url().'/assets/uploads/stallmap/');
$barn        			= isset($result['barn']) ? $result['barn'] : [];
$pageaction 			= $id=='' ? 'Add' : 'Update';
?>

<section class="content">
	<div class="d-flex justify-content-between align-items-center flex-wrap">
		<div align="left" class="m-0"><h3>Events</h3></div>
		<div class="page-action mb-4 m-0" align="right">
			<a href="<?php echo base_url(); ?>/myaccount/events" class="btn btn-dark">Back</a>
		</div>
	</div>
	<div class="card">
		<div class="card-header w-100">
			<h3 class="card-title"><?php echo $pageaction; ?> Event</h3>
		</div>
		<div class="card-body">
			<form method="post" id="form" action="" autocomplete="off">
				<input type="hidden" id="id" name="id" value="<?php echo $id;?>" >
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12 my-2">
							<div class="form-group">
								<label>Name</label>								
								<input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="<?php echo $name; ?>">
							</div>
						</div>
						<div class="col-md-6 my-2">
							<div class="form-group">
								<label>Location</label>								
								<input type="text" name="location" class="form-control" id="location" placeholder="Enter Location" value="<?php echo $location; ?>">
							</div>
						</div>
						<div class="col-md-6 my-2">
							<div class="form-group">
								<label>Mobile</label>								
								<input type="text" name="mobile" class="form-control mobile" id="mobile" placeholder="Enter Mobile" value="<?php echo $mobile; ?>">								
							</div>
						</div>
						<div class="col-md-6 my-2">
							<div class="form-group">
								<label>Start Date</label>	
								<input type="text" class="form-control" name="start_date" value="<?php echo $start_date;?>" id="start_date">
							</div>
						</div>
						<div class="col-md-6 my-2">
							<div class="form-group">
								<label>End Date</label>	
								<input type="text" class="form-control" name="end_date" value="<?php echo $end_date;?>" id="end_date">
							</div>
						</div>
						<div class="col-md-6 my-2">
							<div class="form-group">
								<label>Start Time</label>	
								<input type="time" class="form-control" name="start_time" value="<?php echo $start_time;?>" id="start_time">
							</div>
						</div>
						<div class="col-md-6 my-2">
							<div class="form-group">
								<label>End Time</label>	
								<input type="time" class="form-control" name="end_time" value="<?php echo $end_time;?>" id="end_time">
							</div>
						</div>
						<div class="col-md-6 my-2">
							<div class="form-group">
								<label>Stalls Price</label>								
								<input type="text" name="stalls_price" class="form-control" id="stalls_price" placeholder="Enter Stalls Price" value="<?php echo $stalls_price;?>">								
							</div>
						</div>
<!-- 						<div class="col-md-6 my-2">
							<div class="form-group">
								<label>RV Spots Price</label>								
								<input type="text" name="rvspots_price" class="form-control" id="rvspots_price" placeholder="Enter RV Spots Price" value="<?php //echo $rvspots_price;?>">								
							</div>
						</div> -->
						<div class="col-md-12 my-2">
							<div class="form-group">
								<label>Event Description</label>
								<textarea class="form-control" id="description" name="description" placeholder="Enter Description" rows="3"><?php echo $description;?></textarea>
							</div>
						</div>
						<div class="col-md-4 my-2">
							<div class="form-group">
								<label>Event Image</label>			
								<div>
									<a href="<?php echo $image[1];?>" target="_blank">
										<img src="<?php echo $image[1];?>" class="image_source" width="100">
									</a>
								</div>
								<input type="file" id="file" name="file" class="image_file">
								<span class="image_msg messagenotify"></span>
								<input type="hidden" id="image" name="image" class="image_input" value="<?php echo $image[0];?>">
							</div>
						</div>							
						<div class="col-md-4 my-2">
							<div class="form-group">
								<label>Event Flyer</label>			
								<div>
									<a href="<?php echo $eventflyer[1];?>" target="_blank">
										<img src="<?php echo $eventflyer[1];?>" class="eventflyer_source" width="100">
									</a>
								</div>
								<input type="file" id="" name="" class="eventflyer_file">
								<span class="eventflyer_msg messagenotify"></span>
								<input type="hidden" id="eventflyer" name="eventflyer" class="eventflyer_input" value="<?php echo $eventflyer[0];?>">
							</div>
						</div>
						<div class="col-md-4 my-2">
							<div class="form-group">
								<label>Stall Map (optional)</label>			
								<div>
									<a href="<?php echo $stallmap[1];?>" target="_blank">
										<img src="<?php echo $stallmap[1];?>" class="stallmap_source" width="100">
									</a>
								</div>
								<input type="file" class="stallmap_file">
								<span class="stallmap_msg messagenotify"></span>
								<input type="hidden" id="stallmap" name="stallmap" class="stallmap_input" value="<?php echo $stallmap[0];?>">
							</div>
						</div>	
					</div>

					<div class="card">
						<div class="card-body">
							<div>
								<div class="d-flex justify-content-between flex-wrap">
									<p>Will you be selling feed at this event? </p>

									<div>
										<button name="feed" class="btn btn-primary sellingfeed" id="yesfeed" value="1"> Yes </button>
										<button name="feed" class="btn btn-danger sellingfeed" id="nofeed" value="2"> No </button>
									</div>
								</div>
								<div class="d-flex justify-content-between flex-wrap">
									<p>Will you be selling shavings at this event?</p>
									<div>
										<button name="shavings" id="yesshavings" class="btn btn-primary sellingshavings" value="1"> Yes </button>
										<button name="shavings" id="noshavings" class="btn btn-danger sellingshavings" value="2"> No </button>
									</div>
								</div>
								<div class="d-flex justify-content-between flex-wrap">
									<p>Will you have RV Hookups at this event? </p>
									<div>
										<button name="rvhookups" id="yeshookups" class="btn btn-primary rvhookups" value="1"> Yes</button>
										<button name="rvhookups" id="nohookups" class="btn btn-danger rvhookups" value="2"> No</button>
									</div>
								</div>
								<div class="d-flex justify-content-between flex-wrap">
									<p>How will you be charging for your stalls? </p>
									<div>
										<button name="chargingstalls" id="week" class="btn btn-primary chargingstalls" value="1">Per Week</button>
										<button name="chargingstalls" id="month" class="btn btn-primary chargingstalls" value="2">Per Month</button>
										<button name="chargingstalls" id="flatrate" class="btn btn-primary chargingstalls" value="3">Flat Rate</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body p-0">
						<div class="container row mt-5 dash-barn-style mx-auto">
							<div class="row align-items-center mb-4 p-0 addbarn">
								<div class="col-md-3">
									<p class="fs-2 fw-bold mb-0 barntfont">Barn</p>
								</div>
								<div class="col-md-9 t-right p-0 respsm">
									<input type="hidden" value="" name="barnvalidation" id="barnvalidation">
									<a href="javascript:void(0);" class="btn btn-info bulkbtn_barn">Add Bulk Barn</a>
									<input type="file" class="bulkfile_barn" style="display:none;">
									<button class="btn-stall barnbtn" value="4" name="tst" id="tes">Add Barn</button>
								</div>
							</div>
							<ul class="nav nav-pills flex-column col-md-3 barntab" role="tablist"></ul>
							<div class="tab-content col-md-9 stalltab"></div>
						</div>
					</div>
					<div class="card-body p-0 addfeed" style="display: none;">
						<div class="container row mt-5 dash-barn-style mx-auto">
							<div class="row align-items-center mb-4 p-0 addfeed">
								<div class="col-md-3">
									<h4 class="fw-bold mb-0 barntfontfeed">Feed</h4>
								</div>
								<div class="col-md-9 t-right p-0 respsm">
								<button class="btn-stall feedbtn">Add feed</button>
								</div>
							</div>

							<div class="row" >
								<ul class="nav nav-pills flex-column feedlist" role="tablist"></ul>
								<div class="tab-content col-md-9 feedstalltab"></div>
							</div>
						</div>
					</div>
					<div class="card-body p-0  addshavings" style="display: none;">
						<div class="container row mt-5 dash-barn-style mx-auto">
							<div class="row align-items-center mb-4 p-0">
								<div class="col-md-3">
									<h4 class="fw-bold mb-0 barntfontshavings">Shavings</h4>
								</div>
								<div class="col-md-9 t-right p-0 respsm">
									<button class="btn-stall shavingsbtn">Add Shavings</button>
								</div>
							</div>
							<div class="row" >
								<ul class="nav nav-pills flex-column shavingslist" role="tablist"></ul>
								<div class="tab-content col-md-9 shavingsstalltab"></div>
							</div>
						</div>
					</div>
					<div class="card-body p-0  addrvhookups" style="display: none;">
						<div class="container row mt-5 dash-barn-style mx-auto">
							<div class="row align-items-center mb-4 p-0">
								<div class="col-md-3">
									<h4 class="fw-bold mb-0 barntfont">RV Hookups</h4>
								</div>
								<div class="col-md-9 t-right p-0 respsm">
									<input type="hidden" value="" name="rvhookupsvalidation" id="rvhookupsvalidation">
									<a href="javascript:void(0);" class="btn btn-info addbulkrvhookups">Add Bulk Rv Hookups</a>
									<input type="file" class="bulkrvfile" style="display:none;">
									<button class="btn-stall addrvhookupsbtn">Add Rv Hookups</button>
								</div>
							</div>
							<div class="row">
								<ul class="nav nav-pills flex-column col-md-3 rvhookupslist" role="tablist"></ul>
								<div class="tab-content col-md-9 rvhookupstab"></div>
							</div>
						</div>
					</div>

					<div class="col-md-12 mt-4">
						<input type="hidden" name="actionid" value="<?php echo $id; ?>">
						<input type="hidden" name="userid" value="<?php echo $userid; ?>">
						<input type="submit" id ="eventSubmit" class="btn btn-danger" value="Submit">
						<a href="<?php echo base_url(); ?>/myaccount/events" class="btn btn-dark">Back</a>
					</div>
				</div>
			</form>

		</div>
	</div>
</section>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
<?php echo $questiontag; ?>

<script>
	var barn				 	= $.parseJSON('<?php echo addslashes(json_encode($barn)); ?>'); 
	var statuslist		 		= $.parseJSON('<?php echo addslashes(json_encode($statuslist)); ?>');
	var barnIndex        		= '0';
	var stallIndex       		= '0';
	var occupied 	 			= $.parseJSON('<?php echo json_encode((isset($occupied)) ? $occupied : []); ?>');
	var reserved 	 			= $.parseJSON('<?php echo json_encode((isset($reserved)) ? explode(",", implode(",", array_keys($reserved))) : []); ?>');
	var occupiedstallcount 	 	= '<?php echo (isset($occupied)) ? count($occupied) : 0; ?>';
	
	$(function(){

		$('#myModal').modal('show'); 

		uidatepicker("#start_date, #end_date");
		fileupload([".image_file"], ['.image_input', '.image_source','.image_msg']);
		fileupload([".eventflyer_file", ['jpg','jpeg','png','gif','tiff','tif','pdf']], ['.eventflyer_input', '.eventflyer_source','.eventflyer_msg']);
		fileupload([".stallmap_file", ['jpg','jpeg','png','gif','tiff','tif','pdf']], ['.stallmap_input', '.stallmap_source','.stallmap_msg']);
		fileupload([".stall_file"], ['.stall_input', '.stall_source','.stall_msg']);

		validation(
			'#form',
			{
				name 	     : {
					required	: 	true
				},
				description  : {	
					required	: 	true
				},
				location 	 : {
					required	: 	true
				},					
				mobile       : {
					phoneUs     :   true,
					required	: 	true
				},
				start_date   : {
					required	: 	true
				},
				end_date     : {
					required	: 	true
				},
				start_time   : {
					required	: 	true
				},
				end_time     : {
					required	: 	true
				},
				status        : {  
					required	: 	true
				},
				barnvalidation : {
					required 	: true
				}
			},
			{},
			{
				ignore : []
			}
		);

		barnstall('barn', [['.barnbtn'], ['.barntab', '.stalltab'], [0, 0], ['#barnvalidation']], [barn, occupied, reserved])
		barnstall('rvhookups', [['.addrvhookupsbtn'], ['.rvhookupslist', '.rvhookupstab'], [0, 0], ['#rvhookupsvalidation']], [barn, occupied, reserved])
		products('feed', [['.feedbtn'], ['.feedlist'], [0]])
		products('shavings', [['.shavingsbtn'], ['.shavingslist'], [0]])

	});
	

	$('.sellingfeed').click(function(e){ 
		e.preventDefault();
		var feed = $(this).val();
		if(feed=='1'){
			$(".addfeed").show(); 
			$('#nofeed').removeClass("btn btn-success").addClass("btn btn-danger");
			$('#yesfeed').removeClass("btn btn-primary").addClass("btn btn-success");
		}
		else{
			$('#yesfeed').removeClass("btn btn-success").addClass("btn btn-primary");
			$('#nofeed').removeClass("btn btn-danger").addClass("btn btn-success");
			$(".addfeed").hide();
			
		}
	});

	$('.sellingshavings').click(function(e){ 
		e.preventDefault();
		var shavings = $(this).val();
		if(shavings=='1'){
			$(".addshavings").show(); 
			$('#noshavings').removeClass("btn btn-success").addClass("btn btn-danger");
			$('#yesshavings').removeClass("btn btn-primary").addClass("btn btn-success");
		}
		else{
			$('#yesshavings').removeClass("btn btn-success").addClass("btn btn-primary");
			$('#noshavings').removeClass("btn btn-danger").addClass("btn btn-success");
			$(".addshavings").hide();			
		}
	});

	$('.rvhookups').click(function(e){ 
		e.preventDefault();
		var rvhookups = $(this).val();
		if(rvhookups=='1'){
			$(".addrvhookups").show(); 
			$('#nohookups').removeClass("btn btn-success").addClass("btn btn-danger");
			$('#yeshookups').removeClass("btn btn-primary").addClass("btn btn-success");
		}
		else{
			$('#yeshookups').removeClass("btn btn-success").addClass("btn btn-primary");
			$('#nohookups').removeClass("btn btn-danger").addClass("btn btn-success");
			$(".addrvhookups").hide();
		}
	});

	$('.chargingstalls').click(function(e){ 
		e.preventDefault();
		var chargingstalls = $(this).val();
		if(chargingstalls=='1'){
			$('#week').removeClass("btn btn-primary").addClass("btn btn-success");
		}
		else if(chargingstalls=='2'){
			$('#month').removeClass("btn btn-primary").addClass("btn btn-success");
		}
		else{
			$('#flatrate').removeClass("btn btn-primary").addClass("btn btn-success");
		}
	});

	$('#eventSubmit').click(function(e){
		var totalstall 		= $('.dash-stall-base').length
		var result 			= parseInt(totalstall) - parseInt(occupiedstallcount);
		tabvalidation();
	});
	
	$(function(){
			$('#mobile').inputmask("(999) 999-9999");
		});

		jQuery.validator.addMethod("phoneUS", function(mobile, element) {
		    mobile = mobile.replace(/\s+/g, "");
		    return this.optional(element) || mobile.length > 9 && 
		    mobile.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
		}, "Please specify a valid phone number");

</script>
<?php $this->endSection(); ?>
