<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {


	public function show($page_id = 0)
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

		$page = $this->site_model->get_page($page_id);
		
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
		} else {
			$data["nb_pm"] = 0;
			$data["nb_new_visits"] = 0;
			$data["nb_new_requests"] = 0;
			$data["nb_new_friends"] = 0;
			$data["nb_new_loves"] = 0;
			$data["total_notif"] = 0;
		}
    	
    	if($page != null)
    	{

			$data["page"] = $page;
			$data["title"] = $page->title;
			
			$this->load->model("user_model");
					    
		    $data["jscripts"] = array();
		    
			$this->load->view('page/show', $data);
		
		} else {
			show_404();
		}
	}
	
    
}
