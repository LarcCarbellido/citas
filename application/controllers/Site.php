<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends CI_Controller {

	
	function get_welcome_page()
	{
		$page_id = $this->input->post("page_id");
		
		$data = array();
		
		$this->load->model('site_model');
		    	
		$page = $this->site_model->get_page($page_id);
		
		$data["page"] = $page;
		
		header('Content-type: application/json;');
		echo json_encode($data);
	}

	public function index()
	{
		if($this->session->userdata('user_id')) {
			redirect(base_url() . "home");	
		} else {
			$this->load->model('site_model');
	
			$settings = $this->site_model->get_website_settings()->result_array();
			$settings = $settings[0];
						
			$this->lang->load('site_lang', $settings["default_language"]);

			$data["title"] = $settings["site_name"] . " - " . $settings["site_tagline"];
			
			$this->load->model("user_model");
	    
		    $data["settings"] = $settings;
		    
		    $data["fb_login_url"] = $this->facebook->login_url();
		    
	    	// Get custom pages
			if($this->site_model->count_custom_pages() > 0) {
				$data["pages"] = $this->site_model->get_welcome_pages()->result_array();
			} else {
				$data["pages"] = null;
			}
			
			$users = $this->user_model->get_last_registered_users_page(8, 0)->result_array();
			
			$captcha = $this->user_model->get_captcha();		
			$data["captcha"] = $captcha[0];
			$data["users"] = $users;
			
			$this->load->view('welcome', $data);
		}
	}

	
    function home() 
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
	
    	$data["title"] = $settings["site_name"] . " - " . $settings["site_tagline"];
    	
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
				$datauser = $datauser[0];
				
				$data["user"] = $datauser;
				
				// Get custom pages
				if($this->site_model->count_custom_pages() > 0) {
					$data["pages"] = $this->site_model->get_pages()->result_array();
				} else {
					$data["pages"] = null;
				}
				
				$this->user_model->update_last_activity($this->session->userdata('user_id'));
		    } else {
			    redirect(base_url() . "?error=fb_session");
		    }
	    		    			    
			$this->load->library("pagination");
		    
		    $config = array();
	        $config["base_url"] 	= base_url() . "home";
	       
	        $config["per_page"] 	= 20;
	        $config["uri_segment"] 	= 2;
	        $config['num_links'] 	= 1;
	        
	        $config['first_link']	= "<<";
	        $config['last_link']	= ">>";
	        
	        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

	        if($this->session->userdata('filter_age_from')) 
	        {
		        $age_from 	= intval($this->session->userdata('filter_age_from'));
		        $age_to		= intval($this->session->userdata('filter_age_to'));
		        	        
		        if($this->session->userdata('filter_gender') == 0)
		        	$gender	= 0;
		        else if($this->session->userdata('filter_gender') == 1)
		        	$gender = 1;
		        else
		        	$gender = 2;
		        	
		        $filter_sort = intval($this->session->userdata('filter_sort'));
		        
		        if($filter_sort == 0)
		        {
			        $sort_by = 0;
		        } else {
			        $sort_by = 1;
		        }
		        		        	
		        if($settings["hide_country"] == 0)
		        	$country = $this->session->userdata('filter_country');
		        else
		        	$country = "0";
		        
		        	
		        $city = $this->session->userdata('filter_city');
		        		        
		        $users = $this->user_model->get_last_registered_users_page_with_param($config["per_page"], $page, $age_from, $age_to, $gender, $country, $city, $sort_by, $this->session->userdata('user_id'))->result_array();
		        $users_count = $this->user_model->record_count_by_settings($age_from, $age_to, $gender, $country, $city);
		    } else {
	        	$users = $this->user_model->get_last_registered_users_page($config["per_page"], $page, $this->session->userdata('user_id'))->result_array();
				$users_count = $this->user_model->record_count();
				
				$age_from = 0;
				$age_to = 100;
				$gender = 2;
				$country = 0;
				$city = "";
	        }
	        
	        $this->load->model("premium_model");
	        $featured_users = $this->premium_model->get_last_featured_users(4, $age_from, $age_to, $gender, $country, $city, $this->session->userdata('user_id'))->result_array();	        
		    
		    $data["featured_users"] = $featured_users;
		    
		    $config["total_rows"] 	= $users_count;
		    $this->pagination->initialize($config);
	        
	        $data["users"] = $users;
	        $data["links"] = $this->pagination->create_links();
	        
	        $data["jscripts"] = array(
	        							"https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places", 
	        							base_url() . "js/pages/home.js",
	        							
	        						);
	
		   	$this->load->view('site/home', $data);
		   	
	   	}
    }
}
