<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	
	function logout()
    {
	    $this->session->unset_userdata('user_id');
		$this->session->unset_userdata('user_username');
		$this->session->unset_userdata('user_snapchat');
		$this->session->unset_userdata('user_avatar');
		$this->session->unset_userdata('user_firstform');
		$this->session->unset_userdata('fb_token');
		
		$this->session->sess_destroy();
		redirect('','refresh');  // <!-- note that
    }
    
    function fb_login()
	{
		$this->lang->load("user");
		
		if(DEMO_MODE == 1) {
			redirect(base_url() . "?error=demo_mode");
			exit;
		}
			
		if ($this->facebook->logged_in())
		{
			$fb_user = $this->facebook->user();

			// Facebook login OK
			if ($fb_user['code'] === 200)
			{
				unset($fb_user['data']['permissions']);
				$user = $fb_user['data'];
				
				$this->load->model("user_model");
				$this->load->model("site_model");
				
				// User is logging in
				if($this->user_model->check_facebook_user_exists($user["id"])) {
					$user_log = $this->user_model->get_facebook_user($user["id"])->result_array();

					$user_log = $user_log[0];
					
					if($user_log["banned"] == 1) {
						redirect(base_url() . "?action=login_banned");
					} else {
						
						$settings = $this->site_model->get_website_settings()->result_array();
						$settings = $settings[0];
						
						$avatar = base_url() . "images/avatar.png";
				
						if(($user_log["thumb_url"] == "" || $user_log["photostatus"] == 0) && $user_log["gender"] == 0) {
							$avatar = base_url() . "images/avatar.png";
						} else if(($user_log["thumb_url"] == "" || $user_log["photostatus"] == 0) && $user_log["gender"] == 1) {
							$avatar = base_url() . "images/avatar.png";
						} else if($user_log["thumb_url"] != "") {
							$avatar = $user_log["thumb_url"];
						}
						
						$this->session->set_userdata(
							array(
								"user_id"		=> $user_log["uid"],
								"fb_id"			=> $user_log["fb_id"],
								"user_username"	=> $user_log["username"],
								"user_email"	=> $user_log["email"],
								"user_rank"		=> $user_log["rank"],
								"user_firstform"=> $user_log["first_step_form"],
								"user_avatar"	=> $avatar
							)
						);
						
						$this->user_model->update_login($user_log["uid"]);
						
						if($user_log["first_step_form"] == 1)
							redirect(base_url() . "?success=login_success");
						else
							redirect(base_url() . "user/firstloginfb?error=first_login");
					}
				} 
				// User is creating a new account
				else {
										
					if($this->input->cookie('bepoke_gender') == "") {
						header("Location: " . base_url() . "?error=cookie");	
						exit;
					}	
					
					$uid = $this->user_model->create_social_account($user["id"], $user["name"], $user["email"]);
					
					$gender			= $this->security->xss_clean($this->input->cookie('bepoke_gender', TRUE));
					$interested_in	= $this->security->xss_clean($this->input->cookie('bepoke_interested_in', TRUE));
					
					$this->user_model->create_info($uid, $gender, $interested_in);
					
					$img = file_get_contents('https://graph.facebook.com/'.$user["id"].'/picture?type=large');
					
					// Check if the directory already exists
		        	if (!file_exists("./uploads/photos/" . $uid . "/")) {
		        		mkdir("./uploads/photos/" . $uid . "/");
		        		mkdir("./uploads/photos/" . $uid . "/thumbnails/");
		        	}
		        	
		        	$file_name = time().'.jpg';
					
					$file = "./uploads/photos/" . $uid . "/".$file_name;
					$fileEcho = "/uploads/photos/" . $uid . "/".$file_name;
					
					file_put_contents($file, $img);
					
					$file_2 = "./uploads/photos/" . $uid . "/thumbnails/".$file_name;
					$file_2Echo = "/uploads/photos/" . $uid . "/thumbnails/".$file_name;
					file_put_contents($file_2, $img);
					
					$this->load->model("photo_model");
					$pic_id = $this->photo_model->add($fileEcho, $file_2Echo, $uid);
					
					$this->user_model->update_info($uid, array("main_photo" => $pic_id));
										
					$this->session->set_userdata(
						array(
							"user_id"		=> $uid,
							"fb_id"			=> $user["id"],
							"user_username"	=> $user["name"],
							"user_email"	=> $user["email"],
							"user_rank"		=> 0,
							"first_login"	=> 0,
							"user_avatar"	=> 'uploads/photos/' . $uid . '/' . $file_name
						)
					);
					
					redirect(base_url() . "user/firstloginfb");
				}
			} else {
				redirect(base_url() . "?action=login_error");
			}
		}
	}
    
    function lovesipn()
	{
		$this->load->model('user_model');
			
		$message_id	= $_GET['message_id'];
		$service_id	= $_GET['service_id'];
		$shortcode	= $_GET['shortcode'];
		$keyword	= $_GET['keyword'];
		$message	= $_GET['message'];
		$sender	= $_GET['sender'];
		$operator	= $_GET['operator'];
		$country	= $_GET['country'];
		$custom	= $_GET['custom'];
		$points	= $_GET['points'];
		$price	= $_GET['price'];
		$currency	= $_GET['currency'];
			
		
		if(!empty($custom)) {
			$this->user_model->activate_purchase($custom, "see_loves_paygol");
		}
		
	}
    
    function apply_filters()
    {	    
	    $filter_gender = $this->input->post('filter_gender');
		$filter_age_from = $this->input->post('filter_age_from');
		$filter_age_to = $this->input->post('filter_age_to');
		$filter_country = $this->input->post('filter_country');
		$filter_city = $this->input->post('filter_city');
		$filter_sort = $this->input->post('filter_sort');
		
		if($filter_country == "0") {
			$filter_city = "";
		}
										
		$this->session->set_userdata('filter_gender', $filter_gender);
		$this->session->set_userdata('filter_age_from', $filter_age_from);
		$this->session->set_userdata('filter_age_to', $filter_age_to);
		$this->session->set_userdata('filter_country', $filter_country);
		$this->session->set_userdata('filter_city', $filter_city);
		$this->session->set_userdata('filter_sort', $filter_sort);
		
		$data = 1;
			    
	    header('Content-type: application/json;');
		echo json_encode($data);
    } 
    
    function report()
    {
    	if(!$this->session->userdata("user_id")) {	
	    	// Not logged in
    		$result["result"] = 999;
    	} else {
    	
    		$user_id = $this->session->userdata("user_id");
    		$profile_id = $this->input->post('profile_id');
    		
    		if($profile_id == $user_id) {
	    		$result["result"] = 998;
    		} else {
	    	    $this->load->model('user_model', 'userManager');
	    	        	    
	    	    $this->userManager->report($this->session->userdata("user_id"), $profile_id);
	    	    $result["result"] = 1;
    	    }
    	}
    
	    header('Content-type: application/json;');
		echo json_encode($result);
    }
    
    function get_filters()
    {	    
		$data = array();
		
	    $filter_gender = $this->session->userdata("filter_gender");
		$filter_age_from = $this->session->userdata("filter_age_from");
		$filter_age_to = $this->session->userdata('filter_age_to');
		$filter_country = $this->session->userdata('filter_country');
		$filter_city = $this->security->xss_clean($this->session->userdata('filter_city'));
		$filter_sort = $this->session->userdata('filter_sort');
									
		$data["filter_gender"] = $filter_gender;
		$data["filter_age_from"] = $filter_age_from;
		$data["filter_age_to"] = $filter_age_to;
		$data["filter_country"] = $filter_country;
		$data["filter_city"] = $filter_city;
		$data["filter_sort"] = $filter_sort;
		
	    header('Content-type: application/json;');
		echo json_encode($data);
    } 
	    
    function reset_filters()
    {
	    $this->session->unset_userdata('filter_gender');
		$this->session->unset_userdata('filter_age_from');
		$this->session->unset_userdata('filter_age_to');
		$this->session->unset_userdata('filter_country');
		$this->session->unset_userdata('filter_city');
		$this->session->unset_userdata('filter_sort');
	    
	    $data = 1;
	    
	    header('Content-type: application/json;');
		echo json_encode($data);
    } 
    
    function block()
    {
	    if(DEMO_MODE == 1) {
		    $result["result"] = 500;
	    } else {
	    	if(!$this->session->userdata("user_id")) {	
		    	// Not logged in
	    		$result["result"] = 999;
	    	} else {
	    	
	    		$user_id = $this->session->userdata("user_id");
	    		$profile_id = $this->input->post('profile_id');
	    		
	    		if($profile_id == $user_id) {
		    		$result["result"] = 998;
	    		} else {
		    	    $this->load->model('user_model', 'userManager');
		    	        	    
		    	    $this->userManager->block($this->session->userdata("user_id"), $profile_id);
		    	    $result["result"] = 1;
	    	    }
	    	}
    	}
    
	    header('Content-type: application/json;');
		echo json_encode($result);
    }
    
    function unblock()
    {
    	if(!$this->session->userdata("user_id")) {	
	    	// Not logged in
    		$result["result"] = 999;
    	} else {
    	
			$profile_id = $this->input->post('profile_id');
    	    $this->load->model('user_model', 'userManager');
    	        	    
    	    $this->userManager->unblock($this->session->userdata("user_id"), $profile_id);
    	    $result["result"] = 1;
    	}
    
	    header('Content-type: application/json;');
		echo json_encode($result);
    }
    
    function friends() 
    {
	    $data = array();
	    
    	$this->load->model("user_model");
		$this->load->model('site_model');

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
    	$this->lang->load('user_lang', $lng_opt);
    	
    	$data["title"] = $this->lang->line("your_friends_title");
    	
    	if($this->session->userdata("user_firstform") != 1)
    	{
	    	redirect(base_url() . "user/firstlogin?redirect=true");
    	} else {
	    	    	    	
	    	if($this->session->userdata('user_id')) {
	    		$this->load->model("pm_model");
	    		$nb_pm = $this->pm_model->count_unread($this->session->userdata('user_id'));
		   		$data["nb_pm"] = $nb_pm;
				$new_profile_visits = $this->user_model->count_new_profile_visits($this->session->userdata("user_id"));
				$data["nb_new_visits"] = $new_profile_visits;
				
				$new_poke_requests = $this->user_model->count_new_requests($this->session->userdata("user_id"));
				$data["nb_new_requests"] = $new_poke_requests;
				
				$new_loves = $this->user_model->count_new_loves($this->session->userdata("user_id"));
				$data["nb_new_loves"] = $new_loves;
				
				$new_friends = 0;
				$data["nb_new_friends"] = 0;
				
				$data["total_notif"] = intval($new_profile_visits) + intval($nb_pm) + intval($new_loves) + intval($new_poke_requests) + intval($new_friends);

				$datauser = $this->user_model->get($this->session->userdata('user_id'))->result_array();
				$datauser = $datauser[0];
				$data["user"] = $datauser;
				
				$this->user_model->update_last_activity($this->session->userdata('user_id'));
				$this->user_model->update_friends_to_seen($this->session->userdata('user_id'));
		    
			    
			    
			    // Get custom pages
				if($this->site_model->count_custom_pages() > 0) {
					$data["pages"] = $this->site_model->get_pages()->result_array();
				} else {
					$data["pages"] = null;
				}
		    		    				    
				$this->load->library("pagination");
			    
			    $config = array();
		        $config["base_url"] 	= base_url() . "user/friends";
		       
		        $config["per_page"] 	= 20;
		        $config["uri_segment"] 	= 3;
		        $config['num_links'] 	= 1;
		        
		        $config['first_link']	= "<<";
		        $config['last_link']	= ">>";
		        
		        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

	        	$users = $this->user_model->get_last_friends($config["per_page"], $page, $this->session->userdata('user_id'))->result_array();
				$users_count = $this->user_model->record_last_friends_count($this->session->userdata('user_id'));
			    
			    $config["total_rows"] 	= $users_count;
			    $this->pagination->initialize($config);
		        
		        $data["users"] = $users;
		        $data["links"] = $this->pagination->create_links();
		        
    	        $data["jscripts"] = array(
					base_url() . "js/pages/friends.js"
				);
		
			   	$this->load->view('user/friends', $data);
			} else {
			    redirect(base_url() . "?error=fb_session");
		    }
		   	
	   	}
    }
    
    function requests() 
    {
	    $data = array();
    	
    	$this->load->model("user_model");
    	$this->load->model('site_model');
	    
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
    	
    	$this->lang->load('user_lang', $lng_opt);
    	$this->lang->load('site_lang', $lng_opt);
    	
    	$data["title"] = $this->lang->line("friend_requests_title");
    	
    	if($this->session->userdata("user_firstform") != 1)
    	{
	    	redirect(base_url() . "user/firstlogin?redirect=true");
    	} else {
	    	    	    	
	    	if($this->session->userdata('user_id')) {
	    		$this->load->model("pm_model");
	    		$nb_pm = $this->pm_model->count_unread($this->session->userdata('user_id'));
		   		$data["nb_pm"] = $nb_pm;
				$new_profile_visits = $this->user_model->count_new_profile_visits($this->session->userdata("user_id"));
				$data["nb_new_visits"] = $new_profile_visits;
				
				$new_poke_requests = $this->user_model->count_new_requests($this->session->userdata("user_id"));
				$data["nb_new_requests"] = $new_poke_requests;
				
				$new_friends = $this->user_model->count_new_friends($this->session->userdata("user_id"));
				$data["nb_new_friends"] = $new_friends;
				
				$new_loves = $this->user_model->count_new_loves($this->session->userdata("user_id"));
				$data["nb_new_loves"] = $new_loves;
								
				$data["total_notif"] = intval($new_profile_visits) + intval($nb_pm) + intval($new_loves) + intval($new_poke_requests) + intval($new_friends);
								
				$datauser = $this->user_model->get($this->session->userdata('user_id'))->result_array();
				$data["user"] = $datauser[0];
				
				$this->user_model->update_last_activity($this->session->userdata('user_id'));
				$this->user_model->update_requests_to_seen($this->session->userdata('user_id'));
		    
			    
			    
			    // Get custom pages
				if($this->site_model->count_custom_pages() > 0) {
					$data["pages"] = $this->site_model->get_pages()->result_array();
				} else {
					$data["pages"] = null;
				}
		    		    	
			    
				$this->load->library("pagination");
			    
			    $config = array();
		        $config["base_url"] 	= base_url() . "user/requests";
		       
		        $config["per_page"] 	= 20;
		        $config["uri_segment"] 	= 3;
		        $config['num_links'] 	= 1;
		        
		        $config['first_link']	= "<<";
		        $config['last_link']	= ">>";
		        
		        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

	        	$users = $this->user_model->get_last_received_requests($config["per_page"], $page, $this->session->userdata('user_id'))->result_array();
				$users_count = $this->user_model->record_last_received_requests_count($this->session->userdata('user_id'));
			    
			    $config["total_rows"] 	= $users_count;
			    $this->pagination->initialize($config);
		        
		        $data["users"] = $users;
		        $data["links"] = $this->pagination->create_links();
		        
		        $data["jscripts"] = array(
					base_url() . "js/pages/requests.js"
				);
		
			   	$this->load->view('user/requests', $data);
			} else {
			    redirect(base_url() . "?error=fb_session");
		    }
		   	
	   	}
    }
    
    function languages()
    {
	    $data = array();
	    	    
	    $this->load->model('site_model');
	    $this->load->model("pm_model");
	    $this->load->model("user_model");
	    	    
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
	    
	    $this->lang->load('user_lang', $lng_opt);
	    $this->lang->load('site_lang', $lng_opt);

	    
		$nb_pm = $this->pm_model->count_unread($this->session->userdata('user_id'));
   		$data["nb_pm"] = $nb_pm;
		$new_profile_visits = $this->user_model->count_new_profile_visits($this->session->userdata("user_id"));
		$data["nb_new_visits"] = $new_profile_visits;
		
		$new_poke_requests = $this->user_model->count_new_requests($this->session->userdata("user_id"));
		$data["nb_new_requests"] = $new_poke_requests;
		
		$new_friends = $this->user_model->count_new_friends($this->session->userdata("user_id"));
		$data["nb_new_friends"] = $new_friends;
		
		$new_loves = $this->user_model->count_new_loves($this->session->userdata("user_id"));
		$data["nb_new_loves"] = $new_loves;
						
		$data["total_notif"] = intval($new_profile_visits) + intval($nb_pm) + intval($new_loves) + intval($new_poke_requests) + intval($new_friends);
						
		$datauser = $this->user_model->get($this->session->userdata('user_id'))->result_array();
		$data["user"] = $datauser[0];
		
		$this->user_model->update_last_activity($this->session->userdata('user_id'));
	    	    
	    // Get custom pages
		if($this->site_model->count_custom_pages() > 0) {
			$data["pages"] = $this->site_model->get_pages()->result_array();
		} else {
			$data["pages"] = null;
		}
	    
	    $data["title"] = $this->lang->line("Languages");
	    
	    // Get languages
	    $data["languages"] = $this->site_model->get_language_redirections()->result_array();
	    
	    $data["jscripts"] = array();
	    
	    $this->load->view('user/languages', $data);
    }
    
    function switch_language($language_id)
    {
		$this->load->model('site_model');
		$this->load->model('user_model');
		
		if($this->site_model->check_language_id_exists($language_id) || $language_id == 0) {
			$this->user_model->update_info($this->session->userdata('user_id'), array("language_id" => $language_id));
			
			redirect(base_url() . "user/languages?action=language_saved");
		} else {
			show_404();
		}
    }
    
    function pay_with_paypal()
	{
		if($this->session->userdata("user_id"))
		{
			$this->load->library('merchant');
			$this->merchant->load('paypal_express');
			
			$this->load->model('site_model');
	    
		    $settingsapp = $this->site_model->get_website_settings()->result_array();
		    $settingsapp = $settingsapp[0];
						
			$settings = array(
			    'username' 		=> $settingsapp["paypal_api_username"],
			    'password'	 	=> $settingsapp["paypal_api_pw"],
			    'signature' 	=> $settingsapp["paypal_api_sign"],
			    'test_mode' 	=> false
			);
			
			$this->merchant->initialize($settings);
						
			$params = array(
				'amount' => $settingsapp["inapp_price"],
				'email' => "",
			    'currency' => 'USD',
			    'description' => "See Who Loves You",
			    'return_url' => base_url() . 'user/payment_paypal_return',
			    'cancel_url' => base_url() . 'user/loves?result=cancel'
		    );
		
			$response = $this->merchant->purchase($params);
			print_r($response);
		}
	}
	
	function payment_paypal_return()
	{
		$data = array();
		
		$this->load->model("user_model");
		
		$this->load->model('site_model');
	    
	    $settingsapp = $this->site_model->get_website_settings()->result_array();
	    $settingsapp = $settingsapp[0];
	    $data["settings"] = $settingsapp;
	    		    	
		$data["title"] = "See Who Loves You";
		
		if($this->session->userdata("user_id"))
		{
			$user = $this->user_model->get($this->session->userdata("user_id"))->result_array();
			$user = $user[0];
			
			$this->load->library('merchant');
			$this->merchant->load('paypal_express');
			
			$this->load->model("pm_model");
    		$nb_pm = $this->pm_model->count_unread($this->session->userdata('user_id'));
	   		$data["nb_pm"] = $nb_pm;
			$new_profile_visits = $this->user_model->count_new_profile_visits($this->session->userdata("user_id"));
			$data["nb_new_visits"] = $new_profile_visits;
			$new_requests = $this->user_model->count_new_requests($this->session->userdata("user_id"));
			$data["nb_new_requests"] = $new_requests;
			$new_loves = $this->user_model->count_new_loves($this->session->userdata("user_id"));
			$data["nb_new_loves"] = $new_loves;
			$new_friends = $this->user_model->count_new_friends($this->session->userdata("user_id"));
			$data["nb_new_friends"] = $new_friends;
						
			$data["total_notif"] = intval($new_profile_visits) + intval($nb_pm) + intval($new_loves) + intval($new_poke_requests) + intval($new_friends);
						
			$settings = array(
			    'username' 		=> $settingsapp["paypal_api_username"],
			    'password'	 	=> $settingsapp["paypal_api_pw"],
			    'signature' 	=> $settingsapp["paypal_api_sign"],
			    'test_mode' => false
			);
			
			$this->merchant->initialize($settings);
			
			$params = array(
				'amount' => $settingsapp["inapp_price"],
				'email' => "",
			    'currency' => 'USD',
			    'description' => "See Who Loves You",
			    'return_url' => base_url() . 'user/payment_paypal_return',
			    'cancel_url' => base_url() . 'user/loves?result=cancel'
		    );
			
			$response = $this->merchant->purchase_return($params);
			
			if ($response->status() == Merchant_response::COMPLETE)
			{
				// -- OK! Payement received, we create the order
				$data["users"] = $this->user_model->get_lasts_users_who_loved($this->session->userdata("user_id"), 70)->result_array();
				$this->user_model->update_loves_to_viewed($this->session->userdata("user_id"));
				
				$this->user_model->activate_purchase($this->session->userdata("user_id"), "see_loves_paypal");
				
				$data["payment_status"] = "Thank you very much! You can now always see who loves you on ". $settings["site_name"] ."!";
				$this->load->view('user/see_loves', $data);
			} else {
				$data["payment_status"] = "Whoops! An error occurred. Please verify your payment method.";
				$this->load->view('cart/see_loves_not_purchased', $data);
			}
		}
	}
    
    function loves()
    {
	    $this->load->model('site_model');
	    $this->load->model("user_model");
	    
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
	    
	    $this->lang->load('premium_lang', $lng_opt);
	    $this->lang->load('site_lang', $lng_opt);
	    $this->lang->load('user_lang', $lng_opt);
	    
	    if(!$this->session->userdata("user_id"))
		{
			header("Location: " . base_url());
		} else {
			if($this->session->userdata("user_firstform") != 1)
	    	{
		    	redirect(base_url() . "user/firstlogin?redirect=true");
	    	} else {

			    
			    // Get custom pages
				if($this->site_model->count_custom_pages() > 0) {
					$data["pages"] = $this->site_model->get_pages()->result_array();
				} else {
					$data["pages"] = null;
				}
		    		    	
				$data["title"] = $this->lang->line("see_who_loves_you_title");
		    
				
				
				$user = $this->user_model->get($this->session->userdata("user_id"))->result_array();
				$user = $user[0];
	
				$data["user"] = $user;
				$this->load->model("pm_model");
	    		$nb_pm = $this->pm_model->count_unread($this->session->userdata('user_id'));
		   		$data["nb_pm"] = $nb_pm;
				$new_profile_visits = $this->user_model->count_new_profile_visits($this->session->userdata("user_id"));
				$data["nb_new_visits"] = $new_profile_visits;
				$new_poke_requests = $this->user_model->count_new_requests($this->session->userdata("user_id"));
				$data["nb_new_requests"] = $new_poke_requests;
				$new_loves = $this->user_model->count_new_loves($this->session->userdata("user_id"));
				$data["nb_new_loves"] = $new_loves;
				$new_friends = $this->user_model->count_new_friends($this->session->userdata("user_id"));
				$data["nb_new_friends"] = $new_friends;
				
				$data["total_notif"] = intval($new_profile_visits) + intval($nb_pm) + intval($new_loves) + intval($new_poke_requests) + intval($new_friends);
				
				if($settings["enable_payments"] == 0) {
					$data["users"] = $this->user_model->get_lasts_users_who_loved($this->session->userdata("user_id"), 70)->result_array();
					$this->user_model->update_loves_to_viewed($this->session->userdata("user_id"));
					
					$this->load->view('user/see_loves', $data);
				} else {
					if($this->user_model->has_purchased_product($this->session->userdata("user_id"), "see_loves_stripe") || $this->user_model->has_purchased_product($this->session->userdata("user_id"), "see_loves_paypal") || $this->user_model->has_purchased_product($this->session->userdata("user_id"), "see_loves_paygol") || $this->user_model->has_purchased_product($this->session->userdata("user_id"), "see_loves_coins")) {
						$data["users"] = $this->user_model->get_lasts_users_who_loved($this->session->userdata("user_id"), 70)->result_array();
						$this->user_model->update_loves_to_viewed($this->session->userdata("user_id"));
						
						$data["jscripts"] = array(
							"https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places",
							base_url() . "js/pages/home.js"
						);
						
						$this->load->view('user/see_loves', $data);
					} else {
						$this->load->library('stripe');
						
						if($_POST) 
						{
							// Get the credit card details submitted by the form
							$token = $_POST['stripeToken'];
							
							$stripe_price = $settings["inapp_price"] * 100;
							
							$this->stripe->charge_card($stripe_price, $token, "See who Loves You", $settings["stripe_secret_key"]);
							
							$this->user_model->activate_purchase($this->session->userdata("user_id"), "see_loves_stripe");
							
							redirect(base_url()."user/loves?action=purchased_success");
						}
						
						$data["jscripts"] = array(
							base_url() . "js/pages/see_loves_not_purchased.js"
						);
		
						$this->load->view('user/see_loves_not_purchased', $data);
						
					}
				}
			}
		}
    }
    
    function profilevisits()
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
		
		$this->lang->load('user_lang', $lng_opt);
		$this->lang->load('site_lang', $lng_opt);

	    // Get custom pages
		if($this->site_model->count_custom_pages() > 0) {
			$data["pages"] = $this->site_model->get_pages()->result_array();
		} else {
			$data["pages"] = null;
		}
		    		    
	    if($this->session->userdata("user_id")) {
	    	    
	    	if($this->session->userdata("user_firstform") == 0) {
		    	redirect( base_url() . "user/firstlogin?redirect=true" );
	    	}
	    	
	    	
			$this->user_model->update_profile_visits_to_viewed($this->session->userdata("user_id"));

	    	$this->load->model('pm_model');
    		$nb_pm = $this->pm_model->count_unread($this->session->userdata('user_id'));
	   		$data["nb_pm"] = $nb_pm;
			$new_profile_visits = $this->user_model->count_new_profile_visits($this->session->userdata("user_id"));
			$data["nb_new_visits"] = $new_profile_visits;
			$new_poke_requests = $this->user_model->count_new_requests($this->session->userdata("user_id"));
			$data["nb_new_requests"] = $new_poke_requests;
			$new_friends = $this->user_model->count_new_friends($this->session->userdata("user_id"));
			$data["nb_new_friends"] = $new_friends;
			$new_loves = $this->user_model->count_new_loves($this->session->userdata("user_id"));
			$data["nb_new_loves"] = $new_loves;
			
			$data["total_notif"] = intval($new_profile_visits) + intval($nb_pm) + intval($new_loves) + intval($new_poke_requests) + intval($new_friends);
				    	
		    $data["title"] = $this->lang->line("last_profile_visits_title");
		    $data["visitors"] = $this->user_model->get_profile_visits($this->session->userdata('user_id'), 20)->result_array();
			
			$this->user_model->update_last_activity($this->session->userdata('user_id'));
			
			// Get infos from DB
			$user = $this->user_model->get($this->session->userdata("user_id"))->result_array();	
			
	    	$data["user"] = $user[0];   	
	    	
	    	$data["jscripts"] = array(
				base_url() . "js/pages/profilevisits.js"
			);
	    	
	       	$this->load->view('user/profilevisits', $data);
       	} else {
	       	header("Location: " . base_url());
       	}
    }
    
    // User chooses to delete his account
	function deleteaccountconfirm()
	{
		$this->load->model('user_model');
		$this->load->model('pm_model');
		
		if(DEMO_MODE == 1) {
			header("Location: " . base_url() . "user/settings?action=demosettings");			
		} else {
			
			// DELETE PM CONV
			$this->pm_model->delete_conversations($this->session->userdata("user_id"));
			
			// DELETE FRIENDS
			$this->user_model->delete_friends($this->session->userdata("user_id"));
			
			// DELETE LOVES
			$this->user_model->delete_loves($this->session->userdata("user_id"));
			
			// DELETE USER PROFIL VISITS
			$this->user_model->delete_profile_visits($this->session->userdata("user_id"));
			
			// DELETE USER INFOS
			$this->user_model->delete_user_info($this->session->userdata("user_id"));
			
			// DELETE USER
			$this->user_model->delete_user($this->session->userdata("user_id"));
			
			// LOGOUT
			$this->session->unset_userdata('user_id');
			$this->session->unset_userdata('user_username');
			$this->session->unset_userdata('user_avatar');
			$this->session->unset_userdata('user_firstform');
			
			$this->session->sess_destroy();
			redirect('','refresh'); 
		
		}
	}
    
    function send_request()
    {
	    if(DEMO_MODE == 1) {
		    $result["result"] = 500;
	    } else {
		    
	    	if(!$this->session->userdata("user_id")) {	
		    	// Not logged in
	    		$result["result"] = 999;
	    	} else {
	    		$user_id = $this->session->userdata("user_id");
	    		$profile_id = $this->input->post('user_id');
	    		
	    		if($profile_id == $user_id) {
		    		$result["result"] = 998;
	    		} else {
		    	    $this->load->model('user_model', 'userManager');
		    	    
		    	    if(!$this->userManager->check_blocked($profile_id, $this->session->userdata('user_id'))) {
			    	    if($this->userManager->has_already_requested($this->session->userdata("user_id"), $profile_id)) {
				    	    $result["result"] = 997;
			    	    } else {
				    	    $user = $this->userManager->get($profile_id)->result_array();
				    	    $user = $user[0];
				    	    
				    	    $this->userManager->send_request($this->session->userdata("user_id"), $profile_id);
				    	    $result["result"] = 1;
				    	    $result["gender"] = $user["gender"];
				    	    $result["username"] = $user["username"];
				    	}
			    	} else {
				        $result["result"] = 996;	
			    	}
	    	    }
	    	}
    	
    	}
    
	    header('Content-type: application/json;');
		echo json_encode($result);
    }
    
    function post_love()
    {
	    $data = array();
		
		$this->load->model('user_model');
		
		$from_user_id = $this->session->userdata("user_id");
		$to_user_id = $this->input->post("profile_id");
		
		if($from_user_id != $to_user_id) {
		
			if(!$this->user_model->check_love_exist($from_user_id, $to_user_id))
			{
				$this->user_model->add_love($from_user_id, $to_user_id);
				$data["result"] = 1;
			} else {
				$this->user_model->remove_love($from_user_id, $to_user_id);
				$data["result"] = 2;
			}
		
		} else {
			$data["result"] = 3;
		}
		
		header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function save_profile_picture()
    {
	    $photo_id = $this->input->post('photo_id');
		$this->load->model('photo_model');
	    	    
	    if(!$this->session->userdata("user_id")) {	
	    	// Not logged in
    		$result = 999;
    	} else {

			if(!$this->photo_model->user_owns_photo($this->session->userdata("user_id"), $photo_id)) {
				$result = 998;
			} else {

				$this->load->model('user_model');
				
				$user_id = $this->session->userdata("user_id");
			    
				$this->user_model->update_info($user_id, array("main_photo" => $photo_id));
				$photo = $this->photo_model->get($photo_id)->result_array();
				$photo = $photo[0];
				
				$this->session->set_userdata(
					array(
						"user_avatar"	=> $photo["thumb_url"],
					)
				);
				
				$user = $this->user_model->get($user_id)->result_array();
				$user = $user[0];
				
				if($user["gender"] == 0) {
					$word_gender = "his";
				} else {
					$word_gender = "her";
				}
				
				$this->load->model('action_model', 'actionModel');
				$this->actionModel->add(1, $this->session->userdata("user_id"), "<a href='" . base_url("user/profile/" . $this->session->userdata("user_id")) . "'>" . $this->session->userdata("user_username") . "</a> has changed $word_gender profile picture.", base_url("user/profile/" . $this->session->userdata("user_id")), "fa-picture-o");
				
				$result = 1;
			
			}
		}
			
		header('Content-type: application/json;');
		echo json_encode($result);
	}
    
    function settings()
    {
	    $data = array();
	    
	    $this->load->model('site_model');
	    $this->load->model('premium_model');
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
		$this->lang->load('user_lang', $lng_opt);
	    	    
	    // Get custom pages
		if($this->site_model->count_custom_pages() > 0) {
			$data["pages"] = $this->site_model->get_pages()->result_array();
		} else {
			$data["pages"] = null;
		}
	    		    	
	    	    
	    if($this->session->userdata("user_id")) {
		    $data["title"] = $this->lang->line("Settings");
	    	$this->load->model('pm_model');
	    	
    		$nb_pm = $this->pm_model->count_unread($this->session->userdata('user_id'));
	   		$data["nb_pm"] = $nb_pm;
			$new_profile_visits = $this->user_model->count_new_profile_visits($this->session->userdata("user_id"));
			$data["nb_new_visits"] = $new_profile_visits;
			$new_poke_requests = $this->user_model->count_new_requests($this->session->userdata("user_id"));
			$data["nb_new_requests"] = $new_poke_requests;
			$new_friends = $this->user_model->count_new_friends($this->session->userdata("user_id"));
			$data["nb_new_friends"] = $new_friends;
			$new_loves = $this->user_model->count_new_loves($this->session->userdata("user_id"));
			$data["nb_new_loves"] = $new_loves;
			
			$data["total_notif"] = intval($new_profile_visits) + intval($nb_pm) + intval($new_loves) + intval($new_poke_requests) + intval($new_friends);
	    	
	    	$user = $this->user_model->get($this->session->userdata("user_id"))->result_array();	
			$data["user"] = $user[0];
	    
			$data["user_photos"] = $this->user_model->get_photos($this->session->userdata("user_id"), 10)->result_array();	
	    	
	    	$this->user_model->update_last_activity($this->session->userdata('user_id'));
	    	
	    	if($this->session->userdata("user_firstform") == 0) {
		    	redirect( base_url() . "user/firstlogin?redirect=true" );
	    	}    			
	    	
	    	// Check has purchased this feature
	    	if($this->premium_model->has_purchased($this->session->userdata("user_id"), "browse_invisibly_coins")) {
		    	$show_browse_invisibly_option = true;
	    	} else {
		    	$show_browse_invisibly_option = false;
	    	}
	    	
	    	$data["extra_user"] = $this->user_model->get_user_extra($this->session->userdata("user_id"))->result_array();
	    	
	    	$data["show_browse_invisibly_option"] = $show_browse_invisibly_option;
	    	
	    	$data["jscripts"] = array(
				base_url() . "js/owl.carousel.min.js",
				"https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places",
				base_url() . "js/pages/account_settings.js"
			);
	    	
	       	$this->load->view('user/account_settings', $data);
       	} else {
	       	header("Location: " . base_url());
       	}
    }
    
    function profile($user_id = 0) 
    {
	    $data = array();
	    
	    if(!is_numeric($user_id))
	    {
		    show_404();
	    } else {
	    
			$this->load->model('user_model');
			$this->load->model("pm_model");
			$this->load->model('site_model');
		    
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
			$this->lang->load('user_lang', $lng_opt);
			
			if($this->session->userdata("user_firstform") != 1)
	    	{
		    	redirect(base_url() . "user/firstlogin?redirect=true");
	    	} else {
				
				if(!$this->user_model->check_user_exists($user_id))
				{
					show_404();
				} else {
			    
				    $user = $this->user_model->get($user_id, $this->session->userdata("user_id"))->result_array(); 
				    $user = $user[0];
				    
				    $data["are_friends"] = false;
					
					$this->load->model('action_model');
					
					// Get custom pages
					if($this->site_model->count_custom_pages() > 0) {
						$data["pages"] = $this->site_model->get_pages()->result_array();
					} else {
						$data["pages"] = null;
					}
			    		    			
			   		if($this->session->userdata("user_id")) {
				   		$nb_pm = $this->pm_model->count_unread($this->session->userdata('user_id'));
			   			$data["nb_pm"] = $nb_pm;
						$new_poke_requests = $this->user_model->count_new_requests($this->session->userdata("user_id"));
						$data["nb_new_requests"] = $new_poke_requests;
						
						$new_profile_visits = $this->user_model->count_new_profile_visits($this->session->userdata("user_id"));
						$data["nb_new_visits"] = $new_profile_visits;
						
						$new_friends = $this->user_model->count_new_friends($this->session->userdata("user_id"));
						$data["nb_new_friends"] = $new_friends;
						
						$new_loves = $this->user_model->count_new_loves($this->session->userdata("user_id"));
						$data["nb_new_loves"] = $new_loves;
						
						$data["total_notif"] = intval($new_profile_visits) + intval($nb_pm) + intval($new_loves) + intval($new_poke_requests) + intval($new_friends);
						
						if($this->user_model->check_love_exist($this->session->userdata("user_id"), $user_id)) {
					    	$data["has_loved"] = true;
				    	} else {
					    	$data["has_loved"] = false;
				    	}
						
						if($this->user_model->has_already_requested($this->session->userdata("user_id"), $user_id)) {
					    	$data["has_requested"] = true;
				    	} else {
					    	$data["has_requested"] = false;
				    	}
				    	
				    	if($this->user_model->has_pending_request($this->session->userdata("user_id"), $user_id)) {
					    	$data["has_pending"] = true;
				    	} else {
					    	$data["has_pending"] = false;
				    	}
			   		
						if($this->user_model->check_already_friend($this->session->userdata("user_id"), $user_id)) {
				   			$data["are_friends"] = true;
						}
						
						$timeline_msgs = $this->action_model->get_last_from_user($user_id, 10)->result_array();
						$data["timeline_msgs"] = $timeline_msgs;
						
						$this->user_model->update_last_activity($this->session->userdata('user_id'));
						
						// Add a visit
						if($user_id != $this->session->userdata("user_id"))
						{
							$current_user = $this->user_model->get($this->session->userdata("user_id"))->result_array();
							$current_user = $current_user[0];
							
							if($current_user["browse_invisibly"] == 0)
							{
							
								if(!$this->user_model->has_already_visited($user_id, $this->session->userdata("user_id"))) {
									$this->user_model->add_profile_visit($user_id, $this->session->userdata("user_id"));
								}
								else {
									$this->user_model->update_profile_visit($user_id, $this->session->userdata("user_id"));
								}
							
							}
						}
						
						$data["check_blocked"] = $this->user_model->check_blocked($this->session->userdata("user_id"), $user_id);
			   		} else {
				   		$data["nb_pm"] = 0;
				   		$data["nb_new_requests"] = 0;
				   		$data["nb_new_visits"] = 0;
			   		}
			
				   	$user_id = $user["uid"];
			
				    $data["title"] = $user["username"];
					
					// Get infos from DB
					$photos = $this->user_model->get_photos($user_id, 50)->result_array();
								
					$birthdate = new DateTime($user["birthday"]);
					$today     = new DateTime();
					$interval  = $today->diff($birthdate);
					$age	   = $interval->format('%y');
					
					if($user["is_fake"] == 0)
					{
						if(($user["thumb_url"] == "" || $user["photostatus"] == 0) && $user["gender"] == 0) {
							$avatar = base_url() . "images/avatar.png";
						} else if(($user["thumb_url"] == "" || $user["photostatus"] == 0) && $user["gender"] == 1) {
							$avatar = base_url() . "images/avatar.png";
						} else if($user["thumb_url"] != "") {
							$avatar = base_url() . $user["thumb_url"];
						}
					} else {
						$avatar = $user["thumb_url"];
					}
					
					$data["avatar"] = $avatar;
					$data["country_name"] = get_country_name_by_code($user["country"]);
			    	$data["user"] = $user;   	
			    	$data["age"] = $age;
			    	$data["photos"] = $photos;
			    	
			    	$data["extra_user"] = $this->user_model->get_user_extra($user_id)->result_array();
			    	
			    	$data["jscripts"] = array(
						base_url() . "js/pages/profile.js"
					);
			    	
			       	$this->load->view('user/profile', $data);
		       	
		       }
	       }
       
       }
	       	

    }
    
    function edit_user_submit()
	{
		if(!$this->session->userdata("user_username")) {
			show_error("Woops! You need to log in to access to this part of Snapals!");
		} else {
			$this->load->model('user_model');
			$this->load->model('site_model');
			
			$settings = $this->site_model->get_website_settings()->result_array();
			$settings = $settings[0];
			
			$update_user = array();
		
			$gender 			= $this->input->post('gender');
			$about 				= $this->security->xss_clean(strip_tags($this->input->post('about_you_txt')));
			$birthday_day 		= $this->input->post('birthday_day');
			$birthday_month 	= $this->input->post('birthday_month');
			$birthday_year 		= $this->input->post('birthday_year');
			$country 			= $this->input->post('country');
			$city 				= $this->security->xss_clean(strip_tags($this->input->post('city')));
			$user_id 			= $this->session->userdata("user_id");
			$browse_invisibly 	= $this->input->post("browse_invisibly");
			
			if(empty($browse_invisibly)){
				$browse_invisibly = 0;
			}
								
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
					"about"			=> $this->security->xss_clean($about),
					"browse_invisibly" => $this->security->xss_clean($browse_invisibly)
				);
				
				// Save user infos
				$this->user_model->update_info($this->session->userdata("user_id"), $update_user);
				
				// Save custom fields
				$extra_fields = $settings["user_extra_fields"];
	            $user_fields = explode(",", $extra_fields);  
	            	            		                        
	            foreach($user_fields as $u_field) {
		            // Check if this extra field exists for this user
		            if($this->user_model->check_extra_field_exists($this->session->userdata("user_id"), $u_field)) {
			            // Update
			            $this->user_model->update_user_extra_field($this->session->userdata("user_id"), $u_field, $this->security->xss_clean(strip_tags($this->input->post(preg_replace('/\s+/', '', $u_field)))));
		            } else {
			            // Insert
			            $this->user_model->insert_user_extra_field($this->session->userdata("user_id"), $u_field, $this->security->xss_clean(strip_tags($this->input->post(preg_replace('/\s+/', '', $u_field)))));
		            }
		        }
				
				// Ok! Redirect
				redirect(base_url() . "user/settings?action=edit_success");

	    	} else {
				show_error("Woops! There is an error. Please go back and try again.");
	    	}
	    	
    	}
	}
	
	function edit_account_submit()
	{
		if(!$this->session->userdata("user_username")) {
			show_error("Woops! You need to log in to access to this part of the website.");
		} else {
			$this->load->model('user_model');
			
			$user = $this->user_model->get($this->session->userdata("user_id"))->result_array();
			$user = $user[0];
			
			$update_user = array();
		
			$username 			= $this->security->xss_clean($this->input->post('username'));
			$email 				= $this->input->post('email');
			$user_id 			= $this->session->userdata("user_id");
			$error = false;
					    	
	    	$username_taken = $this->user_model->is_username_taken_user($username, $this->session->userdata("user_id"));
			$email_taken 	= $this->user_model->is_email_taken_user($email, $this->session->userdata("user_id"));
			
			
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
				$this->user_model->update($this->session->userdata("user_id"), $update_user);
				
				// Ok! Redirect
				redirect(base_url("user/settings?action=edit_success"));
				
			}

    	}
	}
	
	function editaccountinfosverif()
	{
		$data = array();
		
		$this->load->model('user_model');	
		$this->load->model('site_model');
		
		$settings = $this->site_model->get_website_settings()->result_array();
		$settings = $settings[0];
		
		$user_lng = $this->user_model->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}
		
		$this->lang->load('user_lang', $lng_opt);
	    
	    $username 			= $this->security->xss_clean($this->input->post('username'));
    	$email 				= $this->input->post('email');
		$username_taken 	= $this->user_model->is_username_taken_user($username, $this->session->userdata('user_id'));
		$email_taken 		= $this->user_model->is_email_taken_user($email, $this->session->userdata('user_id'));
		
		if(DEMO_MODE == 1) {
			$data["error"] = 500;
		} else {
						    	
	    	if(strlen($username) < 3)
	    	{
		    	$data["error"] = 999;
		    	$data["error_msg"] = $this->lang->line("username_3_chars_error");
	    	} else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		    	$data["error"] = 998;
		    	$data["error_msg"] = $this->lang->line("email_not_valid");
	    	} else if($username_taken) {
		    	$data["error"] = 996;
		    	$data["error_msg"] = $this->lang->line("username_taken");
	    	} else if($email_taken) {
	    		$data["error"] = 995;
		    	$data["error_msg"] = $this->lang->line("email_taken");
	    	} else {
		    	$data["error"] = 0;
	    	}
    	
    	}
    	
    	header('Content-type: application/json;');
		echo json_encode($data);
	}
	
	function firstloginfbsubmit()
	{
		$this->load->model('user_model');
		$this->load->model('site_model');
		$settings = $this->site_model->get_website_settings()->result_array();
		$settings = $settings[0];
		
		$user_lng = $this->user_model->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}
		
		$this->lang->load('user_lang', $lng_opt);
		
		if(!$this->session->userdata("user_username")) {
			show_error($this->lang->line('not_logged_in_error'));
		} else {
			
			
			// Get the actual infos of the user
			$user = $this->user_model->get($this->session->userdata("user_id"))->result_array();
			$user = $user[0];
			
			
			$update_user = array();
		
			$gender				= $user["gender"];
			$username 			= $this->security->xss_clean($this->input->post('username'));
			$birthday_day 		= $this->security->xss_clean($this->input->post('birthday_day'));
			$birthday_month 	= $this->security->xss_clean($this->input->post('birthday_month'));
			$birthday_year 		= $this->security->xss_clean($this->input->post('birthday_year'));
			$about_you_txt 		= $this->security->xss_clean(strip_tags($this->input->post('about_you_txt')));
			$country 			= $this->security->xss_clean($this->input->post('country'));
			$city 				= $this->security->xss_clean(strip_tags($this->input->post('city')));
			
			$user_id 			= $this->session->userdata("user_id");
					
			
			if($birthday_day != "0" && $birthday_month != "0" && $birthday_year != "0" && $country != "0") {
		    	// Check if the directory already exists			
				if($this->session->userdata("user_avatar") == "")
				{
					$this->session->set_userdata(
						array(
							"user_avatar"	=> base_url() . "images/avatar.png"
						)
					);
				}
				
				$date_string = $birthday_day . " " . $birthday_month . " " . $birthday_year;
			
				
				$update_user = array(
					"birthday"		=> date("Y-m-d", strtotime($date_string)),
					"about"			=> $about_you_txt,
					"country"		=> $country,
					"city"			=> $city
				);
				
				// Save user infos
				$this->user_model->update_info($this->session->userdata("user_id"), $update_user);
				$this->user_model->update($this->session->userdata("user_id"), array("username" => $username, "first_step_form" => 1));
				$final_user = $this->user_model->get($this->session->userdata("user_id"))->result_array();
				$final_user = $final_user[0];
								
				$this->load->model('action_model', 'actionModel');
				
				$action_txt = sprintf($this->lang->line('created_account_action'),base_url("user/profile/" . $this->session->userdata("user_id")), $this->session->userdata("user_username"));
				
				$this->actionModel->add(1, $this->session->userdata("user_id"), $action_txt, base_url("user/profile/" . $this->session->userdata("user_id")), "fa-sign-in");
												
				// Update user session
				$this->session->set_userdata(
					array(
						"user_firstform" => 1,
						"user_username"	=> $username
					)
				);
				
				// Ok! Redirect
				redirect(base_url("home?action=welcome"));

	    	} else {
				show_error("Whoops! There is an error. Please go back and try again.");
	    	}
	    	
    	}
	}
	
	function firstloginsubmit()
	{
		$this->load->model('user_model');
		$this->load->model('site_model');
		$settings = $this->site_model->get_website_settings()->result_array();
		$settings = $settings[0];
		
		$user_lng = $this->user_model->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}
		
		$this->lang->load('user_lang', $lng_opt);
		
		if(!$this->session->userdata("user_username")) {
			show_error($this->lang->line('not_logged_in_error'));
		} else {
			
			
			// Get the actual infos of the user
			$user = $this->user_model->get($this->session->userdata("user_id"))->result_array();
			$user = $user[0];
			
			
			$update_user = array();
		
			$gender				= $user["gender"];
			$birthday_day 		= $this->security->xss_clean($this->input->post('birthday_day'));
			$birthday_month 	= $this->security->xss_clean($this->input->post('birthday_month'));
			$birthday_year 		= $this->security->xss_clean($this->input->post('birthday_year'));
			$about_you_txt 		= $this->security->xss_clean(strip_tags($this->input->post('about_you_txt')));
			$country 			= $this->security->xss_clean($this->input->post('country'));
			$city 				= $this->security->xss_clean(strip_tags($this->input->post('city')));
			
			$user_id 			= $this->session->userdata("user_id");
			$photo_id			= 0;
					
			
			if($birthday_day != "0" && $birthday_month != "0" && $birthday_year != "0" && $country != "0") {
		    	// Check if the directory already exists
	        	if (!file_exists("./uploads/photos/" . $user_id . "/")) {
	        		mkdir("./uploads/photos/" . $user_id . "/");
	        		mkdir("./uploads/photos/" . $user_id . "/thumbnails/");
	        	}
	        	
	        	// Upload the file
				if (!empty($_FILES))
				{
					$nameFile 	= rand(0,999999).time();
				    $tempFile 	= $_FILES['photomain']['tmp_name'];
				    $fileTypes 	= array('jpg','jpeg','png', 'JPG', 'JPEG', 'PNG'); // File extensions
				    $fileParts 	= pathinfo($_FILES['photomain']['name']);
				    $targetPath = "./uploads/photos/" . $user_id . "/";
				    $targetPathThumb = $targetPath . "thumbnails/";
				    $targetPathEchoThumb = "/uploads/photos/" . $user_id . "/thumbnails/";
				    $targetPathEcho = "/uploads/photos/" . $user_id . "/";
				    $targetFile =  str_replace('//','/',$targetPath) . $nameFile . "." . $fileParts["extension"];
				    $targetFileThumb = str_replace('//','/',$targetPathThumb) . $nameFile . "." . $fileParts["extension"];
				    $targetFileEcho = str_replace('//','/',$targetPathEcho) . $nameFile . "." . $fileParts["extension"];
				    $targetFileEchoThumb = str_replace('//','/',$targetPathEchoThumb) . $nameFile . "." . $fileParts["extension"];
				
				    if (in_array($fileParts['extension'],$fileTypes)) {
				    
				    	$this->load->model('photo_model');
				    
				    	$file = $this->compress_image($tempFile, $targetFile, 70);
				    	
				    	$thumbWidth = 400;
				    	
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
	                	
	                	$photo_id = $this->photo_model->add($targetFileEcho, $targetFileEchoThumb, $this->session->userdata("user_id"));
	                	
	                	$this->session->set_userdata(
							array(
								"user_avatar"	=> $targetFileEchoThumb
							)
						);
					}
				}
				
			
				if($this->session->userdata("user_avatar") == "")
				{
					$this->session->set_userdata(
						array(
							"user_avatar"	=> base_url() . "images/avatar.png"
						)
					);
				}
				
				$date_string = $birthday_day . " " . $birthday_month . " " . $birthday_year;
			
				
				$update_user = array(
					"main_photo"	=> $photo_id,
					"birthday"		=> date("Y-m-d", strtotime($date_string)),
					"about"			=> $about_you_txt,
					"country"		=> $country,
					"city"			=> $city
				);
				
				// Save user infos
				$this->user_model->update_info($this->session->userdata("user_id"), $update_user);
				$this->user_model->update($this->session->userdata("user_id"), array("first_step_form" => 1));
				$final_user = $this->user_model->get($this->session->userdata("user_id"))->result_array();
				$final_user = $final_user[0];
								
				$this->load->model('action_model', 'actionModel');
				
				$action_txt = sprintf($this->lang->line('created_account_action'),base_url("user/profile/" . $this->session->userdata("user_id")), $this->session->userdata("user_username"));
				
				$this->actionModel->add(1, $this->session->userdata("user_id"), $action_txt, base_url("user/profile/" . $this->session->userdata("user_id")), "fa-sign-in");
			
												
				// Update user session
				$this->session->set_userdata(
					array(
						"user_firstform" => 1
					)
				);
				
				// Ok! Redirect
				redirect(base_url("home?action=welcome"));

	    	} else {
				show_error("Whoops! There is an error. Please go back and try again.");
	    	}
	    	
    	}
	}
	
	function firstloginveriffb()
	{
		$data = array();
		
		$this->load->model('user_model');
			
		$this->load->model('site_model');
		$settings = $this->site_model->get_website_settings()->result_array();
		$settings = $settings[0];
		
		$user_lng = $this->user_model->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}
		
		$this->lang->load('user_lang', $lng_opt);
		
		if(DEMO_MODE == 1) {
			$data["error"] = 500;
		} else {
		
		    $username 			= $this->input->post('username');
			$birthday_day 		= $this->input->post('birthday_day');
			$birthday_month 	= $this->input->post('birthday_month');
			$birthday_year 		= $this->input->post('birthday_year');
			$country 			= $this->input->post('country');
			$city 				= $this->security->xss_clean(strip_tags($this->input->post('city')));
			
			$date_string = $birthday_day . " " . $birthday_month . " " . $birthday_year;
			$bday = date("Y-m-d", strtotime($date_string));
			
			$birthdate = new DateTime($bday);
			$today     = new DateTime();
			$interval  = $today->diff($birthdate);
			$age	   = $interval->format('%y');
					    	
			if(strlen($username) < 3) {
				$data["error"] = 992;
		    	$data["error_msg"] = $this->lang->line('username_3_chars_error');
			}
	    	else if($birthday_day == "0" || $birthday_month == "0" || $birthday_year == "0") {
		    	$data["error"] = 998;
		    	$data["error_msg"] = $this->lang->line('provide_valid_birthday');
	    	} else if($country == "0" && $settings["hide_country"] == 0) {
		    	$data["error"] = 995;
		    	$data["error_msg"] = $this->lang->line('select_country_where_live');
    		} else {
	    		if($settings["site_age_limit"] != 0 && $settings["site_age_limit"] != NULL) {
			    	if($age < $settings["site_age_limit"]) {
			    		$data["error"] = 993;
						$data["error_msg"] = sprintf($this->lang->line('too_young_to_register'), $settings["site_age_limit"]);
					} else {
						$data["error"] = 0;
					}
				} else {
			    	$data["error"] = 0;
		    	}
	    	}
    	
    	}
    	
    	header('Content-type: application/json;');
		echo json_encode($data);
	}
	
	function firstloginverif()
	{
		$data = array();
		
		$this->load->model('user_model');
			
		$this->load->model('site_model');
		$settings = $this->site_model->get_website_settings()->result_array();
		$settings = $settings[0];
		
		$user_lng = $this->user_model->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}
		
		$this->lang->load('user_lang', $lng_opt);
		
		if(DEMO_MODE == 1) {
			$data["error"] = 500;
		} else {
		
		    
			$birthday_day 		= $this->input->post('birthday_day');
			$birthday_month 	= $this->input->post('birthday_month');
			$birthday_year 		= $this->input->post('birthday_year');
			$country 			= $this->input->post('country');
			$city 				= $this->security->xss_clean(strip_tags($this->input->post('city')));
			
			$date_string = $birthday_day . " " . $birthday_month . " " . $birthday_year;
			$bday = date("Y-m-d", strtotime($date_string));
			
			$birthdate = new DateTime($bday);
			$today     = new DateTime();
			$interval  = $today->diff($birthdate);
			$age	   = $interval->format('%y');
					    	
	    	if($birthday_day == "0" || $birthday_month == "0" || $birthday_year == "0") {
		    	$data["error"] = 998;
		    	$data["error_msg"] = $this->lang->line('provide_valid_birthday');
	    	} else if($country == "0" && $settings["hide_country"] == 0) {
		    	$data["error"] = 995;
		    	$data["error_msg"] = $this->lang->line('select_country_where_live');
    		} else {
	    		if($settings["site_age_limit"] != 0 && $settings["site_age_limit"] != NULL) {
			    	if($age < $settings["site_age_limit"]) {
			    		$data["error"] = 993;
						$data["error_msg"] = sprintf($this->lang->line('too_young_to_register'), $settings["site_age_limit"]);
					} else {
						$data["error"] = 0;
					}
				} else {
			    	$data["error"] = 0;
		    	}
	    	}
    	
    	}
    	
    	header('Content-type: application/json;');
		echo json_encode($data);
	}
	
	function save_password_recovery()
	{
		$data = array();
		
		if(DEMO_MODE == 1) {
			$data["result"] = 500;
		} else {
			$this->load->model('user_model');	
		    
	    	$encrypt_id 		= $this->security->xss_clean($this->input->post('encrypt_id'));
	    	$user_id			= $this->security->xss_clean($this->input->post('user_id'));
	    	$password1			= $this->security->xss_clean($this->input->post('password1'));
			$password2			= $this->security->xss_clean($this->input->post('password2'));    	
			
	    	if($this->user_model->check_password_recovery($encrypt_id, $user_id)) {
	    		if($password1 != $password2)
	    		{
		    		$data["result"] = 998;
	    		} else if(strlen($password1) < 4) {
		    		$data["result"] = 997;
	    		} else {
		    		$this->user_model->update($user_id, array("password" => sha1($password1)));
		    		$this->user_model->remove_password_recovery($encrypt_id, $user_id);
		    		
		    		$data["result"] = 1;
	    		}
	    	} else {
				$data["result"] = 999;
			}
		}
		
		header('Content-type: application/json;');
		echo json_encode($data);
    	
	}
	
	function create_new_password($encrypt_id, $user_id) {
		$data = array();
		
		$this->load->model('site_model');	
		
		$settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    $data["settings"] = $settings;
		
		$this->lang->load('user_lang', $settings["default_language"]);
		$data["title"] = $this->lang->line('create_new_password_title');
		
		$this->load->model('user_model');
				
		if($this->user_model->check_password_recovery($encrypt_id, $user_id)) {
		
			$user = $this->user_model->get($user_id)->result_array();
			$user = $user[0];
			
			$data["user"] = $user;
			$data["encrypt_id"] = $encrypt_id;
		
			$this->load->view('user/password_recovery', $data);
		} else {
			show_404();
		}
	}
	
	function recover_password()
	{
		$data = array();
		
		$this->load->model('user_model');
		$this->load->model('site_model');	
		
		$settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    $data["settings"] = $settings;
			
		$this->lang->load('user_lang', $settings["default_language"]);
	    
    	$email 		= $this->input->post('email');
    	
    	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	    	$data["error"]	= 999;
    	} else {
    		// Check email exists
    		$user = $this->user_model->get_by_email($email)->result_array();
    		    		
    		if(isset($user[0])) {
    		
    			$encrypt_id = sha1(time().$user[0]["username"]);
    		
    			$this->user_model->add_password_recovery($encrypt_id, $user[0]["uid"]);
    		
    			$subject = $this->lang->line('recover_password_email_title');
    			$message = $this->lang->line('recover_password_email_first_line') . "<br /><br />";
    			$message.= $this->lang->line('recover_password_email_second_line') . "<br />";
    			$message.= "<a href='" . base_url() . "user/create_new_password/" . $encrypt_id . "/" . $user[0]["uid"] . "'>" . base_url() . "user/create_new_password/" . $encrypt_id . "/" . $user[0]["uid"] . "</a>";
    			
    			$title = $subject;
    			
    			$this->send_mail($subject, $email, $message, $title, $user[0]["username"]);
    		
				$data["error"] = 1;
			} else {
				$data["error"] = 998;
			}
    		
    	}
    	
    	header('Content-type: application/json;');
		echo json_encode($data);
	}
	
	function login()
	{
		$data = array();
		
		$this->load->model('user_model');	
		
		$this->load->model('site_model');	
		
		$settings = $this->site_model->get_website_settings()->result_array();
	    $settings = $settings[0];
	    $data["settings"] = $settings;
		
		$this->lang->load('user_lang', $settings["default_language"]);	
			
		$res = $this->user_model->login(
			$this->input->post('username'),
			$this->input->post('password')
		);
			
		if ( $res !== false ) {
			
			if($res[0]->banned == 1) {
				$data["error"] = 997;
				$data["status"]	= $this->lang->line('banned_error');
			} else {
				
				$user_firstform = 0;
				
				
				if($res[0]->first_step_form == 0) {
					$data["url_redirect"] = base_url() . "user/firstlogin";
				} else {
					$data["url_redirect"] = base_url() . "home";
					$user_firstform = 1;
				}
				
				$avatar = base_url() . "images/avatar.png";
				
				if(($res[0]->thumb_url == "" || $res[0]->photostatus == 0) && $res[0]->gender == 0) {
					$avatar = base_url() . "images/avatar.png";
				} else if(($res[0]->thumb_url == "" || $res[0]->photostatus == 0) && $res[0]->gender == 1) {
					$avatar = base_url() . "images/avatar.png";
				} else if($res[0]->thumb_url != "") {
					$avatar = $res[0]->thumb_url;
				}
				
				$this->session->set_userdata(
					array(
						"user_id"		=> $res[0]->id,
						"user_username"	=> $res[0]->username,
						"user_firstform"=> $user_firstform,
						"user_avatar"	=> $avatar,
						"user_rank"		=> $res[0]->rank
					)
				);
				
				$this->user_model->update_login($res[0]->id);
				
				$data["error"] = 0;
			}
			
		} else {
			$data["error"]	= 999;
			$data["status"]	= $this->lang->line('invalid_username_password');
		}
		
    	header('Content-type: application/json;');
		echo json_encode($data);
	}
	
	function firstloginfb()
    {
	    $data = array();
	    
	    $this->load->model('user_model');
	    $this->load->model('site_model');

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
	    
	    $this->lang->load('user_lang', $lng_opt);
	    $this->lang->load('site_lang', $lng_opt);
	    	    
	    if($this->session->userdata("user_id")) {

		    
		    // Get custom pages
			if($this->site_model->count_custom_pages() > 0) {
				$data["pages"] = $this->site_model->get_pages()->result_array();
			} else {
				$data["pages"] = null;
			}
		    
		    $data["title"] = $this->lang->line("first_login_title");
	    		    
	    	$user = $this->user_model->get_complete_user_fb($this->session->userdata("user_id"))->result_array();	
	    	$user = $user[0];
	    	
	    	if($this->session->userdata("user_firstform") == 1) {
		    	redirect( base_url() . "home" );
	    	}  	
	    	
	    	$data["user"] = $user;	
	    	
	    	$data["nb_pm"] = 0;
	    	$data["nb_new_requests"] = 0;
	    	
	    	$data["nb_new_friends"] = 0;
	    	$data["nb_new_loves"] = 0;
	    	$data["nb_new_visits"] = 0;
	    	$data["total_notif"] = 0;
	    	
	    	$data["jscripts"] = array(
		    	"https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places",
				base_url() . "js/pages/firstloginfb.js"
			);
	    	
	       	$this->load->view('user/firstloginfb', $data);
       	} else {
	    	redirect(base_url());
       	}
	}
	
	function firstlogin()
    {
	    $data = array();
	    
	    $this->load->model('user_model');
	    $this->load->model('site_model');

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
	    
	    $this->lang->load('user_lang', $lng_opt);
	    $this->lang->load('site_lang', $lng_opt);
	    
	    if($this->session->userdata("fb_id")) {
		    redirect(base_url()."user/firstloginfb");
			exit;   
		}
	    	    
	    if($this->session->userdata("user_id")) {

		    
		    // Get custom pages
			if($this->site_model->count_custom_pages() > 0) {
				$data["pages"] = $this->site_model->get_pages()->result_array();
			} else {
				$data["pages"] = null;
			}
		    
		    $data["title"] = $this->lang->line("first_login_title");
	    	
	    	
	    
	    	$user = $this->user_model->get($this->session->userdata("user_id"))->result_array();	
	    	$user = $user[0];
	    	
	    	if($this->session->userdata("user_firstform") == 1) {
		    	redirect( base_url() . "home" );
	    	}  	
	    	
	    	$data["user"] = $user;	
	    	
	    	$data["nb_pm"] = 0;
	    	$data["nb_new_requests"] = 0;
	    	
	    	$data["nb_new_friends"] = 0;
	    	$data["nb_new_loves"] = 0;
	    	$data["nb_new_visits"] = 0;
	    	$data["total_notif"] = 0;
	    	
	    	$data["jscripts"] = array(
		    	"https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places",
				base_url() . "js/pages/firstlogin.js"
			);
	    	
	       	$this->load->view('user/firstlogin', $data);
       	} else {
	    	redirect(base_url());
       	}
	}

	function create() 
	{
		$data = array();
		
		if(DEMO_MODE == 1) {
			$data["error"] = 500;
			// echo 'modo demo';
		} else {
			// echo 'normal';
			$this->load->model('user_model');	
			$this->load->model('site_model');	
			
		    $settings = $this->site_model->get_website_settings()->result_array();
		    $settings = $settings[0];
		    
	    	$username 		= $this->security->xss_clean($this->input->post('username'));
	    	$password 		= $this->security->xss_clean($this->input->post('password'));
	    	$email	 		= $this->input->post('email');
	    	$gender			= $this->security->xss_clean($this->input->post('bepoke_gender'));
	    	$interested_in	= $this->security->xss_clean($this->input->post('bepoke_interested_in'));
	    	
	    	$captcha_rep	= $this->security->xss_clean($this->input->post('captcha_rep'));
	    	$captcha_id		= $this->input->post('captcha_id');
	    		    	
			$username_taken = $this->user_model->is_username_taken($username);
			$email_taken 	= $this->user_model->is_email_taken($email);
			
			if($settings["web_captcha"] == 1) {
				$capcha_answer  = $this->user_model->get_captcha_answer($captcha_id);
			} else {
				$capcha_answer 	= "";
			}
			
			$this->lang->load('user_lang', $settings["default_language"]);
	    	
	    	if(strlen($username) < 3) {
	    		$data["error"]	= 999;
		    	$data["error_msg"] = $this->lang->line('username_3_chars_error');
	    	} else if(strlen($password) < 4) {
		    	$data["error"]	= 998;
		    	$data["error_msg"] = $this->lang->line('password_4_chars_error');
	    	} else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		    	$data["error"]	= 997;
		    	$data["error_msg"] = $this->lang->line('email_not_valid');
	    	} else if($settings["web_captcha"] == 1 && $capcha_answer != $captcha_rep) {
		    	$data["error"] = 996;
		    	$data["error_msg"] = $this->lang->line('captcha_error');
	    	} else if($username_taken) {
			    $data["error"] = 995;
				$data["error_msg"] = $this->lang->line('username_taken');
		    } else if($email_taken) {
			    $data["error"] = 994;
			    $data["error_msg"] = $this->lang->line('email_taken');
		    } else {
				$encrypt_id = md5(time().$username);

				/*
				$this->load->library('email');
				$config = array (
					'mailtype' => 'html',
					'charset'  => 'utf-8',
					'priority' => '1'
				);
				$this->email->initialize($config);
				$this->email->from("noreply@email.com", $settings["site_name"]);
				$this->email->to($email);
				$this->email->subject('Welcome to ' . $settings["site_name"] . ' !');
				
				$datamail = array();
				
				
				$datamail["encrypt_id"] = $encrypt_id;
				$datamail["username"]	= $username;
				$datamail["settings"] = $settings;
				
				$message = $this->load->view('email/welcome-html',$datamail,TRUE);
				$this->email->message($message);
				$this->email->send();  
				*/
				
				$data["error"] = 0;
		    
		    	// Create the user
		    	if($this->user_model->record_count() == 0)
		    	{
			    	$user_id = $this->user_model->create($username, $password, $email, 1, $encrypt_id, "", "", 2);
			    	
			    	$this->session->set_userdata(
						array("user_rank" => 2)
					);
			    }
			    else
			    	$user_id = $this->user_model->create($username, $password, $email, 1, $encrypt_id);
			    	
			    // Create user info
			    $this->user_model->create_info($user_id, $gender, $interested_in);
			    
			    $this->session->set_userdata(
					array(
						"user_id"		=> $user_id,
						"user_username"	=> $username,
						"user_avatar"	=> "/images/avatar.png",
						"user_firstform"=> 0
					)
				);
				
				$this->user_model->update_login($user_id);
		    }
	    
	    }
		    	    	
    	header('Content-type: application/json;');
		echo json_encode($data);
	}
	
	function accept_friend()
	{
		$user_id = $this->input->post('user_id');
		$this->load->model('user_model');
		$this->load->model('site_model');
		
		$settings = $this->site_model->get_website_settings()->result_array();
		$settings = $settings[0];
		
		$user_lng = $this->user_model->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}
		
		$this->lang->load('user_lang', $lng_opt);
	    	    
	    if(!$this->session->userdata("user_id")) {	
	    	// Not logged in
    		$result = 999;
    	} else {

			// Check if the poke exists
			if(!$this->user_model->has_already_requested($user_id, $this->session->userdata("user_id"))) {
				$result = 998;
			} else {
							    
				$from_user = $this->user_model->get($user_id)->result_array();
				$from_user = $from_user[0];
				
				// Set as friend
				$this->user_model->update_request_to_friend($user_id, $this->session->userdata("user_id"));
				
				// Add the action
				$this->load->model('action_model', 'actionModel');
				
				$became_friends_one = sprintf($this->lang->line("became_friends_action"), base_url("user/profile/" . $this->session->userdata("user_id")), $this->session->userdata("user_username"), base_url("user/profile/" . $user_id), $from_user["username"]);				
				$became_friends_two = sprintf($this->lang->line("became_friends_action"), base_url("user/profile/" . $user_id), $from_user["username"], base_url("user/profile/" . $this->session->userdata("user_id")), $this->session->userdata("user_username"));
								
				$this->actionModel->add($user_id, $this->session->userdata("user_id"), $became_friends_one, base_url("user/profile/" . $this->session->userdata("user_id")), "fa-users");
				$this->actionModel->add($this->session->userdata("user_id"), $user_id, $became_friends_two, base_url("user/profile/" . $user_id), "fa-users");
				
				// Add friend notif
				$this->user_model->add_friend_notif($user_id);
				$this->user_model->add_friend_notif($this->session->userdata("user_id"));
				
				$result = 1;
			}
		}
			
		header('Content-type: application/json;');
		echo json_encode($result);
	}
	
	function get_user_edit_infos()
    {
	    $data = array();
		
		$this->load->model('user_model');	
		    	
    	$user = $this->user_model->get($this->session->userdata("user_id"))->result_array();	
    	$user = $user[0];
    	
    	$data["user"] = $user;
    	
    	header('Content-type: application/json;');
		echo json_encode($data);
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
