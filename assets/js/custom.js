function baseurl(){
	var base = window.location;

	if(base.host=='localhost'){
		return base.protocol + "//" + base.host + "/ezstall2/";
	}else{
		return base.protocol + "//" + base.host + "/ezstall2/";
	}
}

/** Jquery Validation **/

function validation(selector, rules, messages, extras=[])
{
	var validation = {};

	validation['rules'] 			= 	rules;
	validation['messages'] 			=	messages;
	validation['errorElement'] 		= 	(extras['errorElement']) ? extras['errorElement'] : 'p';
	validation['errorClass'] 		= 	(extras['errorClass']) ? extras['errorClass'] : 'error_class_1';
	validation['ignore'] 			= 	(extras['ignore']) ? extras['ignore'] : ':hidden';
	validation['errorPlacement']	= 	function(error, element) {
											if(element.attr('data-error') == 'firstparent'){
												jQuery(element).parent().append(error);
											}else if(element.attr('data-error') == 'secondparent'){
												jQuery(element).parent().parent().append(error);
											}else{
												error.insertAfter(element);
											}
										}

	var validator = $(selector).validate(validation);						

	if(extras['callback']){
		return validator;
	}
}

/** Datatable **/

function ajaxdatatables(selector, options={}){
	if(options.destroy && options.destroy==1){
		$(selector).DataTable().destroy();
	}

	var datatableoptions = {
		'responsive'	: 	true, 
		'autoWidth' 	: 	false,
		'processing'	: 	true,
		'serverSide'	: 	true,	
		'ajax'			: 	{
								'url' 		: 	options.url,
								'data'		: 	(options.data) ? options.data : {},
								'dataType'	: 	'json',								
								'type'		: 	(options.method) ? options.method : 'post',
								'complete'	: 	function(){
													tooltip();
												}
							},	
		'columns'		: 	options.columns,
		'order'			: 	(options.order) ? options.order : [[0, 'desc']],
		'columnDefs'	: 	(options.columndefs) ? options.columndefs : [],
		'searching'		: 	(options.search && options.search=='0') ? false : true,
		'lengthMenu'	: 	(options.lengthmenu && options.lengthmenu.length > 0) ? options.lengthmenu : [10, 25, 50, 100],
	};

	var datatable = $(selector).DataTable(datatableoptions);	

	$(document).on('resize', function(){
		datatable.columns.adjust().responsive.recalc();
	})
}

function tooltip(){
	$('[data-toggle="tooltip"]').tooltip(); 
}


/** Select 2 **/

function select2(selector, options={}){
	$(selector).select2(options);
}


/** Sweet Alert 2 **/
function sweetalert2(action, data, extras=[]){

	Swal.fire({
		title: extras['title'] ? extras['title'] : 'Are you sure?',
		text: extras['text'] ? extras['text'] : "You want to proceed?",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: extras['confirm'] ? extras['confirm'] : 'Yes',
		cancelButtonText: extras['cancel'] ? extras['cancel'] : 'No',
	}).then((result) => {
		if (result.value) {
			extras['method'] ? extras['method'] : formsubmit(action, data)
		}
	})
}


function formsubmit(action, data){
	$('<form action="'+action+'" method="post">'+data+'</form>').appendTo('body').submit();
}


/** Ajax **/

function ajax(url, data, extras=[]){  
    var options = {};
    
    options['url']          =   url;
    options['type']         =   (extras['type']) ? extras['type'] : 'post';
    options['data']         =   data;
    options['dataType']     =   (extras['datatype']) ? extras['datatype'] : 'json';
    
    if(extras['contenttype'])   options['contentType']  =   false;
    if(extras['processdata'])   options['processData']  =   false;
    if(extras['asynchronous'])  options['async']        =   false;
    if(extras['beforesend'])    options['beforeSend']   =   extras['beforesend'];
    if(extras['complete'])      options['complete']     =   extras['complete'];
    
    if(extras['success']){
        options['success']      =   extras['success'];
    }else if(extras['method']){
        options['success']      =   function(data){ 
                                        extras['method'](data);
                                    }
    }   
    
    if(extras['error']){
        options['error']      =   extras['error'];
    }
	
    $.ajax(options);
}


function filetype(data1, data2){

	var type 		= data1[0];
	var multiple 	= (data1[2]) ? data1[2] : '';
	var selector 	= data2[0];
	var name 		= data2[1];
	var input		= data2[2][0];
	var source		= data2[2][1];

	if(type=='image'){

		var data = 	'\
						<div class="imagecontent">\
							<a href="'+source+'" target="_blank">\
								<img src="'+source+'" class="'+name+'_source" width="100">\
							</a>\
						</div>\
					';

	}else if(type=='video'){

		var data = 	'\
						<div class="videocontent">\
							<video width="320" height="240" controls>\
								<source src="'+source+'" type="video/mp4" class="'+name+'_source">\
							</video>\
						</div>\
					';

	}else if(type=='audio'){

		var data = 	'\
						<div class="audiocontent">\
							<audio controls>\
								<source src="'+source+'" type="audio/mpeg" class="'+name+'_source">\
							</audio>\
						</div>\
					';
	}

	if(multiple==''){
		var fields = '<input type="file" class="'+name+'_file"><input type="hidden" name="'+name+'" class="'+name+'_input" value="'+input+'">';
	}else{
		var fields = '<div class="'+name+'multiple"></div><div class="clear"></div><input type="file" class="'+name+'_file" multiple>';
	}

	$(selector).html(data+fields);

	if(multiple!=''){

		var multipledata 	= (data2[3] && data2[3][0] && data2[3][0]!='') ? data2[3][0].split(',') : '';
		var multipleurl 	= (data2[3] && data2[3][1] && data2[3][1]!='') ? data2[3][1] : '';

		if(multipledata!=''){
			$(multipledata).each(function(i, v){
				multiplefileappend(name, v, multipleurl+'/'+v)
			})
		}

		$(document).on('click', '.multipleupload i', function(){
			if($(this).parent().parent().find('.multipleupload').length=='1') $(this).parent().parent().parent().find('.imagecontent').show();
			$(this).parent().remove();
		})		
	}
}


function multiplefileappend(name, value, src){

	var data = 	'<div class="multipleupload">\
					<input type="hidden" value="'+value+'" name="'+name+'[]">\
					<img src="'+src+'" width="100">\
					<i class="fas fa-times"></i>\
				</div>';

	$(document).find('.'+name+'multiple').append(data);
	$(document).find('.'+name+'multiple').parent().find('.imagecontent').hide();
}


function fileupload(data1=[], data2=[], path){ 
	var url 			= baseurl()+'ajax/fileupload';
	var path			= baseurl()+'assets/uploads/temp/';
	var relativepath	= './assets/uploads/temp/';
	var pdfimg			= baseurl()+'assets/images/pdf.png';
	
	var selector 		= data1[0];
	var extension 		= data1[1] ? data1[1] : ['jpg','jpeg','png','gif','tiff','tif'];
	
	$(document).on('change', selector, function(){
		var name 		= $(this).val();
		var ext 		= name.split('.').pop().toLowerCase();
		
		if($.inArray(ext, extension) !== -1){
            var formdata    = new FormData();
            formdata.append("file", $(selector)[0].files[0]);
            formdata.append("path", relativepath);
            formdata.append("type", extension.join('|'));
            formdata.append("name", name);
			
			ajax(url, formdata, { contenttype : 1, processdata : 1, method : fileappend});
		}else{
			$(selector).val('');
			alert('Supported file format are '+extension.join(','));
		}
	})
	
	function fileappend(data){
        if(data.success && data2.length){           
            var file        = data.success;
            var fileinput   = (data2[0]) ? data2[0] : '';
            var filesource  = (data2[1]) ? data2[1] : '';
            
            var ext = file.split('.').pop().toLowerCase();
            
            if(ext=='jpg' || ext=='jpeg' || ext=='png' || ext=='tif' || ext=='tiff'){
                var filesrc = path+'/'+file;
            }else if(ext=='pdf'){
                var filesrc = pdfimg;
            }
            
            if(fileinput!=''){
                $(fileinput).val(file);
            }
            
            if(filesource!='' && filesrc){
                $(filesource).attr('src', filesrc);
                $(filesource).parent().attr('href', filesrc);
            }
        }
        
        $(selector).val('');
    }
}

/** Tinymce Editor **/

function editor(selector, height=300){

	tinymce.init({
		selector	: 	selector,
		height		: 	height,
		statusbar	: 	false,
		menubar		: 	false,
	  	plugins		: 	'code', 
	 	toolbar		: 	'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl | code',
		
	});
}


/** Datepicker **/

function dateformat(selector, extras=[]){

	var options = {};	
	options['format'] 		= 'mm-dd-yyyy';
	options['autoclose'] 	= true;

	if($.inArray('startdate', extras) != -1) options['startDate'] = new Date();
	
	$(selector).datepicker(options).on('keypress paste', function(e){
		e.preventDefault();
		return false;
	});
}

function uidatepicker(selector, extras=[]){

	var options = {};	
	options['dateFormat'] 		= 'mm-dd-yy';
	options['changeMonth'] 		= true;
	options['changeYear'] 		= true;
	
	if(extras['mindate'])	options['minDate'] = extras['mindate'];
	if(extras['maxdate'])	options['maxDate'] = extras['maxdate'];
	if(extras['close'])		options['onClose'] = extras['close'];
	
	$(selector).datepicker(options);
}

$(".navbar-toggler.collapsed").click(function() {
    $(".navbar-collapse.collapse").slideToggle();
});

$('#sidebarCollapse').click(function() {
    $('.navbar-header').toggleClass("show-sidebar");
});

function barnstall(barnstallname, barnstallitem=[], barnstallresult=[]){  
	var selector_btn1 		= barnstallitem[0][0];
	var barn_append 		= barnstallitem[1][0];
	var stall_append 		= barnstallitem[1][1];
	var barnIndex 			= barnstallitem[2][0];
	var stallIndex 			= barnstallitem[2][1];
	var barn_validation 	= barnstallitem[3][0];
	var charging_flagdata	= barnstallitem[4][0];  
	var usertype			= barnstallitem[4][1];  
	
	var bsresult = barnstallresult[0] ? barnstallresult[0] : [];
	var occupied = barnstallresult[1] ? barnstallresult[1] : [];
	var reserved = barnstallresult[2] ? barnstallresult[2] : [];
	
	/* START BARN AND STALL RESULT */
	if(bsresult.length > 0){
		$(bsresult).each(function(i, v){
			barndata(v);
		});
	}
	/* END BARN AND STALL RESULT */
	
	/* START ADD EDIT BARN */
	$(selector_btn1).click(function(e){
		e.preventDefault();
		barndata([], 1);
	});
	
	function barndata(result=[], type=''){ 
		var barnId   	= result['id'] ? result['id'] : '';
		var barnName 	= result['name'] ? result['name'] : 'Barn';
		var stall		= result['stall'] ? result['stall'] : (result['rvstall'] ? result['rvstall'] : []);
		
		var activeclass = $.trim($(barn_append).html())=='' ? 'active' : '';
		
		var barntab='\
			<li class="nav-item text-center mb-3">\
				<a class="nav-link tab-link '+activeclass+'" data-bs-toggle="pill" data-bs-target="#tabtarget_'+barnstallname+'_'+barnIndex+'">\
					<span class="barnametext">'+barnName+'</span>\
					<input type="text" id="barn_'+barnstallname+'_'+barnIndex+'_name" name="'+barnstallname+'['+barnIndex+'][name]" class="form-control barnnametextbox" placeholder="Enter Barn Name" value="'+barnName+'" style="display:none;">\
				</a>\
				<input type="hidden" name="'+barnstallname+'['+barnIndex+'][id]" value="'+barnId+'">\
			</li>\
		';
		
		var stalltab = '\
			<div id="tabtarget_'+barnstallname+'_'+barnIndex+'" class="container tab-pane p-0 mb-3 '+activeclass+'">\
				<div class="col-md-11 p-0 my-3 stallbtns">\
					<input type="hidden" name="stallvalidation_'+barnstallname+'_'+barnIndex+'" id="stallvalidation_'+barnstallname+'_'+barnIndex+'">\
					<button class="btn-stall stallbtn_'+barnstallname+'" data-barnIndex="'+barnIndex+'" >Add Stall</button>\
					<button class="btn-stall bulkstallmodal_'+barnstallname+'" data-barnIndex="'+barnIndex+'" data-bs-toggle="modal" data-bs-target="#bulkstallmodal_'+barnstallname+'">Add Bulk Stall</button>\
					<button class="btn-stall barnremovebtn_'+barnstallname+'">Remove Barn and Stall</button>\
				</div>\
			</div>\
		';

		$(barn_append).append(barntab);
		$(stall_append).append(stalltab);
		$(barn_validation).val('1');
		$(barn_validation).valid();

		$(document).find('#barn_'+barnstallname+'_'+barnIndex+'_name').rules("add", {required: true});
		$(document).find('#stallvalidation_'+barnstallname+'_'+barnIndex).rules("add", {required: true});

		if(stall.length > 0){
			$(stall).each(function(i, v){
				stalldata(barnIndex, v)
			});
		}
		
		if(type=='1') stalldata(barnIndex);
		++barnIndex;
	}
	/* END ADD EDIT BARN */
	
	
	/* START ADD EDIT STALL */
	$(document).on('click', '.stallbtn_'+barnstallname, function(e){ 
		e.preventDefault();
		stalldata($(this).attr('data-barnIndex'));
	});
	
	
	function stalldata(barnIndex, result=[])
	{ 
		var stallId       		= result['id'] ? result['id'] : '';
		var charging_flags      = result['charging_id'] ? result['charging_id'] : ''; 
		var stallName     		= result['name'] ? result['name'] : '';
		var stallPrice    		= result['price'] ? result['price'] : '';
		var stallImage    		= result['image'] ? result['image'] : '';
		var stallBulkImage    	= result['bulkimage'] ? result['bulkimage'] : '';
		var block_unblock      	= result['block_unblock'] ? result['block_unblock'] : ''; 
		var checked	= (block_unblock=='1') ? 'checked' : '';
		
		if(stallImage!='' && stallBulkImage==''){
			var stallImages   	= baseurl()+'assets/uploads/stall/'+stallImage;
		}else if(stallBulkImage!=''){
			var stallImages   	= baseurl()+'assets/uploads/temp/'+stallBulkImage;
		}else{
			var stallImages   	= baseurl()+'assets/images/upload.png';
		}
			var charging_flag= '';
			$.each(charging_flagdata, function(i,v){
	        	var selected = i==charging_flags ? 'selected' : '';
	            charging_flag += '<option value='+i+' '+selected+'>'+v+'</option>';
	        })

			var blockunblock = '';
			
			var blockunblock = '<div class="col-md-6 mb-3">\
					<input type="checkbox" id="stall_'+barnstallname+'_'+stallIndex+'_block_unblock" '+checked+' name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][block_unblock]" value="1">block/unblock\
				</div>';
		
		var availability = '<a href="javascript:void(0);" class="dash-stall-remove fs-7 stallremovebtn_'+barnstallname+'" data-barnIndex="'+barnIndex+'"><i class="fas fa-times text-white"></i></a>';
		if($.inArray(stallId, occupied) !== -1)	availability = '<span class="red-box"></span>';
		if($.inArray(stallId, reserved) !== -1)	availability = '<span class="yellow-box"></span>';

		var data='\
		<div class="row mb-2 dash-stall-base">\
			<div class="col-md-6 mb-3">\
			    <select class="form-control" id="stall_'+barnstallname+'_'+stallIndex+'_chargingflag" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][chargingflag]">\
			    '+charging_flag+'\
			    </select>\
			</div>\
			<div class="col-md-6 mb-3">\
				<input type="text" id="stall_'+barnstallname+'_'+stallIndex+'_name" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][name]" class="form-control  fs-7" placeholder="Stall Name" value="'+stallName+'">\
			</div>\
			<div class="col-md-6 mb-3">\
				<input type="text" id="stall_'+barnstallname+'_'+stallIndex+'_price" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][price]" class="form-control fs-7" placeholder="Price" value="'+stallPrice+'">\
			</div>\
			<div class="col-md-3 mb-3">\
				<a href="'+stallImages+'" target="_blank">\
					<img src="'+stallImages+'" class="stall_image_source_'+barnstallname+'_'+stallIndex+'" width="40" height="35">\
				</a>\
				<button class="dash-upload stalluploadimage_'+barnstallname+' fs-7" title="Upload image here">Upload</button>\
				<input type="file" class="stallimage stall_image_file_'+barnstallname+'_'+stallIndex+'" style="display:none;">\
				<span class="stall_image_msg'+stallIndex+'"></span>\
				<input type="hidden" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][image]" class="stall_image_input_'+barnstallname+'_'+stallIndex+'" value="'+stallImage+'">\
			</div>\
			'+blockunblock+'\
			<div class="col-md-1 mb-3 delete">\
				<input type="hidden" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][id]" value="'+stallId+'" class="stall_id">\
				<input type="hidden" name="'+barnstallname+'['+barnIndex+'][stall]['+stallIndex+'][status]" value="1">\
				'+availability+'\
			</div>\
		</div>\
		';
		
		$(document).find('#stallvalidation_'+barnstallname+'_'+barnIndex).val('1');
		$(document).find('#stallvalidation_'+barnstallname+'_'+barnIndex).valid();
		
		$(document).find('#tabtarget_'+barnstallname+'_'+barnIndex).find('.stallbtns').before(data); 

		fileupload(['.stall_image_file_'+barnstallname+'_'+stallIndex], ['.stall_image_input_'+barnstallname+'_'+stallIndex, '.stall_image_source_'+barnstallname+'_'+stallIndex, '.stall_image_msg_'+barnstallname+'_'+stallIndex]);
		
		$(document).find('#stall_'+barnstallname+'_'+stallIndex+'_name').rules("add", {required: true});
		$(document).find('#stall_'+barnstallname+'_'+stallIndex+'_price').rules("add", {required: true});
		++stallIndex;
	}
	/* END ADD EDIT STALL */
	
	
	/* START BARN REMOVE */
	$(document).on('click', '.barnremovebtn_'+barnstallname, function(e){
		e.preventDefault();
		var stalltabparent = $(this).parent().parent();
		$(document).find('[data-bs-target="#'+stalltabparent.attr('id')+'"]').parent().remove();
		stalltabparent.remove();
		
		if($(document).find(barn_append+' li').length){
			$(document).find(barn_append+' li:first a').addClass('active');
			$(document).find(stall_append+' div:first').addClass('active');
		}else{
			$(barn_validation).val('');
			$(barn_validation).valid();
		}
	});
	/* END BARN REMOVE */

	
	/* START STALL REMOVE */
	$(document).on('click', '.stallremovebtn_'+barnstallname, function(e){
		e.preventDefault();
		var stallparent = $(this).parent().parent().parent();
		var bi = $(this).attr('data-barnIndex')
		$(this).parent().parent().remove();
		
		if(stallparent.find('.dash-stall-base').length==0){
			$(document).find('#stallvalidation_'+barnstallname+'_'+bi).val('');
			$(document).find('#stallvalidation_'+barnstallname+'_'+bi).valid();
		}
	})
	/* END STALL REMOVE */
	
	
	/* START BARN NAME TEXTBOX */
	$(document).on('click', barn_append+' li a.active', function(e){ 
		e.preventDefault();
		barntext();
	});
	
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
			$(document).find(barn_append+' li').each(function(){
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
	/* END BARN NAME TEXTBOX */
	
	
	/* START STALL IMAGE CLICK */
	$(document).on('click','.stalluploadimage_'+barnstallname, function (e) {
		e.preventDefault();
		$(this).parent().find('.stallimage').click();
	});
	/* END STALL IMAGE CLICK */
	
	
	/* START BULK UPLOAD */
	$(document).on('click','.bulkbtn_'+barnstallname, function () {
		$(this).parent().find('.bulkfile_'+barnstallname).click();
	});

	$(document).on('change', '.bulkfile_'+barnstallname, function () {
		var _this = $(this);
  		var formdata = new FormData();
		formdata.append('file', $(this)[0].files[0]); 
		
		ajax(
			baseurl()+'myaccount/events/importbarnstall', 
			formdata, 
			{
				contenttype : 1,
				processdata : 1,
				success: function(result) {
					$(result).each(function(i, v){
						barndata(v)
					})
					
					_this.val('');
				}
			}
		);
	});
	/* END BULK UPLOAD */
	
	/* START STALL BULK UPLOAD */
	var charging_flagmodal ='';
		$.each(charging_flagdata, function(i,v){ 
        	//var selected = i==1 ? 'selected' : '';
            charging_flagmodal += '<option value='+i+'>'+v+'</option>';
        })

	var modal = '<div class="modal fade" id="bulkstallmodal_'+barnstallname+'" role="dialog">\
					<div class="modal-dialog">\
						<div class="modal-content">\
							<div class="modal-header">\
								<h4 class="modal-title">Stall</h4>\
								<button type="button" class="close" data-bs-dismiss="modal">&times;</button>\
							</div>\
							<div class="modal-body">\
								<div class="col-md-12 my-2">\
									<div class="form-group">\
										<label>Stall Rate</label>\
										<select class="form-control stall_charging_id_'+barnstallname+'">\
										'+charging_flagmodal+'\
										</select>\
									</div>\
								</div>\
								<div class="col-md-12 my-2">\
									<div class="form-group">\
										<label>Stall Name</label>\
										<input type="text" class="form-control stall_name_'+barnstallname+'" placeholder="Enter Your Stall Name">\
									</div>\
								</div>\
								<div class="col-md-12 my-2">\
									<div class="form-group">\
										<label>Price</label>\
										<input type="number" class="form-control stall_price_'+barnstallname+'" placeholder="Enter Price">\
									</div>\
								</div>\
								<div class="col-md-6 my-2">\
									<div class="form-group">\
										<label>Stall Image</label>\
										<div>\
											<a href="" target="_blank">\
												<img src="" class="stall_source_'+barnstallname+'" width="100">\
											</a>\
										</div>\
										<input type="file" class="stall_file_'+barnstallname+'">\
										<input type="hidden" class="stall_input_'+barnstallname+'" value="">\
									</div>\
								</div>	\
								<div class="col-md-12 my-2">\
									<div class="form-group">\
										<label>Total Number of Stalls</label>\
										<input type="number" class="form-control stall_total_'+barnstallname+'" placeholder="Enter Total Number of Stalls" min="1" required>\
									</div>\
								</div>\
								<div class="col-md-12 my-2">\
									<div class="form-group">\
										<label>First Stall Number</label>\
										<input type="text" class="form-control stall_number_'+barnstallname+'" placeholder="Enter First Stall Number" min="1" required>\
									</div>\
								</div>\
							</div>\
							<div class="modal-footer">\
								<input type="hidden" class="barnIndexValue_'+barnstallname+'" value="0">\
								<button type="button" class="btn btn-info bulkstallbtn_'+barnstallname+'">Submit</button>\
								<button type="button" class="btn btn-info" data-bs-dismiss="modal">Close</button>\
							</div>\
						</div>\
					</div>\
				</div>';
	
	$('body').append(modal);	
	fileupload(['.stall_file_'+barnstallname], ['.stall_input_'+barnstallname, '.stall_source_'+barnstallname]);
	
	$('#bulkstallmodal_'+barnstallname).on('shown.bs.modal', function (e) { 
		$('.stall_name_'+barnstallname+', .stall_price_'+barnstallname+', .stall_input_'+barnstallname+', .stall_file_'+barnstallname+', .stall_total_'+barnstallname+', .stall_number_'+barnstallname).val('');
		$('.stall_source_'+barnstallname).attr('src', baseurl()+'assets/images/upload.png');
		$('.stall_source_'+barnstallname).parent().attr('href', baseurl()+'assets/images/upload.png');
	})

	$(document).on('click', '.bulkstallmodal_'+barnstallname, function (e) { 
		e.preventDefault();
		$('.barnIndexValue_'+barnstallname).val($(this).attr('data-barnIndex'));
	});

	$(document).on('click', '.bulkstallbtn_'+barnstallname, function(e){ 
		e.preventDefault();
		if($('.stall_total_'+barnstallname+'').val()==''){
			$('.stall_total_'+barnstallname+'').focus();
			return false;
		}

		var name          	= $('.stall_name_'+barnstallname).val();
		var price        	= $('.stall_price_'+barnstallname).val();
		var charging_id    = $('.stall_charging_id_'+barnstallname).val(); 
		var image        	= $('.stall_image_'+barnstallname).val();
		var stalltotal    	= $('.stall_total_'+barnstallname).val();
		var stallnumber 	= $('.stall_number_'+barnstallname).val(); 
		var barnIndexValue	= $('.barnIndexValue_'+barnstallname).val();

		for(var i=0; i<stalltotal; i++){ 
			var names = stallnumber!='' ? name+' '+stallnumber : name; 
			stalldata(barnIndexValue, {name:names,charging_id: charging_id,price:price,status:1,bulkimage:image});
			if(stallnumber!='') stallnumber++ ;
		}

		$('#bulkstallmodal_'+barnstallname).modal('hide');
	});
	/* END STALL BULK UPLOAD */
}


function products(productsname, productsitem=[], productsresult=[]){ 
	var selector_btn1 	= productsitem[0][0];
	var product_append 	= productsitem[1][0];
	var productIndex 	= productsitem[2][0];

	var productsresult 	= productsresult[0] ? productsresult[0] : [];

	if(productsresult.length > 0){
		$(productsresult).each(function(i, v){
			productsdata(v);
		});
	}
	
	/* START ADD EDIT PRODUCTS */
	$(selector_btn1).click(function(e){
		e.preventDefault();
		productsdata();
	});
	
	function productsdata(result=[])
	{  
		var productId       		= result['id'] ? result['id'] : '';
		var productName     		= result['name'] ? result['name'] : ''; 
		var productQuantity    		= result['quantity'] ? result['quantity'] : '';
		var productPrice    		= result['price'] ? result['price'] : '';
		
		var soldoutmsg = '';
		if(productQuantity==0){
			soldoutmsg = 'sold out';
		}
		
		var data='\
		<div class="row mb-2 dash-stall-base">\
			<div class="col-md-6 mb-4">\
				<input type="text" id="product_'+productsname+'_'+productIndex+'_name" name="'+productsname+'['+productIndex+'][name]" class="form-control fs-7" placeholder="Name" value="'+productName+'">\
				<p class="tagline">'+soldoutmsg+'</p>\
			</div>\
			<div class="col-md-2 mb-4">\
				<input type="text" id="product_'+productsname+'_'+productIndex+'_quantity" name="'+productsname+'['+productIndex+'][quantity]" class="form-control fs-7" placeholder="Quantity" value="'+productQuantity+'">\
			</div>\
			<div class="col-md-2 mb-4">\
				<input type="text" id="product_'+productsname+'_'+productIndex+'_price" name="'+productsname+'['+productIndex+'][price]" class="form-control fs-7" placeholder="Price" value="'+productPrice+'">\
			</div>\
			<div class="col-md-1 mb-4 delete">\
				<a href="javascript:void(0);" class="dash-stall-remove fs-7 productremovebtn_'+productsname+'"><i class="fas fa-times text-white"></i></a>\
				<input type="hidden" name="'+productsname+'['+productIndex+'][id]" value="'+productId+'">\
				<input type="hidden" name="'+productsname+'['+productIndex+'][status]" value="1">\
			</div>\
		</div>\
		';
		
		$(document).find('#product_'+productsname+'_'+productIndex+'_name').rules("add", {required: true});
		$(document).find('#product_'+productsname+'_'+productIndex+'_quantity').rules("add", {required: true});
		$(document).find('#product_'+productsname+'_'+productIndex+'_price').rules("add", {required: true});
		++productIndex;
		
		$(product_append).append(data);
	}
	/* END ADD EDIT PRODUCTS */
	
	
	/* START PRODUCTS REMOVE */
	$(document).on('click', '.productremovebtn_'+productsname, function(e){
		e.preventDefault();
		$(this).parent().parent().remove();
	})
	/* END PRODUCTS REMOVE */
}