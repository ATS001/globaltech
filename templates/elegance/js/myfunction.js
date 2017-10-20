// Ajax load content


function ajax_loader($url,$data,$redirect){
	//alert($url)
	bootbox.process({
	    		    message:'Working',
	            });
	$('#content').empty();
	$('#content').html('');	
	
	$.ajax({
		cache: false,
		url  : '?_tsk='+$url+'&ajax=1',
		type : 'POST',
		data : $data+'&cor=1',
		dataType:"html",
		success: function(data){
			bootbox.hideAll();
			var data_arry = data.split("#");
			if(data_arry[0]==3){



				ajax_loadmessage(data_arry[1],'nok',5000)
				$('#content').empty();

				if(typeof $redirect !== 'undefined'){
					ajax_loader($redirect,'');

				}else{
					window.setTimeout( function(){
					window.location = "./";
				    }, 5000 );

				}
			}else if(data_arry[0]==4){
				bootbox.process({
	    		    message:'Working',
	            });
	            $('#main-container').empty();
	            $('#main-container').html('');
				ajax_loadmessage(data_arry[1],'nok',5000)
				window.setTimeout( function(){
					    window.location = "./";
				        }, 5000 );
				

			}else{
				
				//alert(data);

                var data_result =  data.split("#||#");
                $("#treeapp").html(data_result[0]);
                //check if is data for tigger then load message
                var data_mes = data_result[1].split('#')
                if(data_mes[0]==3)
                {
                	ajax_loadmessage(data_mes[1],'nok',5000)
				    $('#content').empty();

				    if(typeof $redirect !== 'undefined'){
					    ajax_loader($redirect,'');

				    }else{
					    window.setTimeout( function(){
					    window.location = "./";
				        }, 5000 );
					}    

				
                }else{

                	$('#content').html(data_result[1]);
                	
                }      
			 
			}
		},
		timeout: 30000,
		error: function(){
			ajax_loadmessage('Délai non attendue','nok',5000)
		}

        // will fire when timeout is reached
     
	});

}


//AJAX load bootbox content
function ajax_bbox_loader($url, $data, $titre, $width, $data_table ){
	//alert($url)
	
	$.ajax({
		cache: false,
		url  : '?_tsk='+$url+'&ajax=1',
		type : 'POST',
		data : $data,
		dataType:"html",
		success: function(data){
			
			var data_arry = data.split("#");
			if(data_arry[0]==3){
				ajax_loadmessage(data_arry[1],'nok',5000)
			}else if(data_arry[0]==4){
				bootbox.process({
					message:'Working',
				});
				$('#main-container').empty();
				$('#main-container').html('');
				ajax_loadmessage(data_arry[1],'nok',5000)
				window.setTimeout( function(){
					window.location = "./";
				}, 5000 );
				

			
		}else{
			var dialog = bootbox.dialog({

				message: data,
				title: $titre,
				size: $width !== undefined? $width : '',
				buttons: 			
				{						
					"click" :
					{
						"label" : "Enregistrer",
						"className" : "btn-sm btn-primary send_modal",
						"callback": function(e) {
						
							return false;
						}
					},
					"cancel" :
					{
						"label" : "Annuler",
						"className" : "btn-sm btn-inverse close_modal",
						"callback": function (e) {
							return true;
						}
					} 

				}
			});

			$('.bootbox-body').ace_scroll({
				size: 400
			});


		}
	},
	timeout: 30000,
	error: function(){
		ajax_loadmessage('Délai non attendue','nok',5000)
	}

        // will fire when timeout is reached

    });
    return true;

}

function bb_add_pic($url,$titre,$width){

	$.ajax({
		cache: false,
		url  : '?_tsk='+$url+'&ajax=1',
		type : 'POST',
		data : '',
		dataType:"html",
		success: function(data){
			
			var data_arry = data.split("#");
			if(data_arry[0]==3){

				ajax_loadmessage(data_arry[1],'nok',5000)
			}else{
				
                	bootbox.dialog({
                		
                        message: data,


                        title: $titre,
                        size: $width !== undefined? $width : '',
                        buttons: 			
						{
							
							 
							"cancel" :
							{
							 label: "Annuler",
							 className: "btn-sm",
						    },
							"click" :
							{
								"label" : "Enregistrer",
								"className" : "btn-sm btn-primary",
								"callback": function(e) {
									$pic_link = $("#photo-id").val();
									$pic_titl = $("#pic_titl").val();
									

									if(!$pic_link || !$pic_titl){
										ajax_loadmessage('Il faut remplire les champs','nok',5000);
										return false;
									}else{
									
									$bloc_pic = '<li><a href="#" class="show_pic" rel="'+$pic_link+'"><img width="150" height="150" alt="150x150" src="'+$pic_link+'" /><div class="text"><div class="inner"><input name="photo_id[]" value="'+$pic_link+'" type="hidden"><input  name="photo_titl[]" value="'+$pic_titl+'" type="hidden">'+$pic_titl+'</div></div></a><div class="tools tools-bottom"><a class="del_pic" href="#"><i class="ace-icon fa fa-times red"></i></a></div></li>';
									$('.ace-thumbnails').append($bloc_pic);

									return true;
									}
									
								}
							}, 
							
							
							
						},

                    });


                    
                
			 
			}
		},
		timeout: 30000,
		error: function(){
			ajax_loadmessage('Délai non attendue','nok',5000)
		}

        // will fire when timeout is reached
     
	});
}

$('html').click(function() {
	if ($('#gritter-notice-wrapper').length) {
		 $('#gritter-notice-wrapper').remove();
		 if(!$('.modal-body'.length)){
		 	bootbox.hideAll();
		  }
		 
	};

   
});


$('body').on('click', '.this_url', function() {
	 $('#gritter-notice-wrapper').remove();//remove message box

	 var $url = $(this).attr('rel');
	 var $data = $(this).attr('data') != ""?$(this).attr('data'):"";
	 var $redirect = $(this).attr('redi') != ""?$(this).attr('redi'):"";
	 ajax_loader($url,$data,$redirect);
	 if($(this).parent('li').attr('left_menu') == 1){
	 	
        //
	 	$(".active").removeClass("active");
	 	$(this).parent("li").addClass("active");
        

	 };
	 

});



$('body').on('click', '.this_exec', function() {
	 $('#gritter-notice-wrapper').remove();//remove message box
	 

	 var $url = $(this).attr('rel');
	 var $data = $(this).attr('data') != ""?$(this).attr('data'):"";
	 var $the_table = $(this).closest('table').attr('id');
	 //var $row_selected = $(this).closest('tr');
	 
	 exec_ajax($url, $data, $confirm = 1, '', $the_table);

});


function do_ajax($url, $data , $the_table){
	bootbox.process({
	    		    message:'Working',
	            });
	$.ajax({
                url: '?_tsk='+$url+'&ajax=1',
                type: 'POST',
                data: $data,
                dataType: 'html',
                success: function(data,e) {

                	var data_arry = data.split("#");
                	if(data_arry[0] == 1) {

        				ajax_loadmessage(data_arry[1],'ok',5000);
        				
        				 var table = $('#'+$the_table).DataTable();
                         table.row('.selected').remove().draw( false );
        				 bootbox.hideAll();
        				
        			}else{
        				
        				ajax_loadmessage(data_arry[1],'nok',50000);
        				bootbox.hideAll();
        			}
        			
                  
                  	          
                },
                timeout: 30000,
		error: function(){
			ajax_loadmessage('Délai non attendue','nok',5000)
		}
    });
}

//Exec function  on backdoor calling do_ajax
function exec_ajax($url, $data, $confirm, $message_confirm , $the_table){

	var $message = typeof $message_confirm !== 'undefined' ? 'Veuillez confirmer !' : $message_confirm;
   
	if($confirm == 1){
		  bootbox.confirm($message, function(result) {
            if (result) {
            	do_ajax($url, $data, $the_table); 
                

             }
          });
	}else{
		do_ajax($url, $data, $the_table);
	}
    


	return true;
	
}


// Load Message

function ajax_loadmessage($core, $class, $time) {
	$.gritter.removeAll();
	
	
	$time = typeof $time !== 'undefined' ? $time : 5000;	

	$laclass = $class == 'ok'?'gritter-success':'gritter-error';
	$titre = $class == 'ok'?'Opération  réussie':'Erreur Opération';
	
	window.setTimeout( function(){
		$.gritter.add({
			title: $titre,
			text:  $core,
			class_name: $laclass + '  gritter-center gritter-light',
			time:  $time,
		});
	}, 10 );
	
	
	return false;


}




// File Uploder


// File Uploder


function fliupld(lechamps, asize,  type, value, edit) {



    var file_input = $('#' + lechamps);
	var upload_in_progress = false;
	var $allowExt = $allowMime = $value = $value_input = null;
	if(type === undefined){
						$allowExt =  null;
					    $allowMime = null; 

	}else if(type == 'image'){
						$allowExt =  ["jpeg", "jpg", "png", "gif"];
					    $allowMime =  ["image/jpg", "image/jpeg", "image/png", "image/gif"];

	}else if(type == 'pdf'){
						$allowExt =  ["pdf"];
					    $allowMime =  ["application/pdf"];

	}else if(type == 'doc'){
						$allowExt =  ["pdf", "doc", "docx", "xls", "xlsx", "txt"];
					    $allowMime =  [
					                   "application/pdf", "application/msword",
					                   "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
					                   "application/vnd.ms-excel",
					                   "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
					                   "text/plain",
					                  ];
	}

	
	$value =  value === undefined ? "" : value;
	
	if($value != "" && edit == 1){
         $value_input = file_input.attr('value');
        
	}
	

				file_input.ace_file_input({
					//
					//Style:           'well',
					//btn_choose:      'Choisir un fichier',
					//btn_change:      null,
					droppable:         true,
					value_field:       $value,
					value_input:       $value_input,
					thumbnail:         'large',
					//container_width: 'container_width class',
					maxSize:           asize,//bytes
					allowExt:          $allowExt, 
					allowMime:         $allowMime, 
					type_file:         type,
					is_edit:           edit,
					
					

					before_remove: function() {
						if(upload_in_progress)
							return false;//if we are in the middle of uploading a file, don't allow resetting file input
						return true;
					},

					preview_error: function(filename , code) {
						//code = 1 means file load error
						//code = 2 image load error (possibly file is not an image)
						//code = 3 preview failed
					}
				})
				file_input.on('file.error.ace', function(ev, info) {

					if(info.error_count['ext'] || info.error_count['mime']){
						ajax_loadmessage('Le type de fichier est non autorisé! <br><b>'+ $allowExt,'nok');
						return;
					} 
					if(info.error_count['size']){
						ajax_loadmessage('La taille de fichier ne doit pas dépasser ' + parseInt(asize / 781) +' Kb !','nok');
						return;
					} 
					
					//you can reset previous selection on error
					//ev.preventDefault();
					
					file_input.ace_file_input('reset_input');
				});
				
				
				var ie_timeout = null;//a time for old browsers uploading via iframe
				
				file_input.on('change', function(e) {
					e.preventDefault();
				
					var files = file_input.data('ace_input_files');
					if( !files || files.length == 0 ) return false;//no files selected
										
					var deferred ;
					if( "FormData" in window ) {
						
						formData_object = new FormData();//create empty FormData object
						
						var field_name = file_input.attr('name');
							//for fields with "multiple" file support, field name should be something like `myfile[]`

							var files = $(this).data('ace_input_files');
							if(files && files.length > 0) {
								for(var f = 0; f < files.length; f++) {
									formData_object.append(field_name,  files[f]);
									formData_object.append("fileID", lechamps);
									formData_object.append("upld", 1);
								}
							}
						//});
	

						upload_in_progress = true;

						file_input.ace_file_input('loading', true);
												
						deferred = $.ajax({
							        url: './?_tsk=upload&ajax=1',
							       type: 'POST',
							processData: false,//important
							contentType: false,//important
							   dataType: 'html',
							    
							       data: formData_object ,
							
						       
						})

					}


					


					////////////////////////////
					//deferred callbacks, triggered by both ajax and iframe solution
					deferred
					.done(function(result) {//success
						//alert(result);
						//format of `result` is optional and sent by server
						var data_arry = result.split("#");
					if(data_arry[0]==1){
						file_input.ace_file_input('disable', true);
						

						$('#'+lechamps+'-id').attr('value', data_arry[1]);




						
						
					}else{
						ajax_loadmessage(data_arry[1],'nok');
						file_input.ace_file_input('reset_input', true);
							
					}
						
					})
					.fail(function(result) {//failure
						ajax_loadmessage('Problème Envoi','nok');
						//alert("There was an error ");
					})
					.always(function() {//called on both success and failure
						if(ie_timeout) clearTimeout(ie_timeout)
						ie_timeout = null;
						upload_in_progress = false;
						file_input.ace_file_input('loading', false);
					});

					deferred.promise();
				});


}


// Melsiouns Function
$(function () {
  function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
// Les Masque
  //called when key is pressed in textbox
  $('body').on('keypress keyup change', '.is-number', function(e) {

  	if (e.which != 8 && e.which != 0  && (e.which < 48 || e.which > 57)) {
  		return false;
  	}
  	
  });
  	
  $("body").bind("DOMNodeInserted", function() {
   //$(this).find('.is-date').mask('99-99-9999');

   
});
    // $('.is-date').mask('99-99-9999');


    



});



// Remplir Une zone on select Input Select
function load_onselect(field){
	//alert($(field).val());
	 
	if($(field).val()!=""){
		var $zone = $(field).closest('.form-group'); 
		//$("<p>Test</p>").appendTo($zone);
		$.ajax({

			url: "./?_tsk=loadenselect&ajax=1&tb=1",
			type: "POST",
			data: "tab=1&id=" + $(field).val(),
			dataType: 'html',
			success: function(data){
				
				$(data).appendTo($zone);   
			} 
		});
	}else{
		$("#"+zone).empty();
	}    

}
//End Remplir Une zone on select Input Select

//Scroll Left bar
// scrollables
/*$('.slim-scroll').each(function () {
	var $this = $(this);
	$this.slimScroll({
		height: $this.data('height') || 550,
						//railVisible:true
					});
});	*/	
//End Scroll left bat
//Colorbox caller
$(document).ready(function(){
				//Examples of how to assign the Colorbox event to elements
				$('body').on('click', '.iframe_pdf', function() {
					$.ajax({
		                type: 'POST',
		                url: './?_tsk=shopdf&ajax=1',
		                data: 'doc='+$(this).attr('rel'),
		                timeout: 3000,
		                success: function(result) {
			                var data_arry = result.split("#");
			
					        if(data_arry[0]==1){
					        	//detrmine is for image or pdf
					        	var $ext_array = data_arry[1].split(".");
					        	if($ext_array[2] == "pdf"){
					        		$.colorbox({iframe:true, width:"80%", height:"90%",href:data_arry[1]});

					        	}else{
					        		$.colorbox({image:true,href:data_arry[1]});

					        	}
					        	
						        return true;
                            }else{
						        ajax_loadmessage(data_arry[1],'nok');
						        return false;
					        }
			            },
		                error: function() {
			                ajax_loadmessage('Affichage Impossible #AJAX','nok',3000);
			                return false;
		                }
	                });
											
					
				});
				// Call report script exec template PDF
				$('body').on('click', '.report_tplt', function() {

					$.ajax({
		                type: 'POST',
		                url: './?_tsk=report&ajax=1',
		                data: $(this).attr('rel')+'&'+$(this).attr('data'),
		                timeout: 3000,
		                dataType:'JSON',
		                success: function(data) {
			                	
					        if(data['error'] == 'error'){
					        	ajax_loadmessage('Erreur chargement Template JS','nok',3000);
					        	return false;
					        }else{
					        	$.colorbox({iframe:true, width:"80%", height:"90%",href:data['file']});
					        	return true;
					        }
					        
			            },
		                error: function() {
			                ajax_loadmessage('Affichage Impossible #AJAX','nok',3000);
			                return false;
		                }
	                });
											
					
				});

				//$(".iframe_pdf").colorbox({iframe:true, width:"80%", height:"90%",href:data});
				$('body').on('click', '.show_pic', function() {
					var $link_pic = $(this).attr('rel');
					$(".show_pic").colorbox({image:true,href:$link_pic});

				});
				$('body').on('click', '.this_map', function() {
					
					var $data = $(this).attr('data');
                    $('body').fullScreen(true);
                     setTimeout(function() { $.colorbox({iframe:true, map:true, width:"100%", height:"100%",href:"./map/?"+$data }) },500)
   		        });

   		        $('body').on('click', '.this_modal', function() {
   		        	var $link  = $(this).attr('rel');
   		        	var $titre = $(this).attr('data_titre'); 
   		        	var $data  = $(this).attr('data'); 

					ajax_bbox_loader($link, $data, $titre, 'large')
   		        });
   		        $('body').on('click', '.del_pic', function() {
   		        	var $tester = true;
   		        	if($(this).attr('rel') == null){
   		        		$(this).closest('li').remove();
   		        	}else{
   		        		$.ajax({
							type: 'POST',
							url: './?_tsk=upload&ajax=1',
							data: '&del=1&f='+$(this).attr('rel'),


							timeout: 3000,
							success: function(data) {

								var data_arry = data.split("#");

								if(data_arry[0]==1){
									//this.reset_input;
									//ajax_loadmessage(data_arry[1],'ok');
									$tester = true;
									

									
								}else{
						            //ajax_loadmessage(data_arry[1],'ok');	
						            ajax_loadmessage(data_arry[1],'nok');
						            $tester = false;						         
					            }
				            },
				            error: function() {
					            ajax_loadmessage('Suppression Impossible #AJAX','nok',3000);
					            $tester = false;
				            }
			            });
			            if($tester == true){
			            	$(this).closest('li').remove();
			            }
   		        	}
   		        	
   		        	
   		        	   

   		        });
//Call dashbord first time
});
function call_colorBox(params) {
	$.colorbox({iframe:true, width:"80%", height:"90%",href:"?_tsk=shopdf&ajax=1&doc="+params});
}//End colorboxcaller				


$(document).ready(function(){
	
//alert(firsttime);
if(typeof  firsttime !== 'undefined' &&  firsttime==1)
{
	bootbox.process({
	    		    message:'Working',
	            });
	setTimeout(function() { ajax_loader('dbd') },2000)		
    firsttime == 2;	
}

});

//Apend button action
function  append_drop_menu($url, $id, $btn){
	//Fisrt empty button
	$($btn+' ul').remove();
	$.ajax({
		type: 'POST',
		url: '?_tsk='+$url+'&ajax=1',
		data: 'id='+ $id + '&act=1',		
		timeout: 3000,
		success: function(data) {
			var data_arry = data.split("#");
			if(data_arry[0]==3){

				ajax_loadmessage(data_arry[1],'nok',5000)
				$('#content').empty();

				if(typeof $redirect !== 'undefined'){
					ajax_loader($redirect,'');

				}else{
					window.setTimeout( function(){
					window.location = "./";
				}, 5000 );

				}
			}else if(data_arry[0]==4){
				bootbox.process({
	    		    message:'Working',
	            });
	            $('#main-container').empty();
	            $('#main-container').html('');
				ajax_loadmessage(data_arry[1],'nok',5000)
				window.setTimeout( function(){
					    window.location = "./";
				        }, 5000 );
				

			
				

			}else{
				$($btn).append(data);
			}
			
			
			
		},
		error: function() {
			ajax_loadmessage('Action indisponible','nok',3000);
		}
	});    
}
