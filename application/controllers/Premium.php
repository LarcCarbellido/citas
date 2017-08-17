<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Premium extends CI_Controller {

	function activate_feature()
	{
		$data = array();
		
		if(!$this->session->userdata("user_id")) {	
	    	// Not logged in
    		$data["result"] = 999;
    	} else {
	    	$feature = $this->input->post("name");
	    		    	
	    	if($feature != "see_who_loves_you" && $feature != "browse_invisibly" && $feature != "featured_one_week" && $feature != "featured_one_month") {
		    	$data["result"] = 998;
	    	} else {
		    	$this->load->model("premium_model");
		    	$this->load->model("site_model");
		    	$this->load->model("user_model");
		    	
		    	// Get user's coins
		    	$user_coins = $this->premium_model->get_user_coin($this->session->userdata("user_id"))->result_array();
		    	
		    	if(sizeof($user_coins) == 0) {
					$user_coins = 0;
				} else {
					$user_coins = $user_coins[0]["nb_coins"];
				}
				
				$settings = $this->site_model->get_website_settings()->result_array();
				$settings = $settings[0];
				
				// Get the feature price
				$feature_price = $settings[$feature . "_price"];
				
				// Not enought coins
				if($feature_price > $user_coins) {
					$data["result"] = 997;
				} else {
					
					// Activate feature
					if($feature == "see_who_loves_you") {
						$this->user_model->activate_purchase($this->session->userdata("user_id"), "see_loves_coins");
					} else if($feature == "browse_invisibly") {
						$this->user_model->activate_purchase($this->session->userdata("user_id"), "browse_invisibly_coins");
						$this->user_model->update_info($this->session->userdata("user_id"), array("browse_invisibly" => 1));
					} else if($feature == "featured_one_week") {
						$this->user_model->activate_purchase($this->session->userdata("user_id"), "featured_one_week_coins");
						
						// Add featured
						$this->premium_model->set_user_featured_one_week($this->session->userdata("user_id"), $feature);
					} else if($feature == "featured_one_month") {
						$this->user_model->activate_purchase($this->session->userdata("user_id"), "featured_one_week_coins");
						
						// Add featured
						$this->premium_model->set_user_featured_one_month($this->session->userdata("user_id"), $feature);
					}
					
					// Remove coins
					$this->premium_model->decrease_user_coins($this->session->userdata("user_id"), $feature_price);
					$data["result"] = 1;
				}
	    	}
	    }
	    
	    header('Content-type: application/json;');
		echo json_encode($data);
	}

	public function index()
	{
		if($this->session->userdata("user_firstform") != 1)
    	{
	    	redirect(base_url() . "user/firstlogin?redirect=true");
    	} else {
	    	$data = array();
			
			$this->load->model('user_model');
			$this->load->model('site_model');
	    	$this->load->model('premium_model');
	    	
	    	$settings = $this->site_model->get_website_settings()->result_array();
			$settings = $settings[0];
			
			$data["nb_lng"] = $this->site_model->count_language_redirections();
			$user_lng = $this->user_model->get_user_language($this->session->userdata('user_id'))->result_array();
			
			if(sizeof($user_lng) > 0) {
				$lng_opt = $user_lng[0]["language"];
			} else {
				$lng_opt = $settings["default_language"];
			}
	    	
	    	$this->lang->load('premium_lang', $lng_opt);
	    	$this->lang->load('site_lang', $lng_opt);
	    	
	    	$this->load->helper('text');
	    		    	
	    	$coins = $this->premium_model->get_coins()->result_array();
		    $data["coins"] = $coins;
	    		    	
	    	$data["title"]				= $this->lang->line("Premium");
	    	
	    	$this->load->model('user_model');
	    	    	
	    	$data["settings"] = $settings;
	    	
	    	// Get custom pages
			if($this->site_model->count_custom_pages() > 0) {
				$data["pages"] = $this->site_model->get_pages()->result_array();
			} else {
				$data["pages"] = null;
			}
			
			// Get user coins
			$user_coins = $this->premium_model->get_user_coin($this->session->userdata('user_id'))->result_array();
			
			if(sizeof($user_coins) == 0) {
				$user_coins = 0;
			} else {
				$user_coins = $user_coins[0]["nb_coins"];
			}
	
			$data["user_coins"] = $user_coins;
			
			$user = $this->user_model->get($this->session->userdata("user_id"))->result_array();
			$user = $user[0];
			
			$data["user"] = $user;
	
	    	$user_id = $this->session->userdata('user_id');
			$this->load->model("pm_model");
			$nb_pm = $this->pm_model->count_unread($this->session->userdata('user_id'));
	   		$data["nb_pm"] = $nb_pm;
			$new_profile_visits = $this->user_model->count_new_profile_visits($this->session->userdata("user_id"));
			$data["nb_new_visits"] = $new_profile_visits;
			$new_poke_requests = $this->user_model->count_new_requests($this->session->userdata("user_id"));
			$data["nb_new_requests"] = $new_poke_requests;
			$new_friends = $this->user_model->count_new_friends($this->session->userdata("user_id"));
			$data["nb_new_friends"] = $new_friends;
			
			$this->user_model->update_last_activity($this->session->userdata('user_id'));
			
			$new_loves = $this->user_model->count_new_loves($this->session->userdata("user_id"));
			$data["nb_new_loves"] = $new_loves;
			
			$data["total_notif"] = intval($new_profile_visits) + intval($nb_pm) + intval($new_loves) + intval($new_poke_requests) + intval($new_friends);
			    	
			// Get user's purchases
			$user_purchases = $this->premium_model->get_user_purchases($this->session->userdata("user_id"))->result_array();
			
			$loves_purchased = false;
			$invisibly_purchased = false;
			
			foreach($user_purchases as $u_p)
			{
				if($u_p["purchase_name"] == "see_loves_coins" || $u_p["purchase_name"] == "see_loves_paypal" || $u_p["purchase_name"] == "see_loves_stripe" || $u_p["purchase_name"] == "see_loves_paygol") 
					$loves_purchased = true;
				else if($u_p["purchase_name"] == "browse_invisibly_coins") 
					$invisibly_purchased = true; 
			}
			
			$data["loves_purchased"] = $loves_purchased;
			$data["invisibly_purchased"] = $invisibly_purchased;
			
			// Check if one week featured is active
			$one_week_featured = $this->premium_model->get_one_week_featured($this->session->userdata("user_id"))->result_array();
			    	
	    	if(sizeof($one_week_featured) == 0) {
				$is_featured_one_week = false;
				$data["one_week_featured"] = "";
			} else {
				$data["one_week_featured"] = $one_week_featured[0];
				$is_featured_one_week = true;
			}
			
			// Check if one month featured is active
			$one_month_featured = $this->premium_model->get_one_month_featured($this->session->userdata("user_id"))->result_array();
			    	
	    	if(sizeof($one_month_featured) == 0) {
				$is_featured_one_month = false;
				$data["one_month_featured"] = "";
			} else {
				$data["one_month_featured"] = $one_month_featured[0];
				$is_featured_one_month = true;
			}
			
			$data["is_featured_one_month"] = $is_featured_one_month;
			$data["is_featured_one_week"] = $is_featured_one_week;
			
			$data["jscripts"] = array(
		    	"https://checkout.stripe.com/checkout.js",
				base_url() . "js/pages/premium_index.js"
			);
		
	        $this->load->view('premium/index', $data);
        }
	}
	
	function payment_stripe_return($token_id = 0, $nb_coins = 0)
	{
		$this->load->library('stripe');
		$this->load->model("site_model");
		$this->load->model("user_model");
		$this->load->model("premium_model");
		
		$settings = $this->site_model->get_website_settings()->result_array();
		$settings = $settings[0];
				
		$token = json_decode($this->stripe->card_token_info($token_id, $settings["stripe_secret_key"]));
		
		if($token->id != null && !$token->used)
		{
			if($this->session->userdata("user_id"))
			{
				$user = $this->user_model->get($this->session->userdata("user_id"))->result_array();
				$user = $user[0];
				
				// Get the price of this coin value
				$coin = $this->premium_model->get_coin_infos($nb_coins)->result_array();
				$coin = $coin[0];
				
				$stripe_price = $coin["price"]*100;
				
				// Charge card
				$this->stripe->charge_card($stripe_price, $token->id, $nb_coins . " Coins on " . $settings["site_name"], $settings["stripe_secret_key"]);
				
				// -- Credit user's account
				// Check if user's credit account exists
				if(!$this->premium_model->check_user_coins_exists($this->session->userdata("user_id")))
				{
					// Create the user's credit account
					$this->premium_model->create_user_coins($this->session->userdata("user_id"));
				}
				
				// Update user coin row with the number of purchased coins
				$this->premium_model->update_user_coins($this->session->userdata("user_id"), $coin["nb"]);
				
				redirect(base_url() . "premium?action=payment_success");
			}
		}
	}
	
	function payment_paypal_return($nb_coins = 0)
	{
		$data = array();
		
		$this->load->model("user_model");
		$this->load->model("premium_model");
		$this->load->model('site_model');
	    
	    $settingsapp = $this->site_model->get_website_settings()->result_array();
	    $settingsapp = $settingsapp[0];
	    $data["settings"] = $settingsapp;
	    		    			
		if($this->session->userdata("user_id"))
		{
			$user = $this->user_model->get($this->session->userdata("user_id"))->result_array();
			$user = $user[0];
			
			$this->load->library('merchant');
			$this->merchant->load('paypal_express');
			
			// Get the price of this coin value
			$coin = $this->premium_model->get_coin_infos($nb_coins)->result_array();
			$coin = $coin[0];
			
			$settings = array(
			    'username' 		=> $settingsapp["paypal_api_username"],
			    'password'	 	=> $settingsapp["paypal_api_pw"],
			    'signature' 	=> $settingsapp["paypal_api_sign"],
			    'test_mode' 	=> false
			);
			
			$this->merchant->initialize($settings);
			
			$params = array(
				'amount' => $coin["price"],
				'email' => "",
			    'currency' => $settingsapp["currency"],
			    'description' => $coin["nb"] . " Coins on " . $settingsapp["site_name"],
			    'return_url' => base_url() . 'premium/payment_paypal_return',
			    'cancel_url' => base_url() . 'premium?action=cancel'
		    );
			
			$response = $this->merchant->purchase_return($params);
			
			if ($response->status() == Merchant_response::COMPLETE)
			{
				// -- Credit user's account
				// Check if user's credit account exists
				if(!$this->premium_model->check_user_coins_exists($this->session->userdata("user_id")))
				{
					// Create the user's credit account
					$this->premium_model->create_user_coins($this->session->userdata("user_id"));
				}
				
				// Update user coin row with the number of purchased coins
				$this->premium_model->update_user_coins($this->session->userdata("user_id"), $coin["nb"]);
				
				redirect(base_url() . "premium?action=payment_success");
				
			} else {
				redirect(base_url() . "premium?action=error_payment");
			}
		}
	}
	
	function pay_with_paypal($nb_coins)
	{
		if($nb_coins == null) {
			show_404();
		} else {
			
			$this->load->model("premium_model");
			
			// Check if this number of coins exists
			if($this->premium_model->check_coins($nb_coins)) {
		
				if($this->session->userdata("user_id"))
				{
					
					// Get the price of this coin value
					$coin = $this->premium_model->get_coin_infos($nb_coins)->result_array();
					$coin = $coin[0];
									
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
						'amount' => $coin["price"],
						'email' => "",
					    'currency' => $settingsapp["currency"],
					    'description' => $coin["nb"] . " Coins on " . $settingsapp["site_name"],
					    'return_url' => base_url() . 'premium/payment_paypal_return/' . $coin["nb"],
					    'cancel_url' => base_url() . 'premium?action=cancel'
				    );
				
					$response = $this->merchant->purchase($params);
					print_r($response);
				}
			
			} else {
				show_error("There is an error. This coin value doesn't exist.");
			}
		}
	}
	
}
