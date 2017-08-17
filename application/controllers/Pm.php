<?php
class pm extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
    }
    
    function index()
    {
	    if (!$this->session->userdata("user_id")) {								
			redirect(base_url(), 'refresh');
		} else {
			
		 	$data = array();
		 	$this->load->model('site_model');
		 	$this->load->model('user_model');

			$settings = $this->site_model->get_website_settings()->result_array();
		    $settings = $settings[0];
		    
			$data["nb_lng"] = $this->site_model->count_language_redirections();
			$user_lng = $this->user_model->get_user_language($this->session->userdata('user_id'))->result_array();
			
			if(sizeof($user_lng) > 0) {
				$lng_opt = $user_lng[0]["language"];
			} else {
				$lng_opt = $settings["default_language"];
			}
			
			$this->lang->load('pm_lang', $lng_opt);
			$this->lang->load('site_lang', $lng_opt);
	    			
			if($this->session->userdata("user_firstform") == 0) {
		    	redirect( base_url() . "user/firstlogin?redirect=true" );
	    	}  
		
		    $data["settings"] = $settings;
		    
		    // Get custom pages
			if($this->site_model->count_custom_pages() > 0) {
				$data["pages"] = $this->site_model->get_pages()->result_array();
			} else {
				$data["pages"] = null;
			}

	    	$data["title"]				= $this->lang->line("pm_title");
	    	
	    	$this->load->model('pm_model', 'pmManager');

	    	$this->load->library("pagination");
	    	
	    	$last_convs = $this->pmManager->get_last_conversations($this->session->userdata('user_id'), 30)->result_array();
	    			
    		$nb_pm = $this->pmManager->count_unread($this->session->userdata('user_id'));
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
				    
			$data["jscripts"] = array(
				base_url() . "js/pages/pm.js"
			);
				    	
	    	if(sizeof($last_convs) == 0) {
		    	$this->load->view('pm/index_no_pm', $data);
	    	} else {
		    	
				$data["last_conv"] = $this->pmManager->get_messages_from_conv($last_convs[0]["id"], 30);
		    	
		    	$conv_row = $this->pmManager->count_conversations($this->session->userdata('user_id'));
			    		    		    	
		    	$config 					= array();
		    	$config["base_url"] 		= base_url() . "conversations";
				$config["total_rows"] 		= $conv_row;
				$config["per_page"] 		= 20;
				$config["uri_segment"] 		= 2;
		
				$this->pagination->initialize($config);
		    	
		    	$page 						= ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		    	$data["per_page"] 			= $config["per_page"];
		    	$data["conversations"]		= $last_convs;
		    	$data["pagination"]			= $this->pagination->create_links();
		    	$data["conv_model"]			= $this->pmManager;
		    	
		        $this->load->view('pm/index', $data);
		        
	        }
        }
    }
    
    function send_reply()
    {
    	$data = array();
    	
    	$this->load->model('user_model', 'userManager');	
    	$this->load->model('site_model');	
    	
    	$settings = $this->site_model->get_website_settings()->result_array();
		$settings = $settings[0];
    	
		$user_lng = $this->userManager->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}
    	
    	$this->lang->load('pm_lang', $lng_opt);
    	$this->lang->load('site_lang', $lng_opt);
    	
    	if(DEMO_MODE == 1) {
		    $data["result"] = 500;
	    } else {
    
		    if ($this->session->userdata("user_id")) {	
		    	$this->load->model('pm_model', 'pmManager'); 
		    	
		    	$conv_id = $this->input->post('conv_id');
		    	$user_id = $this->input->post('user_id');
		    	$form_message = $this->security->xss_clean(strip_tags($this->input->post('content')));
		    	
		    	if($form_message != "")
		    	{
					// Check if the conversation already exists
					$conv = $this->pmManager->get_conv($user_id, $this->session->userdata('user_id'));
					
					if($conv != NULL) {
						if(!$this->userManager->check_blocked($user_id, $this->session->userdata('user_id'))) {
							$msg_id = $this->pmManager->new_message_to_conv($conv->id, $form_message, $this->session->userdata('user_id'));
							$user = $this->userManager->get($this->session->userdata("user_id"))->result_array();
							$user = $user[0];	
							
							// Make conv as unread for the receiver user
							if($conv->sender_id == $this->session->userdata('user_id'))
							{
								$this->pmManager->update_conv($conv->id, array("is_read_recipient" => 0, "last_answer_user_id" => $this->session->userdata('user_id')));
							} else {
								$this->pmManager->update_conv($conv->id, array("is_read_sender" => 0, "last_answer_user_id" => $this->session->userdata('user_id')));
							}
							
							$receiver = $this->userManager->get($user_id)->result_array();
							$receiver = $receiver[0];
							
							if(!$this->pmManager->check_daily_notif($receiver["uid"])) {
								$message = "You have received some new private messages.";
	
								$title_email = "New private message";
	
								$this->send_mail($title_email, $receiver["email"], $message, $title_email, $receiver["username"]);
								$this->pmManager->add_daily_notif($receiver["uid"]);
							}	
							
							$data["user"] = $user;
							$msg_date_php = date('m/d/Y h:i:s a', time());	
							$msg_date = date("c", strtotime($msg_date_php));	
							$data["msg_date"] = $msg_date;
							$data["msg_id"] = $msg_id;		
							$data["result"] = 1;
							$data["form_message"] = $form_message;
						} else {
							$data["result"] = 996;
						}
	 				} else {
		 				$data["result"] = 997;
	 				}
								
				} else {
					$data["result"] = 998;
				}
		    
		    } else {
			    $data["user"] = "";
			    $data["result"] = 999;
		    }
	    }
	    
	    header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function conversation($user_id = 0)
    { 
	    $data = array();
	    $this->load->model('site_model');
	    $this->load->model('user_model');

		$data["nb_lng"] = $this->site_model->count_language_redirections();
	    $settings = $this->site_model->get_website_settings()->result_array();
		$settings = $settings[0];
	    
	    $user_lng = $this->user_model->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}
	    
	    $this->lang->load('pm_lang', $lng_opt);
	    $this->lang->load('site_lang', $lng_opt);
	    
    	if (!$this->session->userdata("user_id")) {								
			redirect(base_url(), 'refresh');
		} else {
			
		   
	    	$data["title"]				= "Mensajes privados";
	    	
	    	$this->load->model('pm_model', 'pmManager');
	    
			// Get custom pages
			if($this->site_model->count_custom_pages() > 0) {
				$data["pages"] = $this->site_model->get_pages()->result_array();
			} else {
				$data["pages"] = null;
			}
	    
		    $data["settings"] = $settings;
	    	$this->load->library("pagination");
			
			$user = $this->user_model->get($user_id)->result_array();
			
			if(sizeof($user) == 0) {
				show_404();
			} else {
				$user = $user[0];
				
				$data["user"] = $user;
							
				$current_conv = $this->pmManager->get_conv($user_id, $this->session->userdata('user_id'));
				
				$this->user_model->update_last_activity($this->session->userdata('user_id'));
				
				if($current_conv != null) {
					$data["last_conv"] = $this->pmManager->get_messages_from_conv($current_conv->id, 200);
						    			
					$data["current_conv"] = $current_conv;
					$nb_pm = $this->pmManager->count_unread($this->session->userdata('user_id'));
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
										
					// Mark the answers of this conversation as read
					$this->pmManager->mark_as_read($user_id, $current_conv->id);
					// Mark conversations
					if($current_conv->sender_id == $this->session->userdata('user_id') && $current_conv->is_read_sender == 0)
					{
							
						$this->pmManager->update_conv($current_conv->id, array("is_read_sender" => 1));
					} else if($current_conv->recipient_id == $this->session->userdata('user_id') && $current_conv->is_read_recipient == 0) {
						$this->pmManager->update_conv($current_conv->id, array("is_read_recipient" => 1));
					}
					
					$data["jscripts"] = array(
						base_url() . "js/pages/display_conv.js"
					);
			    	
			        $this->load->view('pm/display_conv', $data);
		        } else {
			        show_404();
		        }
	        }
        }
    }
        
    function conversations()
    {
	    $data = array();
	    
		$this->load->model('user_model');
	    $this->load->model('site_model');
	    
	    $data["nb_lng"] = $this->site_model->count_language_redirections();
		$settings = $this->site_model->get_website_settings()->result_array();
		$settings = $settings[0];
	    
	    $user_lng = $this->user_model->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}
	    
	    $this->lang->load('pm_lang', $lng_opt);
	    $this->lang->load('site_lang', $lng_opt);
	    
    	if (!$this->session->userdata("user_id")) {								
			redirect(base_url(), 'refresh');
		} else {
		
			if($this->session->userdata("user_firstform") == 0) {
		    	redirect( base_url() . "user/firstlogin?redirect=true" );
	    	}  
		
		    
	    	$data["title"]				= "Mensajes privados";
	    	
	    	$this->load->model('pm_model', 'pmManager');

	    	// Get custom pages
			if($this->site_model->count_custom_pages() > 0) {
				$data["pages"] = $this->site_model->get_pages()->result_array();
			} else {
				$data["pages"] = null;
			}
		    
		    $data["settings"] = $settings;
	    	$this->load->library("pagination");
	    	
	    	$last_convs = $this->pmManager->get_last_conversations($this->session->userdata('user_id'))->result_array();
	    	
	    	$new_friend_requests = $this->user_model->count_new_friend_requests($this->session->userdata("user_id"));
			$data["nb_new_requests"] = $new_friend_requests;
	    			
			$data["nb_pm"] = $this->pmManager->count_unread($this->session->userdata('user_id'));
			
			$new_profile_visits = $this->user_model->count_new_profile_visits($this->session->userdata("user_id"));
			$data["nb_new_visits"] = $new_profile_visits;
			
			$new_friends = $this->user_model->count_new_friends($this->session->userdata("user_id"));
			$data["nb_new_friends"] = $new_friends;
			
			$data["total_notif"] = intval($new_profile_visits) + intval($nb_pm) + intval($new_loves) + intval($new_poke_requests) + intval($new_friends);
	    	
	    	if(sizeof($last_convs) == 0) {
		    	$data["jscripts"] = array(
					base_url() . "js/pages/pm.js"
				);
				
		    	$this->load->view('pm/index_no_pm', $data);
	    	} else {
		    	
				$data["last_conv"] = $this->pmManager->get_messages_from_conv($last_convs[0]["id"], 50);
		    	
		    	$conv_row = $this->pmManager->count_conversations($this->session->userdata('user_id'));
			    		    		    	
		    	$config 					= array();
		    	$config["base_url"] 		= base_url() . "conversations";
				$config["total_rows"] 		= $conv_row;
				$config["per_page"] 		= 20;
				$config["uri_segment"] 		= 2;
		
				$this->pagination->initialize($config);
				
				$current_conv = $last_convs[0];
				
				// Mark the answers of this conversation as read
				$this->pmManager->mark_as_read($current_conv["from_userid"], $current_conv["id"]);
				
				// Mark conversations
				if($current_conv["sender_id"] == $this->session->userdata('user_id') && $current_conv["is_read_sender"] == 0)
				{
					$this->pmManager->update_conv($current_conv["id"], array("is_read_sender" => 1));
				} else if($current_conv["recipient_id"] == $this->session->userdata('user_id') && $current_conv["is_read_recipient"] == 0) {
					$this->pmManager->update_conv($current_conv["id"], array("is_read_recipient" => 1));
				}
		    	
		    	$page 						= ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		    	$data["per_page"] 			= $config["per_page"];
		    	$data["conversations"]		= $last_convs;
		    	$data["pagination"]			= $this->pagination->create_links();
		    	$data["conv_model"]			= $this->pmManager;
		    	
		    	$data["jscripts"] = array(
					base_url() . "js/pages/pm.js"
				);
				
		        $this->load->view('pm/index', $data);
		        
	        }
        }
    }
    
    function send_pm()
    {
    	$data = array();
    	$this->load->model('user_model', 'userManager');
    	$this->load->model('site_model');
    	
    	$settings = $this->site_model->get_website_settings()->result_array();
		$settings = $settings[0];
		
    	$user_lng = $this->userManager->get_user_language($this->session->userdata('user_id'))->result_array();
		
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}
    	
    	$this->lang->load('pm_lang', $lng_opt);
    	$this->lang->load('site_lang', $lng_opt);
    	
    	if(DEMO_MODE == 1) {
		    $data["result"] = 500;
	    } else {
    
		    if ($this->session->userdata("user_id")) {	
		    		
		    		
		    		
		    	$this->load->model('pm_model', 'pmManager');
		    	
		    	$user_id = $this->input->post('user_id');
		    	$form_message = $this->security->xss_clean(strip_tags($this->input->post('message')));
		    	
		    	if($form_message != "")
		    	{
			    	if($user_id != $this->session->userdata('user_id'))
			    	{
			    	
			    		if($this->session->userdata("user_firstform") == 0) {
					    	$data["result"] = 995;
				    	} else {
					    	$receiver = $this->userManager->get($user_id)->result_array();
					    	
					    	if($receiver != NULL)
							{
								if(!$this->userManager->check_blocked($user_id, $this->session->userdata('user_id'))) {
									// Check if the conversation already exists
									$conv = $this->pmManager->get_conv($user_id, $this->session->userdata('user_id'));
									
									if($conv != NULL) {
										$this->pmManager->new_message_to_conv($conv->id, $form_message, $this->session->userdata('user_id'));
										
										// Make conv as unread for the receiver user
										if($conv->sender_id == $this->session->userdata('user_id'))
										{
											$this->pmManager->update_conv($conv->id, array("is_read_recipient" => 0, "last_answer_user_id" => $this->session->userdata('user_id')));
										} else {
											$this->pmManager->update_conv($conv->id, array("is_read_sender" => 0, "last_answer_user_id" => $this->session->userdata('user_id')));
										}
										
										$datauser = $this->userManager->get($this->session->userdata('user_id'))->result_array();
										$datauser = $datauser[0];
										
										$data["user"] = $datauser;
										
									} else {
										$new_conv = $this->pmManager->new_conv($this->session->userdata('user_id'), $user_id);
										$this->pmManager->new_message_to_conv($new_conv, $form_message, $this->session->userdata('user_id'));
									}
										
									if(!$this->pmManager->check_daily_notif($receiver[0]["uid"])) {
										$message = "You have received some new private messages.";
		
										$title_email = "New private message";
			
										$this->send_mail($title_email, $receiver[0]["email"], $message, $title_email, $receiver[0]["username"]);
										$this->pmManager->add_daily_notif($receiver[0]["uid"]);
									}
	
									$data["result"] = 1;
								} else {
									$data["result"] = 994;
								}
							} else {
								$data["result"] = 996;
							}
						
						}
			    	}
			    	else
					{
						$data["result"] = 997;
					}
				
				} else {
					$data["result"] = 998;
				}
		    
		    } else {
			    $data["result"] = 999;
		    }
	    }
	    
	    header('Content-type: application/json;');
		echo json_encode($data);
    }
    
    function refresh_conv()
    {
	    $data = array();
	    
	    $conv_id = $this->input->post('conv_id');
	    $last_message_id = $this->input->post('last_message_id');
	    
	    $this->load->model("pm_model");
	    
	    if($this->session->userdata('user_id')) {
	    	$conv = $this->pm_model->get_conv_by_id_user_id($conv_id, $this->session->userdata('user_id'));
	    	
	    	if($conv != NULL) {
		    	
		    	$last_messages = $this->pm_model->get_last_messages_from_conv($conv_id, $last_message_id)->result_array();
		    	
		    	$data["last_messages"] = $last_messages;
		    	$data["error"] = 0;
		    	
	    	} else {
		    	$data["error"] = 998;
	    	}
	    } else {
		    $data["error"] = 999;
	    }
	    
	    header('Content-type: application/json;');
		echo json_encode($data);
    }

       
    // Verify if the user exists
    function user_exists($str)
    {
	    $this->load->model('user_model', 'userManager');
	    
	    if($this->userManager->get_user_by_username($str) == NULL)
	    {
	    	$this->form_validation->set_message('user_exists', 'Cet utilisateur n\'existe pas :-(');
	    	return false;
	    }
	    	    	    
	    return true;
    }
    
    // Verify if the user id exists
    function user_id_exists($str)
    {
	    $this->load->model('user_model', 'userManager');
	    
	    if($this->userManager->get_user($str) == NULL)
	    {
	    	$this->form_validation->set_message('user_id_exists', 'Cet utilisateur n\'existe pas :-(');
	    	return false;
	    }
	    	    	    
	    return true;
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
?>