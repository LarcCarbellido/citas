<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    function index() 
    {
	    $data = array();
	    
	    $this->load->model('site_model');
	    
	    $settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $data["settings"] = $settings;
	    
		if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {
	    	$this->load->model("user_model");
	    	
	    	$data["title"] = "Admin Dashboard";
	    	
	    	if($this->session->userdata("user_firstform") != 1)
	    	{
		    	show_error("You can't access to this part of the website.");
	    	} else {
		    	
		    	$data["total_users"] = $this->user_model->record_count();
		    	$data["new_users_today"] = $this->user_model->count_today_new_users();
		    	$data["total_purchases"] = $this->user_model->record_count_purchases();
		    	
		    	$data["css"] = array();
		    	
		    	$data["jscripts"] = array();
		    	
				$this->load->view('admin/index', $data);
		   	}
	   	}
    }
    
    function theme() 
    {
	    $data = array();
	    
	    $this->load->model('site_model');
	    
	    $settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $data["settings"] = $settings;
	    
		if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {
	    	$this->load->model("user_model");
	    	
	    	$data["title"] = "Theme Options";
	    	
	    	if($this->session->userdata("user_firstform") != 1)
	    	{
		    	show_error("You can't access to this part of the website.");
	    	} else {
		    	
		    	$data["total_users"] = $this->user_model->record_count();
		    	$data["new_users_today"] = $this->user_model->count_today_new_users();
		    	$data["total_purchases"] = $this->user_model->record_count_purchases();
		    	
		    	$data["css"] = array();
		    	
		    	$data["jscripts"] = array(base_url() . "js/pages/admin_theme.js");
		    	
				$this->load->view('admin/theme', $data);
		   	}
	   	}
    }
    
    function user_generator() 
    {
	    $data = array();
	    
	    $this->load->model('site_model');
	    
	    $settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $this->lang->load('user_lang', $settings["default_language"]);
	    
	    $data["settings"] = $settings;
	    
		if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {
	    	$this->load->model("user_model");
	    	$this->load->model("photo_model");
	    	
	    	$data["title"] = "User Generator";
	    	
	    	$data["jscripts"] = array();
	    	
	    	$this->load->library('form_validation');
	    	
	    	$this->form_validation->set_rules('users_url', '"Profile Photos URLs"', 'trim|required');
	    	$this->form_validation->set_rules('gender_users', 'Users Gender', "trim|required");
	    	
	    	if ($this->form_validation->run()) {
		    	if(DEMO_MODE == 1) {
					redirect(base_url() . 'admin/user_generator?action=demo');
			    } else {
			    	$urls = $this->input->post("users_url");
			    	
					preg_match_all("/(\n)/", $urls, $matches);
					$nb_photos = count($matches[0]) + 1;
					
					$cpt = 0;
					
					// Each line = One user
					foreach(explode("\n", $urls) as $line) {
						$username = genUsername(8);
						$password = $username.time();
						$email = $username."@gmail.com";
						$encrypt_id = $password;
											
						$gender = $this->input->post("gender_users");
						
						// Create the user
						$user_id = $this->user_model->create($username, sha1($password), $email, 1, $encrypt_id, "", "", 0);
						$this->user_model->create_info($user_id, $gender, 0);
									
						$photo_id = $this->photo_model->add($line, $line, $user_id);
						
						$birthday_timestamp = mt_rand(573276495, 873276495);
						
						$country =  strtoupper(get_country_code_by_name(randomCountry()));
						
						$update_user = array(
							"main_photo"	=> $photo_id,
							"birthday"		=> date("Y-m-d", $birthday_timestamp),
							"about"			=> "",
							"country"		=> $country,
							"city"			=> ""
						);
						
						$this->load->model('action_model', 'actionModel');
				
						$action_txt = sprintf($this->lang->line('created_account_action'),base_url("user/profile/" . $user_id), $username);
						
						$this->actionModel->add(1, $user_id, $action_txt, base_url("user/profile/" . $user_id), "fa-sign-in");
						
						$this->user_model->update_info($user_id, $update_user);
						$this->user_model->update($user_id, array("first_step_form" => 1, "is_fake" => 1));
						
						$cpt++;
					}
				}
				
				redirect(base_url() . "admin/user_generator?action=users_created");
		    }
		    	
			$this->load->view('admin/user_generator', $data);
	   	}
    }
    
    function reported_content() 
    {
	    $data = array();
	    
	    $this->load->model('site_model');
	    
	    $settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $data["settings"] = $settings;
	    
		if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {
	    	$this->load->model("user_model");
	    	
	    	$data["title"] = "Reported Content";
	    	
	    	$data["jscripts"] = array();
		    	
			$this->load->view('admin/reported_content', $data);
	   	}
    }
    
    // Edit a custom page
    function edit_page($page_id = 0)
    {
	    if (!$this->session->userdata('user_id')) {	
    		$this->session->set_flashdata('status', 'not_registered');
			show_error("You are not logged in anymore.");
		} else {	
		
			if($this->session->userdata("user_firstform") == 0) {
		    	redirect( base_url() . "user/firstlogin?redirect=true" );
	    	}  
	    	
	    	if($this->session->userdata("user_rank") > 0) {
		    	$this->load->model('site_model');
		    	
		    	$page = $this->site_model->get_page($page_id);
		    	
		    	if($page != null)
		    	{
					$data = array();
					
					$data["page"] = $page;
					
					$settings = $this->site_model->get_website_settings()->result_array();
				    $settings = $settings[0];
				    
				    $data["settings"] = $settings;
					
			    	$user_id = $this->session->userdata('user_id');
			    	$this->load->model('user_model');
			    	$this->load->library('form_validation');
					
					// Page title
					$data["title"]	= "Admin - Edit a Custom Page";
				
					// Form part
				    $this->form_validation->set_rules('title',  '"Title"',  'trim|required|min_length[2]|max_length[80]');
				    $this->form_validation->set_rules('content', '"Content"', 'trim|required');
				    $this->form_validation->set_rules('icon', '"Icon"', 'trim|required');
				    $this->form_validation->set_rules('show_on_welcome', '"Show on Welcome Page"', 'trim');
				    		    
				    // Form post
				    if ($this->form_validation->run()) {
					    
					    if(DEMO_MODE == 1) {
							redirect(base_url() . 'admin/pages?action=demo');
					    } else {
	
							if($this->input->post("show_on_welcome") == null) {
							    $show_on_welcome = 0;
						    } else {
							    $show_on_welcome = 1;
						    }
	
						    $this->site_model->edit_page(
							    $page_id,
							    array(
		    						"title" 	=> $this->input->post('title'), 
									"content" 	=> $this->input->post('content'),
									"icon" 		=> $this->input->post('icon'),
									"welcome_enable" => $show_on_welcome
								)
							);
					    							
					    	redirect(base_url() . 'admin/pages?action=page_edited');
				    	
				    	}
	
				    }
				   		  
					$data["jscripts"] = array(base_url()."js/summernote.min.js", base_url()."js/pages/admin_add_custom_page.js");  
				   		    
				    $this->load->view('admin/edit_page', $data);
			    } else {
				    show_404();
			    }
		    } else {
			    show_404();
		    }
		}
    }
    
    // Create a new custom page
    function add_page()
    {
	    if (!$this->session->userdata('user_id')) {	
    		redirect(base_ur());
		} else {	
		
			if($this->session->userdata("user_firstform") == 0) {
		    	redirect( base_url() . "user/firstlogin?redirect=true" );
	    	}  
	    	
	    	if($this->session->userdata("user_rank") > 0) {
		    	$this->load->model('site_model');
		    			
				$data = array();
				$settings = $this->site_model->get_website_settings()->result_array();
			    $settings = $settings[0];
			    
			    $data["settings"] = $settings;
				
		    	$user_id = $this->session->userdata('user_id');
		    	$this->load->model('user_model');
		    	$this->load->library('form_validation');
				
				// Page title
				$data["title"]	= "Admin - Create a New Custom Page";
			
				// Form part
			    $this->form_validation->set_rules('title',  '"Title"',  'trim|required|min_length[2]|max_length[80]');
			    $this->form_validation->set_rules('content', '"Content"', 'trim|required');
			    $this->form_validation->set_rules('icon', '"Icon"', 'trim|required');
			    $this->form_validation->set_rules('show_on_welcome', '"Show on Welcome Page"', 'trim');
			    		    
			    // Form post
			    if ($this->form_validation->run()) {
				    
				    if(DEMO_MODE == 1) {
						redirect(base_url() . 'admin/pages?action=demo');
				    } else {
					    
					    if($this->input->post("show_on_welcome") == null) {
						    $show_on_welcome = 0;
					    } else {
						    $show_on_welcome = 1;
					    }

				    	$insertAndId = $this->site_model->add_page(
			    						$this->input->post('title'), 
										$this->input->post('content'),
										$this->input->post('icon'),
										$this->session->userdata("user_id"),
										$show_on_welcome
									);
			    							
						redirect(base_url() . 'admin/pages?action=page_created');
			    	
			    	}

			    }
			   	
			   	$data["jscripts"] = array(base_url()."js/summernote.min.js", base_url()."js/pages/admin_add_custom_page.js");    
			    $this->load->view('admin/add_page', $data);
		    } else {
			    show_404();
		    }
		}
    }
    
    function pages()
    {	   
	    $data = array();
    	
    	$this->load->model('site_model');
	    
	    $settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $data["settings"] = $settings;
    	
    	$data["title"] = "Admin - Manage Pages";
    	
    	$data["pages"]			= $this->site_model->get_pages();
    	
    	
    	if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {
			$data["jscripts"] = array(base_url()."js/pages/admin_manage_pages.js");  
			$this->load->view('admin/manage_pages', $data);
	   	}
	    
    }
    
    function forum()
    {	   
	    $data = array();
    	
    	$this->load->model('site_model');
    	$this->load->model("user_model");
    	$this->load->model("forum_model");
	    
	    $settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $data["settings"] = $settings;
    	
    	$data["title"] = "Admin - Manage Forum";
    	
    	$data["categories"]			= $this->forum_model->get_categories();
    	
    	
    	if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {
			$data["jscripts"] = array(base_url()."js/pages/admin_manage_forum.js");
			$this->load->view('admin/manage_forum', $data);
	   	}
	    
    }
    
    // Edit a forum category
    function edit_forum_cat($cat_id = 0)
    {
	    if (!$this->session->userdata('user_id')) {	
    		$this->session->set_flashdata('status', 'not_registered');
			show_error("You are not logged in anymore.");
		} else {	
		
			if($this->session->userdata("user_firstform") == 0) {
		    	redirect( base_url() . "user/firstlogin?redirect=true" );
	    	}  
	    	
	    	if($this->session->userdata("user_rank") > 0) {
	    	
		    	$this->load->model('forum_model', 'forumManager');
		    	$this->load->model('site_model');
		    	
		    	// Get the forum cat
		    	$category = $this->forumManager->get_category($cat_id);
		    	
		    	if($category != null)
		    	{
			    	$data = array();
			    	
			    	$data["category"] = $category;
			    			
					$settings = $this->site_model->get_website_settings()->result_array();
				    $settings = $settings[0];
				    
				    $data["settings"] = $settings;
					
			    	$user_id = $this->session->userdata('user_id');
			    	$this->load->model('user_model');
			    	$this->load->library('form_validation');
					
					// Page title
					$data["title"]	= "Admin - Edit a Forum Category";
				
					// Form part
				    $this->form_validation->set_rules('title',  '"Name"',  'trim|required|min_length[5]|max_length[80]');
				    $this->form_validation->set_rules('desc', '"Description"', 'trim|required');
				    		    
				    // Form post
				    if ($this->form_validation->run()) {
					    
					    if(DEMO_MODE == 1) {
							redirect(base_url() . 'admin/forum?action=demo');
					    } else {
					    
					    	$this->forumManager->edit_category($cat_id, array("name" => $this->input->post('title'), "desc" => $this->input->post('desc')));
					    							
					    	redirect(base_url() . 'admin/forum?action=cat_edited');
				    	
				    	}
				    }
				   		    
					$data["jscripts"] = array();
				   
				    $this->load->view('admin/edit_forum_cat', $data);
			    } else {
				    show_404();
			    }
		    
		    }
		}
    }
    
    // Create a new forum category
    function add_forum_cat()
    {
	    if (!$this->session->userdata('user_id')) {	
    		$this->session->set_flashdata('status', 'not_registered');
			show_error("You are not logged in anymore.");
		} else {	
		
			if($this->session->userdata("user_firstform") == 0) {
		    	redirect( base_url() . "user/firstlogin?redirect=true" );
	    	}  
	    	
	    	if($this->session->userdata("user_rank") > 0) {
	    	
		    	$this->load->model('site_model');
		    			
				$data = array();
				$settings = $this->site_model->get_website_settings()->result_array();
			    $settings = $settings[0];
			    
			    $data["settings"] = $settings;
				
		    	$user_id = $this->session->userdata('user_id');
		    	$this->load->model('user_model');
		    	$this->load->library('form_validation');
				
				// Page title
				$data["title"]	= "Admin - Create a New Forum Category";
			
				// Form part
			    $this->form_validation->set_rules('title',  '"Name"',  'trim|required|min_length[5]|max_length[80]');
			    $this->form_validation->set_rules('desc', '"Description"', 'trim|required');
			    		    
			    // Form post
			    if ($this->form_validation->run()) {
				    
				    if(DEMO_MODE == 1) {
						redirect(base_url() . 'admin/forum?action=demo');
				    } else {
			    
				    	$this->load->model('forum_model', 'forumManager');
				    	
				    	$insertAndId = $this->forumManager->add_category(
				    						$this->input->post('title'), 
											$this->input->post('desc')
										);
				    							
				    	redirect(base_url() . 'admin/forum?action=cat_created');
			    	
			    	}
			    }
			   		    
			   	$data["jscripts"] = array();
			   	
			    $this->load->view('admin/add_forum_cat', $data);
		    
		    }
		}
    }
    
    function delete_photo()
    {
	    $data = array();
	    
	    if(DEMO_MODE == 1) {
			$data["result"] = 500;
			$data["error_msg"] = "You can't do this in demo mode.";
	    } else {
		    if($this->session->userdata("user_rank") < 1) {
				$data["result"] = 999;
				$data["error_msg"] = "You are not allowed to do that.";
			} else {
			    $photo_id = $this->input->post("photo_id");
			    
			    $this->load->model("photo_model");
				$this->load->model("user_model");
							    
			    $photo = $this->photo_model->get($photo_id)->result_array();
				$photo = $photo[0];
				
				$filepath = $_SERVER['DOCUMENT_ROOT'].$photo["url"];
				$thumbpath = $_SERVER['DOCUMENT_ROOT'].$photo["thumb_url"];
				
				// Check if it was this user profile's photo
				$user = $this->user_model->get($photo["uid"])->result_array();
				$user = $user[0];
				
				// Update user profile picture to null
				if($user["main_photo"] == $photo_id) {
					$this->user_model->update_info($photo["uid"], array("main_photo" => ""));
				}
				
				// Delete photo from DB
				$this->photo_model->delete($photo_id);
				
				// Delete files
				@unlink($filepath);
				@unlink($thumbpath);
				
				$report_id = $this->input->post("report_id");
			    
			    $this->load->model("user_model");
			    
			    $this->user_model->cancel_report($report_id);
							    
			    $data["result"] = 1;
		    }
	    }
	    
	    header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function cancel_report()
    {
	    $data = array();
	    
	    if(DEMO_MODE == 1) {
			$data["result"] = 500;
			$data["error_msg"] = "You can't do this in demo mode.";
	    } else {
		    if($this->session->userdata("user_rank") < 1) {
				$data["result"] = 999;
				$data["error_msg"] = "You are not allowed to do that.";
			} else {
			    $report_id = $this->input->post("report_id");
			    
			    $this->load->model("user_model");
			    
			    $this->user_model->cancel_report($report_id);
			    
			    $data["result"] = 1;
		    }
	    }
	    
	    header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function get_user_edit_infos()
    {
	    $data = array();
		
		$this->load->model('user_model');	
		$user_id = $this->input->post("user_id");
		    	
    	$user = $this->user_model->get($user_id)->result_array();
    	$user = $user[0];	
    	
    	$data["user"] = $user;
    	
    	header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function change_forum_state()
    {
	    $data = array();
	    
	    if(DEMO_MODE == 1) {
			$data["result"] = 500;
			$data["error_msg"] = "You can't edit this in demo mode.";
	    } else {
		    
		    if($this->session->userdata("user_rank") < 1) {
				$data["result"] = 999;
				$data["error_msg"] = "You are not allowed to do that.";
			} else {
				$this->load->model('forum_model');	
				$enabled = $this->input->post("enabled");
				
				$this->forum_model->change_forum_state($enabled);
				    	
		    	$data["result"] = 1;
	    	}
    	}
    	
    	header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function edit_user_submit($user_id = 0)
    {
	    if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {
			$this->load->model('user_model');
			
			if($this->user_model->check_user_exists($user_id))
			{
			
				$update_user = array();
			
				$gender 			= $this->input->post('gender');
				$about 				= $this->input->post('about_you_txt');
				$birthday_day 		= $this->input->post('birthday_day');
				$birthday_month 	= $this->input->post('birthday_month');
				$birthday_year 		= $this->input->post('birthday_year');
				$country 			= $this->input->post('country');
				$city 				= $this->input->post('city');
									
				if($gender != "0" && $birthday_day != "0" && $birthday_month != "0" && $birthday_year != "0" && $country != "0") {
				
					if($gender == "female")
					{
						$gender = 1;
					} else {
						$gender = 0;
					}
			    					
					$date_string = $birthday_day . " " . $birthday_month . " " . $birthday_year;
					
					$update_user = array(
						"gender"		=> $this->security->xss_clean($gender),
						"birthday"		=> date("Y-m-d", strtotime($date_string)),
						"country"		=> $this->security->xss_clean($country),
						"city"			=> $this->security->xss_clean($city),
						"about"			=> $this->security->xss_clean($about)
					);
					
					// Save user infos
					$this->user_model->update_info($user_id, $update_user);
					
					// Ok! Redirect
					redirect(base_url("admin/edit_user/$user_id?action=edit_success"));
	
		    	} else {
					show_error("Woops! There is an error. Please go back and try again.");
		    	}
	    	
	    	} else {
		    	show_404();
	    	}
	    	
    	}
    }
    
    function edit_account_submit($user_id = 0)
	{
		if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {
			$this->load->model('user_model');
			
			if($this->user_model->check_user_exists($user_id))
			{
			
				$user = $this->user_model->get($user_id)->result_array();
				$user = $user[0];
				
				$update_user = array();
			
				$username 			= $this->security->xss_clean($this->input->post('username'));
				$email 				= $this->input->post('email');
	
				$error = false;
						    	
		    	$username_taken = $this->user_model->is_username_taken_user($username,$user_id);
				$email_taken 	= $this->user_model->is_email_taken_user($email,$user_id);
				
				
				if(strlen($username) < 3) {
		    		$error = true;
		    	} else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
		    		$error = true;
		    	} else if($username_taken) {
				    $error = true;
			    } else if($email_taken) {
				    $error = true;
			    }
					
				if($error) {
					show_error("Whoops. There is an error. Please try again.");
				} else {
			
					$update_user = array(
						"username"		=> $username,
						"email"			=> $email,
					);
	
					
					$this->session->set_userdata(
						array(
							"user_username"	=> $username,
						)
					);
					
					// Save user infos
					$this->user_model->update($user_id, $update_user);
					
					// Ok! Redirect
					redirect(base_url("admin/edit_user/$user_id?action=edit_success"));
			
				}
			} else {
				show_404();
			}

    	}
	}
	
	function demoteuseradmin($user_id = 0)
	{
		if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {
			$this->load->model('user_model');
			
			if($this->user_model->check_user_exists($user_id))
			{
				
				$this->user_model->update($user_id, array("rank" => 0));
				
				redirect(base_url("admin/edit_user/$user_id?action=edit_success"));
			
			} else {
				show_404();
			}
		}	
	}
	
	function makeuseradmin($user_id)
	{
		if(DEMO_MODE == 1) {
			show_error("You can't edit this user in demo mode.");
	    } else {
			if($this->session->userdata("user_rank") < 1) {
				show_error("You can't access to this part of the website.");
			} else {
				$this->load->model('user_model');
				
				if($this->user_model->check_user_exists($user_id))
				{
				
					$this->user_model->update($user_id, array("rank" => 1));
					
					redirect(base_url("admin/edit_user/$user_id?action=edit_success"));
				
				} else {
					show_404();
				}
			}
		}
	}
    
    function editaccountinfosverif()
	{
		$data = array();
		
		$this->load->model('user_model');	
	    $user_id 			= $this->input->post('user_id');
	    $username 			= $this->security->xss_clean($this->input->post('username'));
    	$email 				= $this->input->post('email');
		$username_taken 	= $this->user_model->is_username_taken_user($username, $user_id);
		$email_taken 		= $this->user_model->is_email_taken_user($email, $user_id);
						    	
		if(DEMO_MODE == 1) {
			$data["error_msg"] = "You can't edit these settings in demo mode.";
		    $data["error"] = 500;
	    } else {
	    	if(strlen($username) < 3)
	    	{
		    	$data["error"] = 999;
		    	$data["error_msg"] = "Please write a username with at least 3 characters.";
	    	} else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		    	$data["error"] = 998;
		    	$data["error_msg"] = "Please write a valid email address.";
	    	} else if($username_taken) {
		    	$data["error"] = 996;
		    	$data["error_msg"] = "This username is already taken. Please choose another one.";
	    	} else if($email_taken) {
	    		$data["error"] = 995;
		    	$data["error_msg"] = "This email is already taken. Please choose another one.";
	    	} else {
		    	$data["error"] = 0;
	    	}
    	}
    	
    	header('Content-type: application/json;');
		echo json_encode($data);
	}
	
	function editusercoins()
	{
		$data = array();
		$this->load->model('premium_model');
		
		$user_id = $this->input->post("user_id");
		$nb_coins = $this->input->post("coins");
		
		if(DEMO_MODE == 1) {
			$data["error_msg"] = "You can't edit these settings in demo mode.";
		    $data["error"] = 500;
	    } else {
		    if(!$this->premium_model->check_user_coins_exists($user_id))
			{
				// Create the user's credit account
				$this->premium_model->create_user_coins($user_id);
			}
			
			// Update user coin row with the number of purchased coins
			$this->premium_model->set_user_coins($user_id, $nb_coins);
			
			$data["error"] = 0;
	    }
		
		header('Content-type: application/json;');
		echo json_encode($data);
	}
    
    function edit_user($user_id = 0) 
    {
	    $data = array();
	    $this->load->model('site_model');
	    $this->load->model('premium_model');
	    
	    $settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $data["settings"] = $settings;
	    
		if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {
	    	$this->load->model("user_model");
	    	
	    	$user = $this->user_model->get($user_id)->result_array();
	    	
	    	if(sizeof($user) == 0) {
		    	show_404();
	    	} else {
		    	
		    	// Get user coins
				$user_coins = $this->premium_model->get_user_coin($user_id)->result_array();
				
				if(sizeof($user_coins) == 0) {
					$user_coins = 0;
				} else {
					$user_coins = $user_coins[0]["nb_coins"];
				}
				
				$data["user_coins"] = $user_coins;
				    	
		    	$user = $user[0];
		    	
		    	$data["user"] = $user;
		    	$data["title"] = "Edit User - " . $user["username"];
		    	
		    	$data["jscripts"] = array(base_url()."js/pages/admin_edit_user.js");
		    	
				$this->load->view('admin/edit_user', $data);
			}
	   	}
    }
    
    function reported_photos() 
    {
	    $data = array();
    	
    	$this->load->model('site_model');
    	$this->load->model("user_model");
	    
	    $settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $data["settings"] = $settings;
    	
    	$data["title"] = "Admin - Reported Photos";
    	
    	
    	if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {
			
			$this->load->library("pagination");
		    
		    $config = array();
	        $config["base_url"] 	= base_url() . "admin/reported_photos";
	       
	        $config["per_page"] 	= 20;
	        $config["uri_segment"] 	= 3;
	        $config['num_links'] 	= 1;
	        
	        $config['first_link']	= "<<";
	        $config['last_link']	= ">>";
	        
	        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
	        
	        $settings = $this->site_model->get_website_settings()->result_array();
	        $settings = $settings[0];
	    	
	    	$data["settings"] = $settings;
	    	
	    	$photos = $this->user_model->get_last_reported_photos_page($config["per_page"], $page, $this->session->userdata('user_id'))->result_array();
			$photos_count = $this->user_model->record_reported_count(1);
			
		    $config["total_rows"] 	= $photos_count;
		    $this->pagination->initialize($config);
	        
	        $data["photos"] = $photos;
	        $data["links"] = $this->pagination->create_links();
	        
	        $data["jscripts"] = array(base_url()."js/pages/admin_manage_users.js");
	    	
			$this->load->view('admin/reported_photos', $data);
	   	}
    }
    
    function reported_users() 
    {
	    $data = array();
    	
    	$this->load->model('site_model');
    	$this->load->model("user_model");
	    
	    $settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $data["settings"] = $settings;
    	
    	$data["title"] = "Admin - Reported Users";
    	
    	
    	if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {
			
			$this->load->library("pagination");
		    
		    $config = array();
	        $config["base_url"] 	= base_url() . "admin/reported_users";
	       
	        $config["per_page"] 	= 20;
	        $config["uri_segment"] 	= 3;
	        $config['num_links'] 	= 1;
	        
	        $config['first_link']	= "<<";
	        $config['last_link']	= ">>";
	        
	        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
	        
	        $settings = $this->site_model->get_website_settings()->result_array();
	        $settings = $settings[0];
	    	
	    	$data["settings"] = $settings;
	    	
	    	$users = $this->user_model->get_last_reported_users_page($config["per_page"], $page, $this->session->userdata('user_id'))->result_array();
			$users_count = $this->user_model->record_reported_count(0);
			
		    $config["total_rows"] 	= $users_count;
		    $this->pagination->initialize($config);
	        
	        $data["users"] = $users;
	        $data["links"] = $this->pagination->create_links();
	        
	        $data["jscripts"] = array(base_url()."js/pages/admin_manage_users.js");
	    	
			$this->load->view('admin/reported_users', $data);
	   	}
    }
    
    function manage_users() 
    {
	    $data = array();
    	
    	$this->load->model('site_model');
    	$this->load->model("user_model");
	    
	    $settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $data["settings"] = $settings;
    	
    	$data["title"] = "Admin - Manage Users";
    	
    	
    	if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {
			
			$this->load->library("pagination");
		    
		    $config = array();
	        $config["base_url"] 	= base_url() . "admin/manage_users";
	       
	        $config["per_page"] 	= 20;
	        $config["uri_segment"] 	= 3;
	        $config['num_links'] 	= 1;
	        
	        $config['first_link']	= "<<";
	        $config['last_link']	= ">>";
	        
	        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
	    	
	    	$settings = $this->site_model->get_website_settings()->result_array();
	    	$settings = $settings[0];
	    	
	    	$data["settings"] = $settings;
	    	
	    	$users = $this->user_model->get_last_registered_users_page_admin($config["per_page"], $page, $this->session->userdata('user_id'))->result_array();
			$users_count = $this->user_model->record_count();
			
		    $config["total_rows"] 	= $users_count;
		    $this->pagination->initialize($config);
	        
	        $data["users"] = $users;
	        $data["links"] = $this->pagination->create_links();
	        
	        $data["jscripts"] = array(base_url()."js/pages/admin_manage_users.js");
	    	
			$this->load->view('admin/manage_users', $data);
	   	}
    }
    
    function manage_finances() 
    {
	    $data = array();
    	
    	$this->load->model('site_model');
    	$this->load->model('premium_model');
	    
	    $settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $coins = $this->premium_model->get_coins()->result_array();
	    $data["coins"] = $coins;
	    
	    $data["settings"] = $settings;
    	
    	$data["title"] = "Admin - Manage Payments";
    	
    	if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {
	    	
	    	$settings = $this->site_model->get_website_settings()->result_array();
	    	$settings = $settings[0];
	    	
	    	$data["settings"] = $settings;
	    	
	    	$data["jscripts"] = array(base_url()."js/pages/admin_manage_finances.js");
	    	
			$this->load->view('admin/manage_finances', $data);
	   	}
    }
        
    function manage_ads() 
    {
	    $data = array();
    	
    	$this->load->model('site_model');
	    
	    $settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $data["settings"] = $settings;
    	
    	$data["title"] = "Admin - Manage Ads";
    	
    	if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {
	    	
	    	$settings = $this->site_model->get_website_settings()->result_array();
	    	$settings = $settings[0];
	    	
	    	$data["settings"] = $settings;
	    	
	    	$data["jscripts"] = array(base_url()."js/pages/admin_manage_ads.js");
	    	
			$this->load->view('admin/manage_ads', $data);
	   	}
    }
    
    function manage_languages() 
    {
	    $data = array();
    	
    	$this->load->model('site_model');
    		    
	    $settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $language_redirections = $this->site_model->get_language_redirections()->result_array();
	    $data["language_redirections"] = $language_redirections;
	    
	    $nb_redirections = $this->site_model->count_language_redirections();
	    $data["nb_redirections"] = $nb_redirections;
	    
	    $data["settings"] = $settings;
    	
    	$data["title"] = "Admin - Manage Languages";
    	
    	if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {	    	
			$data["jscripts"] = array(base_url()."js/pages/admin_manage_languages.js");
			$this->load->view('admin/manage_languages', $data);
	   	}
    }
    
    function settings() 
    {
	    $data = array();
    	
    	$this->load->model('site_model');
	    
	    $settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $data["settings"] = $settings;
    	
    	$data["title"] = "Admin - Settings";
    	
    	if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {	    	
			$data["jscripts"] = array(base_url()."js/pages/admin_settings.js");
			$this->load->view('admin/settings', $data);
	   	}
    }
   
    function social_profiles() 
    {
	    $data = array();
    	
    	$this->load->model('site_model');
	    
	    $settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    
	    $data["settings"] = $settings;
    	
    	$data["title"] = "Admin - Social Profiles";
    	
    	if($this->session->userdata("user_rank") < 1) {
			show_error("You can't access to this part of the website.");
		} else {	    	
			$data["jscripts"] = array(base_url()."js/pages/admin_social_profiles.js");
			$this->load->view('admin/social_profiles', $data);
	   	}
    }
    
    function save_payment_settings()
    {
	    $data = array();
	   	$data["error"] = 0;

	   	if(DEMO_MODE == 1) {
			$data["error_msg"] = "You can't edit these settings in demo mode.";
		    $data["error"] = 500;
	    } else {
	    	if($this->session->userdata("user_rank") < 1) {
				$data["error_msg"] = "You are not logged in anymore.";
				$data["error"] = 999;
			} else {
				$currency = $this->input->post('currency');
				$enable_payment = $this->input->post('enable_payment');
				$inapp_price = $this->input->post('inapp_price');
				$paypal_api_username = $this->input->post('paypal_api_username');
				$paypal_api_pw = $this->input->post('paypal_api_pw');
				$paypal_api_sign = $this->input->post('paypal_api_sign');
				$stripe_secret_key = $this->input->post('stripe_secret_key');
				$stripe_pub_key = $this->input->post('stripe_pub_key');
				$price_100_coins = $this->input->post('price_100_coins');
				$price_500_coins = $this->input->post('price_500_coins');
				$price_1000_coins = $this->input->post('price_1000_coins');
				$see_who_loves_you_price = $this->input->post('see_who_loves_you_price');
				$browse_invisibly_price = $this->input->post('browse_invisibly_price');
				$featured_one_week_price = $this->input->post('featured_one_week_price');
				$featured_one_month_price = $this->input->post('featured_one_month_price');
				
				if($enable_payment == 1) {
					// OK! We can update the settings.
					$this->load->model("site_model");
					$this->load->model("premium_model");
					
					$this->site_model->update_settings(array(
						"enable_payments"	 		=> 1,
						"inapp_price"				=> $inapp_price,
						"paypal_api_username"		=> $paypal_api_username,
						"paypal_api_pw"				=> $paypal_api_pw,
						"paypal_api_sign"			=> $paypal_api_sign,
						"stripe_secret_key"			=> $stripe_secret_key,
						"stripe_pub_key"			=> $stripe_pub_key,
						"see_who_loves_you_price"	=> $see_who_loves_you_price,
						"browse_invisibly_price"	=> $browse_invisibly_price,
						"featured_one_week_price"	=> $featured_one_week_price,
						"featured_one_month_price"	=> $featured_one_month_price,
						"currency"					=> $currency
					));
					
					$this->premium_model->delete_coins();
					
					$this->premium_model->create_coin(100, $price_100_coins);
					$this->premium_model->create_coin(500, $price_500_coins);
					$this->premium_model->create_coin(1000, $price_1000_coins);
					
					$data["error"] = 0;
				} else {
					// OK! We can update the settings.
					$this->load->model("site_model");
					
					$this->site_model->update_settings(array(
						"enable_payments"	 		=> 0
					));
					
					$data["error"] = 0;
				}
			}
		}
		
		header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function search_user()
    {
	    $data = array();
	   	$data["error"] = 0;

	    if($this->session->userdata("user_rank") < 1) {
			$data["error_msg"] = "You are not logged in anymore.";
			$data["error"] = 999;
		} else {
			$this->load->model('user_model');
			
			$username = $this->input->post("username");
			
			$user_id = $this->user_model->get_user_by_username_like($username)->result_array();
			
			if(sizeof($user_id) == 0) {
				$data["error"] = 998;
				$data["error_msg"] = "This user has not been found in the database.";
			} else {
			
				$data["user_id"] = $user_id[0]["id"];
				$data["error"] = 0;
			
			}
 		}
		
	    header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function save_social_profiles()
    {
	   	$data = array();
	   	$data["error"] = 0;

	   	if(DEMO_MODE == 1) {
			$data["error_msg"] = "You can't edit these settings in demo mode.";
		    $data["error"] = 500;
	    } else {
		    if($this->session->userdata("user_rank") < 1) {
				$data["error_msg"] = "You are not logged in anymore.";
				$data["error"] = 999;
			} else {
			    $fb_url = $this->input->post('fb_url');
				$twitter_url = $this->input->post('twitter_url');
				$instagram_url = $this->input->post('instagram_url');
				$gplus_url = $this->input->post('gplus_url');
				
				if(!empty($fb_url) && filter_var($fb_url, FILTER_VALIDATE_URL) === false) {
					$data["error"] = 998;
					$data["error_msg"] = "Your Facebook Page URL is not valid.";
				} else if(!empty($twitter_url) && filter_var($twitter_url, FILTER_VALIDATE_URL) === false) {
					$data["error"] = 997;
					$data["error_msg"] = "Your Twitter Page URL is not valid.";
				} else if(!empty($instagram_url) && filter_var($instagram_url, FILTER_VALIDATE_URL) === false) {
					$data["error"] = 996;
					$data["error_msg"] = "Your Instagram Page URL is not valid.";
				} else if(!empty($gplus_url) && filter_var($gplus_url, FILTER_VALIDATE_URL) === false) {
					$data["error"] = 995;
					$data["error_msg"] = "Your Google+ Page URL is not valid.";
				}
				
				if($data["error"] == 0) {
					// OK! We can update the settings.
					$this->load->model("site_model");
					
					$this->site_model->update_settings(array(
						"fb_url"	 		=> $fb_url,
						"twitter_url"		=> $twitter_url,
						"instagram_url" 	=> $instagram_url,
						"googleplus_url" 	=> $gplus_url
					));
				}
			}
		}
							    
	    header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function save_ads_code()
    {
	    $data = array();

		if(DEMO_MODE == 1) {
			$data["error_msg"] = "You can't edit these settings in demo mode.";
		    $data["error"] = 500;
	    } else {
		    if($this->session->userdata("user_rank") < 1) {
				$data["error_msg"] = "You are not logged in anymore.";
				$data["error"] = 999;
			} else {
				$ads_code = $this->input->post('ads_code');
				
				if(!empty($ads_code) && strlen($ads_code) < 5) {
					$data["error_msg"] = "Your ad code seems to be not a valid one.";
					$data["error"] = 998;
				} else {
					// OK! We can update the settings.
					$this->load->model("site_model");
					
					$this->site_model->update_settings(array(
						"ads_code"	=> $ads_code
					));
					
					$data["error"] = 0;
				}
								    
	
			}
		}
		
		header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function save_edit_language_redirection()
    {
	    $data = array();
	    
	    $country_comes_from = $this->input->post("country_comes_from");
	    $redirects_to = $this->input->post("redirects_to");
	    $id = $this->input->post("id");

		if(DEMO_MODE == 1) {
			$data["error_msg"] = "You can't edit these settings in demo mode.";
		    $data["error"] = 500;
	    } else {
		    if($this->session->userdata("user_rank") < 1) {
				$data["error_msg"] = "You are not logged in anymore.";
				$data["error"] = 999;
			} else {
							
				// OK! We can update the settings.
				$this->load->model("site_model");
				
				// Check if redirection already exists
				if($this->site_model->check_language_redirection_exists_not_id($id,$country_comes_from)) {
					$data["error_msg"] = "A language redirection for this country already exists.";
					$data["error"] = 998;
				} else {
					
					$this->site_model->edit_language_redirection($id, array("country" => $country_comes_from, "language" => $redirects_to));		
					$data["error"] = 0;
				
				}
			}
		}
		
	    header('Content-type: application/json;');
		echo json_encode($data); 
    }
    
    function save_language_redirection()
    {
	    $data = array();
	    
	    $redirects_to = $this->input->post("redirects_to");

		if(DEMO_MODE == 1) {
			$data["error_msg"] = "You can't edit these settings in demo mode.";
		    $data["error"] = 500;
	    } else {
		    if($this->session->userdata("user_rank") < 1) {
				$data["error_msg"] = "You are not logged in anymore.";
				$data["error"] = 999;
			} else {
							
				// OK! We can update the settings.
				$this->load->model("site_model");
				
				// Check if redirection already exists
				if($this->site_model->check_language_exists($redirects_to)) {
					$data["error_msg"] = "This language option already exists.";
					$data["error"] = 998;
				} else {
					
					$this->site_model->add_language_redirection("", $redirects_to);		
					$data["error"] = 0;
				
				}
			}
		}
		
	    header('Content-type: application/json;');
		echo json_encode($data); 
	}
    
    function save_default_language()
    {
	    $data = array();
	    
	    $default_language = $this->input->post("language");

		if(DEMO_MODE == 1) {
			$data["error_msg"] = "You can't edit these settings in demo mode.";
		    $data["error"] = 500;
	    } else {
		    if($this->session->userdata("user_rank") < 1) {
				$data["error_msg"] = "You are not logged in anymore.";
				$data["error"] = 999;
			} else {
				// OK! We can update the settings.
				$this->load->model("site_model");
									
				if($default_language == "") {
					$default_language = "english";
				}
				
				$this->site_model->update_settings(array(
					"default_language"	=> $default_language
				));
				
				$data["error"] = 0;
			}
		}
		
	    header('Content-type: application/json;');
		echo json_encode($data);  
    }
    
    function save_theme_settings()
    {
	    $data = array();

		if(DEMO_MODE == 1) {
			$data["error_msg"] = "You can't edit these settings in demo mode.";
		    $data["error"] = 500;
	    } else {
		    $f_bg_color = $this->input->post('f_bg_color');
			$f_txt_color = $this->input->post('f_txt_color');
			$s_bg_color = $this->input->post('s_bg_color');
			$s_txt_color = $this->input->post('s_txt_color');
			$s_third_color = $this->input->post('s_third_color');
			$bgcolor_main = $this->input->post('bgcolor_main');
			$textcolor_navbar = $this->input->post('textcolor_navbar');
			$main_block_color = $this->input->post('main_block_color');
			$main_txt_color = $this->input->post('main_txt_color');
			$logo_color = $this->input->post('logo_color');
			
			$error_format = false;
			
			if(!empty($main_txt_color)) {
				if(!preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", $main_txt_color)) {
					$error_format = true;	
				}
			}
			
			if(!empty($main_block_color)) {
				if(!preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", $main_block_color)) {
					$error_format = true;	
				}
			}
			
			if(!empty($textcolor_navbar)) {
				if(!preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", $textcolor_navbar)) {
					$error_format = true;	
				}
			}
			
			if(!empty($bgcolor_main)) {
				if(!preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", $bgcolor_main)) {
					$error_format = true;	
				}
			}
			
			if(!empty($s_third_color)) {
				if(!preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", $s_third_color)) {
					$error_format = true;	
				}
			}
			
			if(!empty($s_txt_color)) {
				if(!preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", $s_txt_color)) {
					$error_format = true;	
				}
			}
			
			if(!empty($f_bg_color)) {
				if(!preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", $f_bg_color)) {
					$error_format = true;	
				}
			}
			
			if(!empty($f_txt_color)) {
				if(!preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", $f_txt_color)) {
					$error_format = true;	
				}
			}
			
			if(!empty($s_bg_color)) {
				if(!preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", $s_bg_color)) {
					$error_format = true;	
				}
			}
			
			if($error_format) {
				$data["error_msg"] = "One of your color is not an hex value. You need to respect the following format : #000000.";
				$data["error"] = 999;
			} else {
				$data["error"] = 0;
				
				// OK! We can update the settings.
				$this->load->model("site_model");
													
				$this->site_model->update_settings(array(
					"f_bg_color"	 	=> $f_bg_color,
					"f_txt_color"		=> $f_txt_color,
					"s_bg_color" 		=> $s_bg_color,
					"s_txt_color" 		=> $s_txt_color,
					"s_third_color"		=> $s_third_color,
					"bgcolor_main"		=> $bgcolor_main,
					"textcolor_navbar"	=> $textcolor_navbar,
					"main_block_color"	=> $main_block_color,
					"main_txt_color"	=> $main_txt_color,
					"logo_color"		=> $logo_color
				));
			}
		}
		
		header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function save_general_settings()
    {
	    $data = array();

		if(DEMO_MODE == 1) {
			$data["error_msg"] = "You can't edit these settings in demo mode.";
		    $data["error"] = 500;
	    } else {
		    if($this->session->userdata("user_rank") < 1) {
				$data["error_msg"] = "You are not logged in anymore.";
				$data["error"] = 999;
			} else {
			    $web_title = $this->input->post('web_title');
				$web_tagline = $this->input->post('web_tagline');
				$web_desc = $this->input->post('web_desc');
				$web_keywords = $this->input->post('web_keywords');
				$web_captcha = $this->input->post('web_captcha');
				$site_analytics = $this->input->post('site_analytics');
				$age_limit = $this->input->post("age_limit");
				$upload_limit = $this->input->post("upload_limit");
				$online_delay = $this->input->post("online_delay");
				$hide_country = $this->input->post("hide_country");
				$hide_timeline = $this->input->post("hide_timeline");
				$extra_fields = $this->input->post("extra_fields");
								
				if(strlen($web_title) < 3) {
					$data["error_msg"] = "The <b>Website Name</b> has to be at least 3 characters long.";
					$data["error"] = 998;
				} else if(strlen($web_tagline) < 3) {
					$data["error_msg"] = "The <b>Website Tagline</b> has to be at least 3 characters long.";
					$data["error"] = 997;
				} else if(strlen($web_desc) < 5) {
					$data["error_msg"] = "The <b>Website Description</b> has to be at least 5 characters long.";
					$data["error"] = 996;
				} else if(strlen($web_keywords) < 5) {
					$data["error_msg"] = "The <b>Website Keywords</b> have to be at least 5 characters long.";
					$data["error"] = 995;
				} else {
					// OK! We can update the settings.
					$this->load->model("site_model");
										
					$this->site_model->update_settings(array(
						"site_name"	 		=> $web_title,
						"site_tagline"		=> $web_tagline,
						"site_description" 	=> $web_desc,
						"site_tags" 		=> $web_keywords,
						"web_captcha"		=> $web_captcha,
						"site_analytics"	=> $site_analytics,
						"site_age_limit"	=> $age_limit,
						"upload_limit"		=> $upload_limit,
						"online_delay"		=> $online_delay,
						"hide_country"		=> $hide_country, 
						"hide_timeline"		=> $hide_timeline,
						"user_extra_fields" => $extra_fields
					));
					
					$data["error"] = 0;
				}
			}
		}
		
	    header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function get_language_redirection()
    {
	    $data = array();

		if(DEMO_MODE == 1) {
			$data["error_msg"] = "You can't edit this in demo mode.";
		    $data["error"] = 500;
	    } else {
		    if($this->session->userdata("user_rank") < 1) {
				$data["error_msg"] = "You are not logged in anymore.";
				$data["error"] = 999;
			} else {
				
				$redir_id = $this->input->post("redir_id");
				
				$this->load->model('site_model');
					
				// GET REDIRECTION
				$redir = $this->site_model->get_language_redirection($redir_id);
				
				$data["redir"] = $redir;
				
				$data["error"] = 0;
			}
		}
		
		header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function delete_redirection()
    {
		$data = array();

		if(DEMO_MODE == 1) {
			$data["error_msg"] = "You can't delete this in demo mode.";
		    $data["error"] = 500;
	    } else {
		    if($this->session->userdata("user_rank") < 1) {
				$data["error_msg"] = "You are not logged in anymore.";
				$data["error"] = 999;
			} else {
				
				$redir_id = $this->input->post("redir_id");
				
				$this->load->model('site_model');
					
				// DELETE REDIRECTION
				$this->site_model->delete_redirection($redir_id);
				
				$data["error"] = 0;
			}
		}
		
		header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function delete_page()
    {
	   	$data = array();

		if(DEMO_MODE == 1) {
			$data["error_msg"] = "You can't delete this in demo mode.";
		    $data["error"] = 500;
	    } else {
		    if($this->session->userdata("user_rank") < 1) {
				$data["error_msg"] = "You are not logged in anymore.";
				$data["error"] = 999;
			} else {
				
				$page_id = $this->input->post("id");
				
				$this->load->model('site_model');
					
				// DELETE PAGE
				$this->site_model->delete_page($page_id);
				
				$data["error"] = 0;
			}
		}
		
		header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function delete_forum_category() {
	    $data = array();

		if(DEMO_MODE == 1) {
			$data["error_msg"] = "You can't delete this in demo mode.";
		    $data["error"] = 500;
	    } else {
		    if($this->session->userdata("user_rank") < 1) {
				$data["error_msg"] = "You are not logged in anymore.";
				$data["error"] = 999;
			} else {
				
				$cat_id = $this->input->post("id");
				
				$this->load->model('user_model');
				$this->load->model('forum_model');
					
					// DELETE FORUM MESSAGES
					$this->forum_model->delete_messages_from_cat($cat_id);
					
					// DELETE POKES
					$this->forum_model->delete_category($cat_id);
					
					$data["error"] = 0;
			}
		}
		
		header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function delete_user()
    {
	    $data = array();

		if(DEMO_MODE == 1) {
			$data["error_msg"] = "You can't edit these settings in demo mode.";
		    $data["error"] = 500;
	    } else {
		    if($this->session->userdata("user_rank") < 1) {
				$data["error_msg"] = "You are not logged in anymore.";
				$data["error"] = 999;
			} else {
				$user_id = $this->input->post("user_id");
				
				$this->load->model('user_model');
				$this->load->model('pm_model');
				
				$user = $this->user_model->get($user_id)->result_array();
				$user = $user[0];
				
				if($user["rank"] == 2) {
					$data["error_msg"] = "You are not allowed to remove the super admin.";
					$data["error"] = 998;
				} else {
					
					// DELETE PM CONV
					$this->pm_model->delete_conversations($user_id);
					
					// DELETE POKES
					$this->user_model->delete_friends($user_id);
					
					// DELETE LOVES
					$this->user_model->delete_loves($user_id);
					
					// DELETE USER PROFIL VISITS
					$this->user_model->delete_profile_visits($user_id);
					
					// DELETE USER INFOS
					$this->user_model->delete_user_info($user_id);
					
					// DELETE USER
					$this->user_model->delete_user($user_id);
					
					$data["error"] = 0;
				
				}
			}
		}
		
		header('Content-type: application/json;');
		echo json_encode($data);
    }
       
    private function send_mail($subject, $email, $message, $title, $username)
	{
		$this->load->model("site_model");
		
		$settings = $this->site_model->get_website_settings()->result_array();
		$settings = $settings[0];
		
		$this->load->library('email');
		$config = array (
			'mailtype' => 'html',
			'charset'  => 'utf-8',
			'priority' => '1'
		);
		$this->email->initialize($config);
		$this->email->from("noreply@email.com", $settings["site_name"]);
		$this->email->to($email);
		$this->email->subject($subject);
		
		$datamail = array();
		$datamail["title"] = $title;
		$datamail["username"] = $username;
		$datamail["content"] = $message;
		$datamail["settings"] = $settings;
		
		$message = $this->load->view('email/send-content',$datamail,TRUE);
		$this->email->message($message);
		$this->email->send(); 
	}
}
