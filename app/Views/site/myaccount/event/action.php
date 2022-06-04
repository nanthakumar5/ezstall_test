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
					<div class="container row mt-5 dash-barn-style mx-auto">
						<div class="row align-items-center mb-4 p-0 addbarn">
							<div class="col-md-3">
								<p class="fs-2 fw-bold mb-0 barntfont">Barn</p>
							</div>
							<div class="col-md-9 t-right p-0 respsm">
								<input type="hidden" value="" name="barnvalidation" id="barnvalidation">
								<a href="javascript:void(0);" class="btn btn-info addbulkbarnbtn">Add Bulk Barn</a>
								<input type="file" class="bulkbarnfile" style="display:none;">
								<button class="btn-stall barnbtn">Add Barn</button>
							</div>
						</div>
						<ul class="nav nav-pills flex-column col-md-3 barntab" role="tablist"></ul>
						<div class="tab-content col-md-9 stalltab"></div>
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

	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Stall</h4>
					<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<div class="col-md-12 my-2">
						<div class="form-group">
							<label>Stall Name</label>
							<input type="text" id="stall_name" class="form-control" placeholder="Enter Your Stall Name">
						</div>
					</div>
					<div class="col-md-12 my-2">
						<div class="form-group">
							<label>Price</label>
							<input type="text" id="stall_price" class="form-control" placeholder="Enter Price">
						</div>
					</div>
					<div class="col-md-6 my-2">
						<div class="form-group">
							<label>Stall Image</label>			
							<div>
								<a href="" target="_blank">
									<img src="" class="stall_source" width="100">
								</a>
							</div>
							<input type="file" class="stall_file">
							<span class="stall_msg"></span>
							<input type="hidden" id="stall_image" class="stall_input" value="">
						</div>
					</div>	
					<div class="col-md-12 my-2">
						<div class="form-group">
							<label>Total Number of Stalls</label>
							<input type="number" id="stall"  name="stall" class="form-control" placeholder="Enter Total Number of Stalls" min="1" required>
						</div>
					</div>
					<div class="col-md-12 my-2">
						<div class="form-group">
							<label>First Stall Number</label>
							<input type="text" id="stallstarting"  name="stallstarting" class="form-control" placeholder="Enter First Stall Number" min="1" required>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" id="barnIndexValue" name="barnIndexValue" value="0">
					<button type="button"class="btn btn-info bulkstallbtn">Submit</button>
					<button type="button"class="btn btn-info " data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</section>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>

<script>
	var barn				 	= $.parseJSON('<?php echo addslashes(json_encode($barn)); ?>'); 
	var statuslist		 		= $.parseJSON('<?php echo addslashes(json_encode($statuslist)); ?>');
	var barnIndex        		= '0';
	var stallIndex       		= '0';
	var occupied 	 			= $.parseJSON('<?php echo json_encode((isset($occupied)) ? $occupied : []); ?>');
	var reserved 	 			= $.parseJSON('<?php echo json_encode((isset($reserved)) ? explode(",", implode(",", array_keys($reserved))) : []); ?>');
	var occupiedstallcount 	 	= '<?php echo (isset($occupied)) ? count($occupied) : 0; ?>';
	
	$(function(){
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

		if(barn.length > 0){
			$(barn).each(function(i, v){
				barndata(v);
			});
		}
	});

	
	$('#eventSubmit').click(function(e){
		var totalstall 		= $('.dash-stall-base').length
		var result 			= parseInt(totalstall) - parseInt(occupiedstallcount);
		tabvalidation();
	});
	
	$(document).on('click', '.barntab li a.active', function(e){ 
		e.preventDefault();
		barntext();
	});
	
	$('.barnbtn').click(function(e){
		e.preventDefault();
		barndata([], 1);
	});

	function barndata(result=[], type=''){ 
		var barnId   	= result['id'] ? result['id'] : '';
		var barnName 	= result['name'] ? result['name'] : 'Barn';
		var stall		= result['stall'] ? result['stall'] : [];
		
		var activeclass = $.trim($(".barntab").html())=='' ? 'active' : '';
		
		var barntab='\
			<li class="nav-item text-center mb-3">\
				<a class="nav-link tab-link '+activeclass+'" data-bs-toggle="pill" data-bs-target="#barnstall'+barnIndex+'">\
					<span class="barnametext">'+barnName+'</span>\
					<input type="text" id="barn'+barnIndex+'name" name="barn['+barnIndex+'][name]" class="form-control barnnametextbox" placeholder="Enter Barn Name" value="'+barnName+'" style="display:none;">\
				</a>\
				<input type="hidden" name="barn['+barnIndex+'][id]" value="'+barnId+'">\
			</li>\
		';
		
		var stalltab = '\
			<div id="barnstall'+barnIndex+'" class="container tab-pane p-0 mb-3 barn_wrapper_'+barnIndex+' '+activeclass+'">\
				<div class="col-md-11 p-0 my-3 stallbtns">\
					<input type="hidden" value="" name="stallvalidation'+barnIndex+'" id="stallvalidation'+barnIndex+'">\
					<button class="btn-stall stallbtn" data-barnIndex="'+barnIndex+'" >Add Stall</button>\
					<button class="btn-stall" data-barnIndex="'+barnIndex+'" data-bs-toggle="modal" data-bs-target="#myModal" id="addbulkstallbtn">Add Bulk Stall</button>\
					<button class="btn-stall barnremovebtn">Remove Barn and Stall</button>\
				</div>\
			</div>\
		';

		$('.barntab').append(barntab);
		$('.stalltab').append(stalltab);
		$('#barnvalidation').val('1');
		$('#barnvalidation').valid();

		$(document).find('#barn'+barnIndex+'name').rules("add", {required: true, messages: {required: "Barn Name field is required."}});
		$(document).find('#stallvalidation'+barnIndex).rules("add", {required: true, messages: {required: "Stall field is required."}});

		if(stall.length > 0){
			$(stall).each(function(i, v){
				stalldata(barnIndex, v)
			});
		}
		
		if(type=='1') stalldata(barnIndex);
		++barnIndex;
	}

	$(document).on('click', '.stallbtn', function(e){ 
		e.preventDefault();
		stalldata($(this).attr('data-barnIndex'));
	});
	
	function stalldata(barnIndex, result=[])
	{  
		var stallId       		= result['id'] ? result['id'] : '';
		var stallName     		= result['name'] ? result['name'] : '';
		var stallPrice    		= result['price'] ? result['price'] : '';
		var stallImage    		= result['image'] ? result['image'] : '';
		var stallBulkImage    	= result['bulkimage'] ? result['bulkimage'] : '';
		if(stallImage!='' && stallBulkImage==''){
			var stallImages   	= '<?php echo base_url()?>/assets/uploads/stall/'+stallImage;
		}else if(stallBulkImage!=''){
			var stallImages   	= '<?php echo base_url()?>/assets/uploads/temp/'+stallBulkImage;
		}else{
			var stallImages   	= '<?php echo base_url()?>/assets/images/upload.png';
		}
		
		var availability = '<a href="javascript:void(0);" class="dash-stall-remove fs-7 stallremovebtn" data-barnIndex="'+barnIndex+'"><i class="fas fa-times text-white"></i></a>';
		if($.inArray(stallId, occupied) !== -1)	availability = '<span class="red-box"></span>';
		if($.inArray(stallId, reserved) !== -1)	availability = '<span class="yellow-box"></span>';

		var data='\
		<div class="row mb-2 dash-stall-base">\
			<div class="col-md-6 mb-3">\
				<input type="text" id="stall'+stallIndex+'name" name="barn['+barnIndex+'][stall]['+stallIndex+'][name]" class="form-control  fs-7" placeholder="Stall Name" value="'+stallName+'">\
			</div>\
			<div class="col-md-2 mb-3">\
				<input type="text" id="stall'+stallIndex+'price" name="barn['+barnIndex+'][stall]['+stallIndex+'][price]" class="form-control fs-7" placeholder="Price" value="'+stallPrice+'">\
			</div>\
			<div class="col-md-3 mb-3">\
				<a href="'+stallImages+'" target="_blank">\
					<img src="'+stallImages+'"  class="stall_image_source'+stallIndex+'" width="40" height="35">\
				</a>\
				<button class="dash-upload fs-7" title="Upload image here">Upload</button>\
				<input type="file" class="stallimage stall_image_file'+stallIndex+'" style="display:none;">\
				<span class="stall_image_msg'+stallIndex+'"></span>\
				<input type="hidden" name="barn['+barnIndex+'][stall]['+stallIndex+'][image]" class="stall_image_input'+stallIndex+'" value="'+stallImage+'">\
			</div>\
			<div class="col-md-1 mb-3 delete">\
				<input type="hidden" name="barn['+barnIndex+'][stall]['+stallIndex+'][id]" value="'+stallId+'">\
				<input type="hidden" name="barn['+barnIndex+'][stall]['+stallIndex+'][status]" value="1">\
				'+availability+'\
			</div>\
		</div>\
		';
		
		$(document).find('#stallvalidation'+barnIndex).val('1');
		$(document).find('#stallvalidation'+barnIndex).valid();
		
		$(document).find('.barn_wrapper_'+barnIndex).find('.stallbtns').before(data); 

		fileupload([".stall_image_file"+stallIndex], [".stall_image_input"+stallIndex, ".stall_image_source"+stallIndex, ".stall_image_msg"+stallIndex]);

		$(document).find('#stall'+stallIndex+'name').rules("add", {required: true, messages: {required: "Stall Name field is required."}});
		$(document).find('#stall'+stallIndex+'price').rules("add", {required: true, messages: {required: "Price field is required."}});
		++stallIndex;
	}
	
	$(document).on('keyup', '.barnnametextbox', function(){
		$(this).parent().find('.barnametext').text($(this).val());
	})
	
	$(document).on('click', function(){
		if (!$(event.target).is(".barnnametextbox, .barnametext")){
			$(document).find('.requiredtab').show();
			$(document).find('.barnametext').show();
			$(document).find('.barnnametextbox').hide();
		}
	})
	
	function barntext(type=''){
		setTimeout(function () {
			$(document).find('.barntab li').each(function(){
				if($(this).find('.tab-link').hasClass('active')){
					$(this).find('.tab-link .requiredtab').hide();
					$(this).find('.tab-link .barnametext').hide();
					$(this).find('.tab-link .barnnametextbox').show();
				}else{
					$(this).find('.tab-link .requiredtab').show();
					$(this).find('.tab-link .barnametext').show();
					$(this).find('.tab-link .barnnametextbox').hide();
				}
				
				$(this).find('.tab-link .barnametext').text($(this).find('.tab-link .barnnametextbox').val());
			})
		}, 10);
	}
		
	$(document).on('click','.dash-upload', function (e) {
		e.preventDefault();
		$(this).parent().find('.stallimage').click();
	});
	
	$(document).on('click', '.barnremovebtn', function(e){
		e.preventDefault();
		var stalltabparent = $(this).parent().parent();
		$(document).find('[data-bs-target="#'+stalltabparent.attr('id')+'"]').parent().remove();
		stalltabparent.remove();
		
		if($(document).find('.barntab li').length){
			$(document).find('.barntab li:first a').addClass('active');
			$(document).find('.stalltab div:first').addClass('active');
		}else{
			$('#barnvalidation').val('');
			$('#barnvalidation').valid();
		}
	});

	$(document).on('click', '.stallremovebtn', function(e){
		e.preventDefault();
		var stallparent = $(this).parent().parent().parent();
		var bi = $(this).attr('data-barnIndex')
		$(this).parent().parent().remove();
		
		if(stallparent.find('.dash-stall-base').length==0){
			$(document).find('#stallvalidation'+bi).val('');
			$(document).find('#stallvalidation'+bi).valid();
		}
	})

	$('#myModal').on('shown.bs.modal', function (e) { 
		$('#stall_name, #stall_price, #stall_image, #stall_file, #stall').val('');
		$('#stall_status').val('1');
		$('.stall_source').attr('src', '<?php echo base_url()?>/assets/images/upload.png');
		$('.stall_source').parent().attr('href', '<?php echo base_url()?>/assets/images/upload.png');
	})

	$(document).on('click','#addbulkstallbtn', function (e) { 
		e.preventDefault();
		$('#barnIndexValue').val($(this).attr('data-barnIndex'));
	});

	$('.bulkstallbtn').click(function(e){
		e.preventDefault();
		if($('#stall').val()==''){
			$('#stall').focus();
			return false;
		}

		var name          = $('#stall_name').val();
		var price         = $('#stall_price').val();
		var image         = $('#stall_image').val();
		var stallcount    = $('#stall').val();
		var stallstarting = $('#stallstarting').val();
		var barnIndex     = $('#barnIndexValue').val();

		for(var i=0; i<stallcount; i++){ 
			var names = stallstarting!='' ? name+' '+stallstarting : name;
			stalldata(barnIndex, {name:names,price:price,status:1,bulkimage:image});
			if(stallstarting!='') stallstarting++ ;
		}

		$('#myModal').modal('hide');
	});
	
	$(document).on('click','.addbulkbarnbtn', function () {
		$('.bulkbarnfile').click();
	});

	$(document).on('change','.bulkbarnfile', function () {
  		var formdata = new FormData();
		formdata.append('file', $(this)[0].files[0]); 
		
		ajax(
			'<?php echo base_url(); ?>/myaccount/events/importbarnstall', 
			formdata, 
			{
				contenttype : 1,
				processdata : 1,
				success: function(result) {
					$(result).each(function(i, v){
						barndata(v)
					})
				}
			}
		);
	});

	function tabvalidation(){
		$(document).find('.requiredtab').remove();	
		
		setTimeout(function(){
			$(document).find('.dash-stall-base').each(function(){;
				if($(this).find('input.error_class_1').length){
					var tabid = $(this).parent().attr('id');
					$(document).find('a[data-bs-target="#'+tabid+'"] input').after('<span class="requiredtab">*</span>');
				}
			})
		}, 100);
	}

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

