function baseurl(){
	var base = window.location;

	if(base.host=='localhost'){
		return base.protocol + "//" + base.host + "/nantha/ezstall/";
	}else{
		return base.protocol + "//" + base.host + "/ezstall/";
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
