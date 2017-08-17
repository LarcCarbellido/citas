$(document).ready(function() {
	
	$(".btn-save-photos").click(function(e) {
		e.preventDefault();
	
		var photo_array = [];
	
		$(".photo_line").each(function() {
		
			var photo = $(this).find(".photo_url").attr("data-id");
	    	var title = $(this).find(".title_img").val();
	    	var tags = $(this).find(".tags_img").val();
		
			// Create the info_array
	    	var info_array = {
	        	photo_id : photo,
	        	title : title,
	    	};
	    	
	    	// Add the info_array to the global array
	    	photo_array.push(info_array);
		});
		
		$.ajax({ 
            url: base_url + "photo/save_photos_infos",
            type: "POST",
            dataType: "json",
            data: {photo_array: photo_array},
            success: function(data) {
				window.location.href = base_url + "user/settings?action=photos_added";
            }
        });   
	});
	
		
	$(".btn-back").click(function(e) {
		e.preventDefault();
		
		if(history.length === 1){
            window.location = base_url;
        } else {
            history.back();
        }
	});
	
	var ua = navigator.userAgent.toLowerCase();
	var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
	function getAndroidVersion(ua) {
	    var ua = ua || navigator.userAgent; 
	    var match = ua.match(/Android\s([0-9\.]*)/);
	    return match ? match[1] : false;
	};
	if(isAndroid) {
		var str = 'Mozilla/5.0 (Linux; U; Android 2.2.1; en-us; device Build/FRG83) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Safari/533.1';
	
		var version = getAndroidVersion();

		if(parseFloat(version) < 5.0) {
		
			$(".strikeyhr").hide();
			$(".photo_add_android").hide();
		}
	}

	var ul = $('#upload ul');

    $('#drop a').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        $(this).parent().find('input').click();
    });

    // Initialize the jQuery File Upload plugin
    $('#upload').fileupload({

        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),
        limitMultiFileUploads: 5,
        sequentialUploads: true,

        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {
        				
			if(data.files[0]['type'] != 'image/png' && data.files[0]['type'] != 'image/jpg' && data.files[0]['type'] != 'image/jpeg'){ alert("Whoops! You can only add photos with the .PNG or .JPG extension."); return; }
			
            var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span></li>');
               
			if(data.files[0].size > 7000000)
			{
				alert(photo_weight_error_str);
				return;
			}

            // Append the file name and file size
            tpl.find('p').text(data.files[0].name)
                         .append('<i>' + formatFileSize(data.files[0].size) + '</i>');

            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);

            // Initialize the knob plugin
            tpl.find('input').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){

                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                });

            });
            
            var t = "";
            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit().success(function(result, textStatus, jqXHR){
            	
            	if(result.status == 1)
            	{
                        	            	
	            	$(".no-photo-yet").hide();
			            	
	            	var nb_photo_line = $(".photo_line").length+1;
	            	
	            	// We create the table of images for the informations (title, tags...)
	            	var photo_block = "<tr class='photo_line' data-cpt='" + nb_photo_line + "'>";
	            	photo_block += '<td style="width:35%;"><img class="col-md-12 photo_url" data-id="' + result.id + '" src="' + base_url + result.photo + '" class="img-responsive" /></td>';
	            	photo_block += '<td class="infos_photo">';
	            	photo_block += '<div class="form-group form-title">';
	            	photo_block += '<label for="inputTitle">' + description_str + ' :</label>';
	            	photo_block += '<textarea class="form-control title_img" id="inputTitle" placeholder="' + description_placeholder_str + '"></textarea>';
					photo_block += '</div>';
					photo_block += '</td>';
					photo_block += '</tr>';
	            	
	            	$(".img_list").append(photo_block);
	            		
	            	$(".img_list").fadeIn();
	            	$(".buttons_send_photos").fadeIn();
            	
            	} else if(result.status == 500) {
	            	alert(demo_mode_str);
            	} else if(result.status == 998) {
	            	alert(upload_limit_reached_str);
            	}
				
			});
			
        },

        progress: function(e, data){

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if(progress == 100){
                data.context.removeClass('working');
            }
        },

        fail:function(e, data){
            // Something has gone wrong!
            data.context.addClass('error');
        }

    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }
		
});