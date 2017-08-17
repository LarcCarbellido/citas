<?php
class photo extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
    }
    
    function report()
    {
	    $this->load->model('user_model', 'userManager');
	    $this->load->model("site_model");
	    
    	$settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $user_lng = $this->userManager->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}
	    
	    $this->lang->load('user_lang', $lng_opt);
	    
    	if(!$this->session->userdata("user_id")) {	
	    	// Not logged in
    		$result["result"] = 999;
    		$result["error_msg"] = $this->lang->line("not_logged_in_error");
    	} else {
    	
    	 	$this->load->model("photo_model");
		    
    		$user_id = $this->session->userdata("user_id");
    		$photo_id = $this->input->post('photo_id');
    		
    		if($this->photo_model->user_owns_photo($user_id, $photo_id)) {
	    		$result["result"] = 998;
	    		$result["error_msg"] = $this->lang->line("cant_report_own_photo");
    		} else {	    	        	    
	    	    $this->userManager->report($this->session->userdata("user_id"), $photo_id, 1);
	    	    $result["result"] = 1;
    	    }
    	}
    
	    header('Content-type: application/json;');
		echo json_encode($result);
    }
    
    function post_comment()
    {
	    $this->load->model('user_model', 'userManager');
	    $this->load->model("site_model");
	    
    	$settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $user_lng = $this->userManager->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}
	    
	    $this->lang->load('user_lang', $lng_opt);
	    $text = strip_tags($this->input->post("text"));
		$pic_id = $this->input->post("pic_id");
		
		$this->load->model('photo_model');
		
		$return = array();
		
		$return["logged_str"] = $this->lang->line("must_be_logged_comment");
		$return["empty_comment_str"] = $this->lang->line("empty_comment");
		$return["photo_removed_str"] = $this->lang->line("photo_removed_comment");
		$return["logged_str"] = $this->lang->line("must_be_logged_comment");
		$return["this_user"] = $this->session->userdata('user_id');
		
		if($this->session->userdata('user_id') == null)
		{
			$return["result"] = 999;
		} else if(empty($text)) {
			$return["result"] = 998;
		} else {
		
			if($this->session->userdata("user_firstform") == 0) {
		    	$return["result"] = 997;
	    	} else {  
		
				// Add the comment
				$comment = $this->photo_model->add_comment($pic_id, $this->session->userdata('user_id'), $text);
				
				$to_user = $this->photo_model->get($pic_id)->result_array();
				
				// Send a notification
				/*
				if($to_user[0]->uid != $this->session->userdata('user_id')) {
				
					$this->load->model('notification_model', 'notifModel');
					
					$this->notifModel->add($this->session->userdata('user_id'), $to_user[0]->uid, "<strong>" . $this->session->userdata('user_username') . "</strong> has commented your photo.", "USER", base_url("photo/show/" . $pic_id));
				}
				*/
				
				// Add the action
				$this->load->model('action_model', 'actionModel');
				
				if($to_user[0]["uid"] == $this->session->userdata('user_id'))
				{
					
					if($to_user[0]["gender"] == 0)
						$comment_str = sprintf($this->lang->line("action_photo_his_own_comment"), base_url("user/profile/" . $this->session->userdata('user_id')), $this->session->userdata('user_username'));
					else
						$comment_str = sprintf($this->lang->line("action_photo_her_own_comment"), base_url("user/profile/" . $this->session->userdata('user_id')), $this->session->userdata('user_username'));
				}
				else
					$comment_str = sprintf($this->lang->line("action_photo_comment"), base_url("user/profile/" . $this->session->userdata('user_id')), $this->session->userdata('user_username'), base_url("user/profile/" . $to_user[0]["uid"]), $to_user[0]["username"]);
					    	
			    $this->actionModel->add($to_user[0]["uid"], $this->session->userdata('user_id'), $comment_str, base_url("photo/show/" . $pic_id), "fa-comments");
	
				if($comment != NULL)
				{					
					$user = $this->userManager->get($this->session->userdata("user_id"))->result_array();
					$user = $user[0];
					
					$return["id"] 		= $comment;
					$return["user_id"] 	= $user["uid"];
					$return["username"] = $user["username"];
					$return["user"] 	= $user;
					$return["text"] 	= $text;
					
					$return["result"] = 1;
				} else {
					$return["result"] = 2;
				}
			
			}
		}
		
		header('Content-type: application/json;');
        echo json_encode($return);
    }
    
    // [Action] Vote for a photo
	function vote_for_photo_id()
	{
		$this->load->model('user_model', 'userManager');
	    $this->load->model("site_model");
	    
    	$settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
		$user_lng = $this->userManager->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}	
		
		$pic_id = $this->input->post("pic_id");
		$this->lang->load('user_lang', $lng_opt);
		
		$this->load->model('photo_model', 'picManager');
		
		$data = array();
		
		$data["cant_like_own_photo"] = $this->lang->line("cant_like_own_photo");
		$data["photo_like"] = $this->lang->line("like_photo");
		$data["you_like_photo"] = $this->lang->line("you_like_photo");
		
		if($this->session->userdata('user_id') == null)
		{
			$data["return"] = 999;
		} else {
			$to_user = $this->picManager->get($pic_id)->result_array();
			
			$data["photo_votes"] = $to_user[0]["votes"];
						
			if($to_user[0]["uid"] == $this->session->userdata('user_id')) {
				$data["return"] = 998;
			} else {
				$vote = $this->picManager->vote_for_photo_id($pic_id, $this->session->userdata('user_id'));
			
				if($vote)
				{
					$data["return"] = 1;
										
					$this->load->model('action_model', 'actionModel');
					
					$like_action_str = sprintf($this->lang->line("photo_liked_action"), base_url("user/profile/" . $this->session->userdata('user_id')), $this->session->userdata('user_username'), base_url("user/profile/" . $to_user[0]["uid"]), $to_user[0]["username"]);
					
					$this->actionModel->add($to_user[0]["uid"], $this->session->userdata('user_id'), $like_action_str, base_url("photo/show/" . $pic_id . "/user"), "fa-thumbs-up");
				} else {
					$data["return"] = 2;
				}
			}
		}
		
		header('Content-type: application/json;');
        echo json_encode($data);
	}
	
	function delete_com()
	{
	 	$this->load->model('photo_model');
	 	
		$data = array();
	    $com_id = $this->input->post("com_id");

		$photo_id = $this->photo_model->get_photo_id_by_com_id($com_id)->result_array();
		$photo_id = $photo_id[0]["photo_id"];
	    
	    if($this->session->userdata("user_id"))
	    {
		    
		    if($this->photo_model->user_owns_comment($this->session->userdata("user_id"), $com_id) || $this->photo_model->user_owns_photo_com($this->session->userdata("user_id"), $com_id)) {
			    $this->photo_model->delete_comment($com_id);
			    
			    $this->photo_model->decrease_photo_comment($photo_id);
			    
			    $data["error"] = 0;
		    } else {
			    $data["error"] = 998;
			    $data["error_msg"] = "You can't delete that.";
		    }
	    } else {
		    $data["error"] = 999;
		    $data["error_msg"] = "Please log in.";
	    }
		
		header('Content-type: application/json;');
        echo json_encode($data);
	}
    
    function get_photo()
    {
	    $this->load->model('user_model', 'userManager');
	    $this->load->model("site_model");
	    
    	$settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
		$user_lng = $this->userManager->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}	
		
	    $this->lang->load('user_lang', $lng_opt);
	    $this->load->model('photo_model');
	    
	    $data = array();
	    
	    $photo_id = $this->input->post("photo_id");
	    
	    $photo = $this->photo_model->get($photo_id, $this->session->userdata("user_id"))->result_array();
	    
	    if($photo == null)
	    {
		    $data["error"] = 999;
		    $data["error_msg"] = $this->lang->line("photo_not_exists");
	    } else {
		    $data["error"] = 0;
		    $data["photo_from"] = sprintf($this->lang->line("photo_from"), ucfirst($photo[0]["username"]));
		    $data["like_photo"] = $this->lang->line("like_photo");
		    $data["comment_photo"] = $this->lang->line("comment_photo");
		   	$data["photo"] = $photo[0];
		   	$data["you_like_photo"] = $this->lang->line("you_like_photo");
		   	
		   	$data["this_user"] = $this->session->userdata("user_id");
		   			   	
		   	// Get photo comments
		   	$data["comments"] = $this->photo_model->get_comments($photo_id, $this->session->userdata("user_id"))->result_array();
		   	$data["no_photo_comment"] = $this->lang->line("no_photo_comment");
	    }
	    
		header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function delete_photo()
    {
		$this->load->model('photo_model');
		$this->load->model('user_model');
		
		$result = array();
		
		$photo_id = $this->input->post("photo_id");
		
		if(DEMO_MODE == 1) {
	        $result["error"] = 500;
        } else {
			
			// Check if this photo is owned by this user
			if($this->photo_model->user_owns_photo($this->session->userdata("user_id"), $photo_id)) {
				
				$photo = $this->photo_model->get($photo_id)->result_array();
				$photo = $photo[0];
				
				$filepath = $_SERVER['DOCUMENT_ROOT'].$photo["url"];
				$thumbpath = $_SERVER['DOCUMENT_ROOT'].$photo["thumb_url"];
				
				// Check if it was this user profile's photo
				$user = $this->user_model->get($this->session->userdata("user_id"))->result_array();
				$user = $user[0];
				
				// Update user profile picture to null
				if($user["main_photo"] == $photo_id) {
					$this->user_model->update_info($this->session->userdata("user_id"), array("main_photo" => ""));
					$this->session->set_userdata(
						array(
							"user_avatar"	=> base_url() . "images/avatar.png"
						)
					);
				}
				
				// Delete photo from DB
				$this->photo_model->delete($photo_id);
				
				// Delete files
				@unlink($filepath);
				@unlink($thumbpath);
				
				$result["error"] = 0;
			} else {
				$result["error"] = 999;
			}
		}
			
		header('Content-type: application/json;');
		echo json_encode($result);
	}
    
    function uploadPhoto()
    {
    	$this->load->helper('url');
    	$this->load->model('photo_model', 'picManager');
    	$this->load->model('user_model', 'userManager');
    	
		$result = array();
		
		$user = $this->userManager->get($this->session->userdata("user_id"))->result_array();
		$user = $user[0];

     	$user_id = url_title($this->session->userdata('user_id'));
        
        if(DEMO_MODE == 1) {
	        $result["status"] = 500;
        } else {
	        if($user_id == null)
	        {
		        $result["status"] = 0;
	        } else {
	        
	        	$this->load->model("site_model");
	        	$settings = $this->site_model->get_website_settings()->result_array();
			    $settings = $settings[0];
	        
				$max_upload = $settings["upload_limit"];
				$user_photos = $this->picManager->count_user_photos($this->session->userdata("user_id"));
				
				if($user_photos >= $max_upload && $max_upload != 0) {
					$result["status"] = 998;
				} else {
		        
		        	// Check if the directory already exists
		        	if (!file_exists("./uploads/photos/" . $user_id . "/")) {
		        		mkdir("./uploads/photos/" . $user_id . "/");
		        		mkdir("./uploads/photos/" . $user_id . "/thumbnails/");
		        	}
		        	
		        	// Copy the file to the correct directory
					if (!empty($_FILES))
					{
						$nameFile 	= rand(0,999999).time();
					    $tempFile 	= $_FILES['upl']['tmp_name'];
					    $fileSize 	= $_FILES['upl']['size'];
					    $fileTypes 	= array('jpg','jpeg','png', 'JPG', 'JPEG', 'PNG'); // File extensions
					    $fileParts 	= pathinfo($_FILES['upl']['name']);
					    $targetPath = "./uploads/photos/" . $user_id . "/";
					    $targetPathThumb = $targetPath . "thumbnails/";
					    $targetPathEcho = "/uploads/photos/" . $user_id . "/";
					    $targetPathEchoThumb = "/uploads/photos/" . $user_id . "/thumbnails/";
					    $targetFile =  str_replace('//','/',$targetPath) . $nameFile . "." . $fileParts["extension"];
					    $targetFileThumb = str_replace('//','/',$targetPathThumb) . $nameFile . "." . $fileParts["extension"];
					    $targetFileEcho = str_replace('//','/',$targetPathEcho) . $nameFile . "." . $fileParts["extension"];
					    $targetFileEchoThumb = str_replace('//','/',$targetPathEchoThumb) . $nameFile . "." . $fileParts["extension"];
					
						if($fileSize <= 7000000)
						{
						    if (in_array($fileParts['extension'],$fileTypes)) {
						    	// Send the file
								$file = $this->compress_image($tempFile, $targetFile, 100);
								
								$thumbWidth = 800;
								
								// Create the thumbnail
								$img = imagecreatefromjpeg( $file );
								$width = imagesx( $img );
								$height = imagesy( $img );
								
								// calculate thumbnail size
								$new_width = $thumbWidth;
								$new_height = floor( $height * ( $thumbWidth / $width ) );
								
								// create a new temporary image
								$tmp_img = imagecreatetruecolor( $new_width, $new_height );
								
								// copy and resize old image into new image
								imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
								
								// save thumbnail into a file
								imagejpeg( $tmp_img, $targetFileThumb );
								
								if($user["gender"] == 1) {
				                	$word_gender = "her";
			                	} else {
				                	$word_gender = "his";
			                	}
								
								$this->load->model('action_model', 'actionModel');
								$this->actionModel->add($this->session->userdata('user_id'), 17, "<a href='" . base_url("user/profile/" . $this->session->userdata('user_id')) . "'>" . $this->session->userdata('user_username') . "</a> uploaded new photos on " . $word_gender . " profile.", base_url("user/profile/" . $this->session->userdata('user_id')), "<i class='fa fa-picture-o'></i>");
								
								// Add picture in the DB 
								$pic_id = $this->picManager->add($targetFileEcho, $targetFileEchoThumb, $this->session->userdata('user_id'));
								$result["status"] 	= 1; 
								$result["photo"] 	= $targetFileEcho;
								$result["id"]		= $pic_id; 
								
								// Count user photos
								$nb_user_photos = $this->picManager->count_user_photos($this->session->userdata('user_id'));
								
								// User has just one photo, set it as the profile picture
								if($nb_user_photos == 1) {
									$this->userManager->update_info($this->session->userdata('user_id'), array("main_photo" => $pic_id));
									
									$this->session->set_userdata(
										array(
											"user_avatar"	=> $targetFileEchoThumb,
										)
									);
								}
								
						    } else {
						        $result["status"] = 0;
						    }
					    }
					} 
				}
			}
		}
		
		header('Content-type: application/json;');
        echo json_encode($result);
    }
    
    function filemanipulation($extension,$filename)
    {
        // you can insert the result into the database if you want.
        if($this->is_image($extension)) 
        {
            $config['image_library']  = 'gd2';
            $config['source_image']   = './uploads/'.$filename;
            $config['new_image']      = './uploads/thumbs/';
            $config['create_thumb']   = TRUE;
            $config['maintain_ratio'] = TRUE;
            $config['thumb_marker']   = '';
            $config['width']   = 300;
            $config['height']   = 300;

            $this->load->library('image_lib', $config); 

            $this->image_lib->resize();
            echo 'image';
        }
        else echo 'file';
    }
    
    function save_photos_infos()
	{
		$result = 0;
		
		$photo_array = $this->input->post("photo_array");

		if(!$this->session->userdata("user_id"))
		{
			$result = 999;
		} else {
			foreach($photo_array as $photo) {				
				
				$pic_id = $photo['photo_id'];
					
				$data = array("text" => $photo['title']);
				
				$this->load->model('photo_model');
			
				$this->photo_model->update($pic_id, $data);
				
				$result = 1;
				
			}
		}
		
		header('Content-type: application/json;');
		echo json_encode($result);
	}
	
	function manage()
	{
		$data = array();
		
		$this->load->model("site_model");
		$this->load->model('user_model');
		
		$data["nb_lng"] = $this->site_model->count_language_redirections();
		
		$settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    $data["settings"] = $settings;
	    
		$user_lng = $this->user_model->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}	
		
		$this->lang->load('user_lang', $lng_opt);
		$this->lang->load('site_lang', $lng_opt);
		
		if(!$this->session->userdata("user_id"))
		{
			header("Location: " . base_url());
		} else {
			
				
			$this->load->model("pm_model");
			
			// Get custom pages
			if($this->site_model->count_custom_pages() > 0) {
				$data["pages"] = $this->site_model->get_pages()->result_array();
			} else {
				$data["pages"] = null;
			}
			
			$data["title"] = $this->lang->line("p_manage_photos");

			$nb_pm = $this->pm_model->count_unread($this->session->userdata('user_id'));
			$data["nb_pm"] = $nb_pm;
			
			$new_loves = $this->user_model->count_new_loves($this->session->userdata("user_id"));
			$data["nb_new_loves"] = $new_loves;
			
			$new_friends = $this->user_model->count_new_friends($this->session->userdata("user_id"));
			$data["nb_new_friends"] = $new_friends;
			
			$user = $this->user_model->get($this->session->userdata("user_id"))->result_array();
			$user = $user[0];
			
			$new_poke_requests = $this->user_model->count_new_requests($this->session->userdata("user_id"));
			$data["nb_new_requests"] = $new_poke_requests;
			
			$new_profile_visits = $this->user_model->count_new_profile_visits($this->session->userdata("user_id"));
			$data["nb_new_visits"] = $new_profile_visits;
			
			$data["total_notif"] = intval($new_profile_visits) + intval($nb_pm) + intval($new_loves) + intval($new_poke_requests) + intval($new_friends);

			$photos = $this->user_model->get_photos($this->session->userdata("user_id"), 50)->result_array();
			$data["photos"] = $photos;
			
			$data["jscripts"] = array(
				base_url() . "js/uploader/jquery.ui.widget.js",
				base_url() . "js/uploader/jquery.knob.js",
				base_url() . "js/uploader/jquery.iframe-transport.js",
				base_url() . "js/uploader/jquery.fileupload.js",
				base_url() . "js/pages/manage_photos.js"
			);
		
			$this->load->view('photo/manage', $data);
		}
	}
    
    function add()
    {
		$data = array();
		
		$this->load->model('site_model');
		$this->load->model('user_model');	
			
		$settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    $data["settings"] = $settings;
	    
	    $data["nb_lng"] = $this->site_model->count_language_redirections();
		$user_lng = $this->user_model->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}
		
	    $this->lang->load('site_lang', $lng_opt);
	    $this->lang->load('upload_lang', $lng_opt);
	   	$this->lang->load('user_lang', $lng_opt);
	    
		if(!$this->session->userdata("user_id"))
		{
			header("Location: " . base_url());
		} else {
			
			// Get custom pages
			if($this->site_model->count_custom_pages() > 0) {
				$data["pages"] = $this->site_model->get_pages()->result_array();
			} else {
				$data["pages"] = null;
			}		    
						
			$this->load->model("pm_model");
			$nb_pm = $this->pm_model->count_unread($this->session->userdata('user_id'));
			$data["nb_pm"] = $nb_pm;
			
			$new_loves = $this->user_model->count_new_loves($this->session->userdata("user_id"));
			$data["nb_new_loves"] = $new_loves;
			
			$new_friends = $this->user_model->count_new_friends($this->session->userdata("user_id"));
			$data["nb_new_friends"] = $new_friends;
			
			$user = $this->user_model->get($this->session->userdata("user_id"))->result_array();
			$user = $user[0];
			
			$new_poke_requests = $this->user_model->count_new_requests($this->session->userdata("user_id"));
			$data["nb_new_requests"] = $new_poke_requests;
			
			$new_profile_visits = $this->user_model->count_new_profile_visits($this->session->userdata("user_id"));
			$data["nb_new_visits"] = $new_profile_visits;
			
			$data["total_notif"] = intval($new_profile_visits) + intval($nb_pm) + intval($new_loves) + intval($new_poke_requests) + intval($new_friends);
			
			$data["title"] = $this->lang->line("upload_photos_title");
			
			$data["jscripts"] = array(
				base_url() . "js/uploader/jquery.ui.widget.js",
				base_url() . "js/uploader/jquery.knob.js",
				base_url() . "js/uploader/jquery.iframe-transport.js",
				base_url() . "js/uploader/jquery.fileupload.js",
				base_url() . "js/pages/add_photos.js"
			);
		
			$this->load->view('photo/add', $data);
		}
	}
	
	private function compress_image($source, $destination, $quality, $width = 1000) 
    { 
    	$info = getimagesize($source); 
    	
    	
    	if ($info['mime'] == 'image/jpeg') 
    		$img = imagecreatefromjpeg( $source );
    	elseif ($info['mime'] == 'image/gif') 
    		$img = imagecreatefromgif( $source ); 
    	elseif ($info['mime'] == 'image/png') 
    		$img = imagecreatefrompng( $source ); 
    		
    	$thumbWidth = $width;
							
		// Create the thumbnail
		$width = imagesx( $img );
		$height = imagesy( $img );
		
		// calculate thumbnail size
		$new_width = $thumbWidth;
		$new_height = floor( $height * ( $thumbWidth / $width ) );
		
		// create a new temporary image
		$tmp_img = imagecreatetruecolor( $new_width, $new_height );
		
		// copy and resize old image into new image
		imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
		
		// save thumbnail into a file
		imagejpeg( $tmp_img, $destination, $quality );
    		
    	return $destination; 
    } 
}