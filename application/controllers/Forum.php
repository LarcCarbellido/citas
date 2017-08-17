<?php
class Forum extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
    }
    
    // Display the last topics and the list of the categories
    function index()
    {    	
	    
	    if($this->session->userdata('user_id'))
	    {
		    if($this->session->userdata("user_firstform") != 1)
	    	{
		    	redirect(base_url() . "user/firstlogin?redirect=true");
	    	} else {
		    	$data = array();
		    	
		    	$this->load->model('user_model');
		    	$this->load->model('site_model');

				$settings = $this->site_model->get_website_settings()->result_array();
				$settings = $settings[0];
				
				$data["nb_lng"] = $this->site_model->count_language_redirections();
				$user_lng = $this->user_model->get_user_language($this->session->userdata('user_id'))->result_array();
				
				if(sizeof($user_lng) > 0) {
					$lng_opt = $user_lng[0]["language"];
				} else {
					$lng_opt = $settings["default_language"];
				}
		    	
		    	$this->load->helper('text');
		    	$this->lang->load('forum_lang', $lng_opt);
		    	$this->lang->load('site_lang', $lng_opt);
		    	$this->lang->load('user_lang', $lng_opt);
		    		    	
		    	$data["title"]				= sprintf($this->lang->line("forum_title"), $settings["site_name"]);
		    	
		    	$this->load->model('forum_model', 'forumManager');
		    	$this->load->library("pagination");
		    	
		    	$topics_row = $this->forumManager->count_records();
		    	
		    	$data["settings"] = $settings;
		    	
		    	// Get custom pages
				if($this->site_model->count_custom_pages() > 0) {
					$data["pages"] = $this->site_model->get_pages()->result_array();
				} else {
					$data["pages"] = null;
				}
		
		    	
				if($this->session->userdata('user_id') == null) {
			    	$user_id = 0;
			    	$data["nb_pm"] = "";
			    	$data["nb_new_requests"] = 0;
			    	$data["nb_new_visits"] = 0;
			    	$data["total_notif"] = 0;
		    	} else {
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
		    	}
				
			    		    		    	
		    	$config 					= array();
		    	$config["base_url"] 		= base_url("forum/index");
				$config["total_rows"] 		= $topics_row;
				$config["per_page"] 		= 20;
				$config["uri_segment"] 		= 3;
		
				$this->pagination->initialize($config);
		    	
		    	$page 						= ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		    	
		    	$data["page"]				= $page;
		
		    	$data["per_page"] 			= $config["per_page"];
		    	$data["topics"]				= $this->forumManager->get_last_topics($page, $config["per_page"]);
		    	$data["topics_sticky"]		= $this->forumManager->get_last_sticky_topics();
		    	$data["pagination"]			= $this->pagination->create_links();
		    	$data["categories"]			= $this->forumManager->get_categories();
		    	$data["forum_model"]		= $this->forumManager;
		    	
		    	$data["jscripts"] = array(
					base_url() . "js/pages/forum.js"
				);
		    	
		        $this->load->view('forum/index', $data);
	        }
        } else {
	        header("Location: " . base_url());
        }
    }
    
    // Display the last topics and the list of the categories
    function category($id = 0)
    {    	
	    
	    if($this->session->userdata('user_id'))
	    {
	    	$data = array();
	    	$this->load->model('forum_model', 'forumManager');
	    	$this->load->model("site_model");
	    	$this->load->model("user_model");
	    	
	    	$settings = $this->site_model->get_website_settings()->result_array();
			$settings = $settings[0];
	    	
	    	$data["nb_lng"] = $this->site_model->count_language_redirections();
			$user_lng = $this->user_model->get_user_language($this->session->userdata('user_id'))->result_array();
			
			if(sizeof($user_lng) > 0) {
				$lng_opt = $user_lng[0]["language"];
			} else {
				$lng_opt = $settings["default_language"];
			}
	    	
	    	$this->lang->load('forum_lang', $lng_opt);
	    	$this->lang->load('site_lang', $lng_opt);
	    	$this->lang->load('user_lang', $lng_opt);
	    				
			$data["settings"] = $settings;
			// Get custom pages
			if($this->site_model->count_custom_pages() > 0) {
				$data["pages"] = $this->site_model->get_pages()->result_array();
			} else {
				$data["pages"] = null;
			}
	
	    	if(!$this->forumManager->cat_exists($id))
		    {
		    	show_404();
		    } else {
		    	
		    	if($this->session->userdata('user_id') == null) {
			    	$user_id = 0;
			    	$data["nb_pm"] = "";
			    	$data["nb_new_visits"] = 0;
					$data["nb_new_requests"] = 0;
		    	} else {			    				
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
	
		    	}
		    	
		    	$category = $this->forumManager->get_category($id);
		    	
		    	$data["title"]				= $category->name;
		    	
		    	$this->load->library("pagination");
		    	
		    	$topics_row = $this->forumManager->count_topics_by_category_id($id);
			    		    		    	
		    	$config 					= array();
		    	$config["base_url"] 		= base_url() . "forum/category/" . $id;
				$config["total_rows"] 		= $topics_row;
				$config["per_page"] 		= 20;
				$config["uri_segment"] 		= 4;
		
				$this->pagination->initialize($config);
		    	
		    	$page 						= ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		    	
		    	
		    	$data["page"]				= $page;
		    	
		    	$data["topics"]				= $this->forumManager->get_last_topics_by_category($id, $page, $config["per_page"]);
		    	$data["pagination"]			= $this->pagination->create_links();
		    	$data["categories"]			= $this->forumManager->get_categories();
		    	$data["category"]			= $category;
		    	
		    	$data["jscripts"] = array(
					base_url() . "js/pages/forum.js"
				);
				
		        $this->load->view('forum/category', $data);
	        }
        }
	}
    
    // Display a topic with the answers
    function topic($id = 0)
    {
	    
	   	if($this->session->userdata('user_id'))
	    {
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
		    
		    $this->lang->load('forum_lang', $lng_opt);
		    $this->lang->load('site_lang', $lng_opt);
	    	
	    	$this->load->model('forum_model', 'forumManager');
	    	$this->load->library("pagination");
	    		    				
			$data["settings"] = $settings;
			
			// Get custom pages
			if($this->site_model->count_custom_pages() > 0) {
				$data["pages"] = $this->site_model->get_pages()->result_array();
			} else {
				$data["pages"] = null;
			}
	
	    	$topic = $this->forumManager->get_topic($id);
	    	
	    	if($this->session->userdata('user_id') == null) {
		    	$user_id = 0;
		    	$data["nb_pm"] = "";
		    	$data["nb_new_visits"] = 0;
		    	$data["nb_new_requests"] = 0;
	    	} else {
		    	$user_id = $this->session->userdata('user_id');
		    	$this->load->model('pm_model');
			
				$data["nb_pm"] = $this->pm_model->count_unread($this->session->userdata('user_id'));
	    	}
	    	
	    	if($topic == NULL)
	    	{
				show_404();    	
	    	} else {
	    	
	    		if($this->session->userdata('user_id')) {
		    		
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
					
	    		}
		    	
		    	$data["topic"]				= $topic;
		    	$data["title"]				= $topic->title;
		    	
		    	$answers_rows = $this->forumManager->count_answers_by_topic_id($id);
			    		    		    	
		    	$config 					= array();
		    	$config["base_url"] 		= base_url() . "forum/topic/" . $id;
				$config["total_rows"] 		= $answers_rows;
				$config["per_page"] 		= 25;
				$config["uri_segment"] 		= 4;
		
				$this->pagination->initialize($config);
		    	
		    	$page 						= ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		    	$data["page"]				= $page;
		    	$data["per_page_disp"]		= 25; 
		    	$data["page"]				= $page;
		    	$data["answers"]			= $this->forumManager->get_answers_by_topic_id($id, $page, $config["per_page"]);
		    	$data["pagination"]			= $this->pagination->create_links();
		    	
		    	/* FROM FOR THE ANSWER */
		    	$this->form_validation->set_rules('content', '"Contenu"', 'trim|required');
		    	
		    	// Form post
			    if ($this->form_validation->run()) {	
				    
				    if(DEMO_MODE == 1) {
						header("Location: " . base_url() . "forum/topic/$id?action=demo");			
					} else {
				    	if($this->session->userdata("user_firstform") == 0) {
					    	redirect( base_url() . "user/firstlogin?redirect=true" );
				    	}  
				    
				    	if($this->forumManager->topic_exists($id)) {	    	
					    	$insertAndId = $this->forumManager->add_answer(
					    						$id, 
												$this->input->post('content'),
												$this->session->userdata('user_id')
											);
					    						
					    	if($topic->app_pref_forum == 1) {
					    		$email_msg = "An answer has been posted on your topic. Open the app and go to the forum to see it :-) !";					
					    	
								$this->send_mail("New answer on your topic", $topic->email, $email_msg, "New answer on your topic", $topic->username);
					    	}
					    				    	
					    	redirect(base_url() . 'forum/topic/' . $id . '?action=reply_added');
				    	} else {
					    	$this->session->set_flashdata('no_topic', 'true');	 
					    	redirect(base_url() . 'forum');
				    	}
			    	}
			    }
			    
			    $data["jscripts"] = array(
					base_url() . "js/pages/forum_topic.js"
				);
		    	
		        $this->load->view('forum/topic', $data);
	        }
        }
    }
    
    // Edit an answer
    function editanswer($id = 0)
    {
		$data = array();

		$this->load->model('site_model');
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
	    
	    $this->lang->load('forum_lang', $lng_opt);
	    $this->lang->load('site_lang', $lng_opt);
	    
	    if(DEMO_MODE == 1) {
			header("Location: " . base_url() . "forum?action=demo");			
		} else {
		    if (!$this->session->userdata('user_id')) {	
				show_error("You are not logged in anymore.");
			} else {	
			
				if($this->session->userdata("user_firstform") == 0) {
			    	redirect( base_url() . "user/firstlogin?redirect=true" );
		    	}  
		    	
				$this->load->model('forum_model', 'forumManager');
		    	
		    	$answer = $this->forumManager->get_answer($id);
		    	
		    	if($answer != null) {
			    	
			    	if($answer->uid == $this->session->userdata('user_id') || $this->session->userdata('user_rank') > 0) {															
						// Get custom pages
						if($this->site_model->count_custom_pages() > 0) {
							$data["pages"] = $this->site_model->get_pages()->result_array();
						} else {
							$data["pages"] = null;
						}
								
				    	$user_id = $this->session->userdata('user_id');
								
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
						
						$this->user_model->update_last_activity($this->session->userdata('user_id'));
						
						// Page title
						$data["title"]	= $this->lang->line("edit_answer_title");
						
						$data["answer"] = $answer;
					
						// Form part
					    $this->form_validation->set_rules('content', '"Content"', 'trim|required');
					    
					    
					    // Form post
					    if ($this->form_validation->run()) {
					    
					    	$this->load->model('forum_model', 'forumManager');
					    	
					    	$this->forumManager->edit_answer(
		    					$id,
		    					array(
		    						"content" => $this->input->post('content')
		    					)
							);
					    							
					    	redirect(base_url() . 'forum/topic/' . $answer->topic_id . '?action=answer_edited');
					    }
					    
					    $data["jscripts"] = array();
					    				    
					    $this->load->view('forum/edit_answer', $data);
				    } else {
					    show_error("You can't access to this part.");
				    }
			    } else {
				    show_404();
			    }
			}
		}
    }
    
    // Delete a forum answer
	function deleteanswer($id = 0)
	{
		$this->load->model('forum_model');
		$this->load->model('site_model');
		$this->load->model('user_model');
		
		$settings = $this->site_model->get_website_settings()->result_array();
		$settings = $settings[0];
		
	    $user_lng = $this->user_model->get_user_language($this->session->userdata('user_id'))->result_array();
			
		if(sizeof($user_lng) > 0) {
			$lng_opt = $user_lng[0]["language"];
		} else {
			$lng_opt = $settings["default_language"];
		}
		
		$this->lang->load('site_lang', $lng_opt);
		
		if($this->session->userdata('user_id'))
	    {
		
			if(DEMO_MODE == 1) {
				header("Location: " . base_url() . "forum?action=demo");			
			} else {
				
				$answer = $this->forum_model->get_answer($id);
		    	
		    	if($answer != null) {
			    	
			    	if($answer->uid == $this->session->userdata('user_id') || $this->session->userdata('user_rank') > 0) {
				
						// DELETE TOPIC ANSWERS
						$this->forum_model->delete_answer($id);
					
						header("Location: " . base_url() . "forum/topic/" . $answer->topic_id . "?action=delete_answer_ok");
					}	
				
				} else {
					show_404();
				}
			
			}
		
		}
	}
    
    // Delete a forum topic
	function deletetopic($id = 0)
	{
		if($this->session->userdata('user_id'))
	    {
			$this->load->model('forum_model');
			$this->lang->load('site_lang');
			
			if(DEMO_MODE == 1) {
				header("Location: " . base_url() . "forum/topic/$id?action=demo");			
			} else {
				
				$topic = $this->forum_model->get_topic($id);
		    	
		    	if($topic != null) {
			    	
			    	if($topic->uid == $this->session->userdata('user_id') || $this->session->userdata('user_rank') > 0) {
				
						// DELETE TOPIC ANSWERS
						$this->forum_model->delete_answers_from_topic($id);
						
						// DELETE THIS TOPIC
						$this->forum_model->delete_topic($id);
					
						header("Location: " . base_url() . "forum?action=delete_topic_ok");
					}	
				
				} else {
					show_404();
				}
			
			}
		}
	}
    
    // Edit a topic
    function edittopic($id = 0)
    {
	    if($this->session->userdata('user_id'))
	    {
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
			    
		    $this->lang->load('forum_lang', $lng_opt);
		    $this->lang->load('site_lang', $lng_opt);
		    
		    if(DEMO_MODE == 1) {
				header("Location: " . base_url() . "forum?action=demo");			
			} else {
			    if (!$this->session->userdata('user_id')) {	
					show_error("You are not logged in anymore.");
				} else {	
				
					if($this->session->userdata("user_firstform") == 0) {
				    	redirect( base_url() . "user/firstlogin?redirect=true" );
			    	}  
			    	
					$this->load->model('forum_model', 'forumManager');
			    	
			    	$topic = $this->forumManager->get_topic($id);
			    	
			    	if($topic != null) {
				    	
				    	if($topic->uid == $this->session->userdata('user_id') || $this->session->userdata('user_rank') > 0) {							
							
							// Get custom pages
							if($this->site_model->count_custom_pages() > 0) {
								$data["pages"] = $this->site_model->get_pages()->result_array();
							} else {
								$data["pages"] = null;
							}
							
							$data["settings"] = $settings;
							
					    	$user_id = $this->session->userdata('user_id');
				
					    	$this->load->model('user_model');
						
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
							
							$this->user_model->update_last_activity($this->session->userdata('user_id'));
							
							// Page title
							$data["title"]	= $this->lang->line("edit_topic_title");
							
							$data["topic"] = $topic;
						
							// Form part
						    $this->form_validation->set_rules('title',  '"Title"',  'trim|required|min_length[5]|max_length[80]');
						    $this->form_validation->set_rules('content', '"Content"', 'trim|required');
						    $this->form_validation->set_rules('category', '"Category"', 'trim|max_length[20]|required|callback_check_cat_exists');
						    
						    
						    // Form post
						    if ($this->form_validation->run()) {
						    
						    	$this->load->model('forum_model', 'forumManager');
						    	
						    	$this->forumManager->edit_topic(
			    					$id,
			    					array(
			    						"title" => $this->input->post('title'),
			    						"content" => $this->input->post('content'),
			    						"cat_id" => $this->input->post('category')
			    					)
								);
						    							
						    	redirect(base_url() . 'forum/topic/' . $id . '?action=topic_edited');
						    }
						    
						    $data["categories"]			= $this->forumManager->get_categories();
						    $data["jscripts"] = array();
						    $this->load->view('forum/edit_topic', $data);
					    } else {
						    show_error("You can't access to this part.");
					    }
				    } else {
					    show_404();
				    }
				}
			}
		}
    }
    
    // Create a new topic
    function create()
    {
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
		
		$this->lang->load('forum_lang', $lng_opt);
		$this->lang->load('site_lang', $lng_opt);
	    
	    if(DEMO_MODE == 1) {
			header("Location: " . base_url() . "forum?action=demo");			
		} else {
		    if (!$this->session->userdata('user_id')) {	
				show_error("You are not logged in anymore.");
			} else {	
			
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
				
		    	$user_id = $this->session->userdata('user_id');
	
		    	$this->load->model('user_model');
			
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
				
				$this->user_model->update_last_activity($this->session->userdata('user_id'));
				
				// Page title
				$data["title"]	= $this->lang->line("create_new_topic");
			
				// Form part
			    $this->form_validation->set_rules('title',  '"Title"',  'trim|required|min_length[5]|max_length[80]');
			    $this->form_validation->set_rules('content', '"Content"', 'trim|required');
			    $this->form_validation->set_rules('category', '"Category"', 'trim|max_length[20]|required|callback_check_cat_exists');
			    
			    $this->load->model('forum_model', 'forumManager');
			    
			    // Form post
			    if ($this->form_validation->run()) {
			    
			    	$this->load->model('forum_model', 'forumManager');
			    	
			    	$insertAndId = $this->forumManager->add_topic(
			    						$this->input->post('title'), 
										$this->input->post('content'),
										$this->input->post('category'),
										$this->session->userdata('user_id')
									);
			    							
			    	redirect(base_url() . 'forum/topic/' . $insertAndId . '?action=topic_created');
			    }
			    
			    $data["categories"]			= $this->forumManager->get_categories();
			    $data["jscripts"] = array();
			    $this->load->view('forum/new_topic', $data);
			}
		}
    }

    // Callback to check if the selected cat exists
    function check_cat_exists($str) {
	    $cat_id = (int) $str;
	    
	    $this->form_validation->set_message('check_cat_exists', 'Please select a category.');
	    
	    $this->load->model('forum_model', 'forumManager');
	    if($this->forumManager->cat_exists($cat_id))
	    {
		    return true;
	    } else {
		    return false;
	    }
    }
    
	private function send_mail($subject, $email, $message, $title, $username)
	{
		$this->load->library('email');
		$config = array (
			'mailtype' => 'html',
			'charset'  => 'utf-8',
			'priority' => '1'
		);
		$this->email->initialize($config);
		$this->email->from("snapalsapp@gmail.com", 'Snapals');
		$this->email->to($email);
		$this->email->subject($subject);
		
		$datamail = array();
		$datamail["title"] = $title;
		$datamail["username"] = $username;
		$datamail["content"] = $message;
		
		$message = $this->load->view('email/send-content',$datamail,TRUE);
		//$this->email->message($message);
		//$this->email->send(); 
	}
}
?>