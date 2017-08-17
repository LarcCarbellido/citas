<?php
class site_model extends CI_Model 
{
	function get_random_welcome_message()
	{
		$this->db->select('id, text', false);
		$this->db->from("welcome_message");
		$this->db->order_by("id", "RANDOM");
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		return $query;
	}
	
	// Add a language redirection
	function add_language_redirection($country, $language)
	{
	    $this->db->set(array('country' 		=> $country,
	    					 'language'		=> $language))
                ->insert("language_redirection");
                
        return $this->db->insert_id();
	}
	
	function check_language_redirection_exists_not_id($id, $country)
	{
		$query = $this->db->query("SELECT id FROM language_redirection WHERE country = ? AND id != ?", array($country, $id));
		
		if($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}	
	
	function check_language_id_exists($id)
	{
		$query = $this->db->query("SELECT id FROM language_redirection WHERE id = ?", array($id));
		
		if($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	function check_language_exists($language)
	{
		$query = $this->db->query("SELECT id FROM language_redirection WHERE language = ?", array($language));
		
		if($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Check language redirection exists
	function check_language_redirection_exists($country)
	{
		$query = $this->db->query("SELECT id FROM language_redirection WHERE country = ?", array($country));
		
		if($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
		
	// Count language redirections
	function count_language_redirections()
	{
		$query = $this->db->query("SELECT id FROM language_redirection");
		
		return $query->num_rows();
	}
	
	function count_custom_pages()
	{
		$query =  $this->db->select('id')
	                   ->from("custom_page")
	                   ->where("status", 1)
	                   ->get();
						   
	    return $query->num_rows();
	}
	
	// Get language by country code
	function get_user_language($country)
	{
		$query = $this->db->select('*')
		                  ->from("language_redirection")
		                  ->where(array('country' => $country))
		                  ->get();
		                  
		return $query->row();
	}
	
	// Get a page by its ID
	function get_page($id)
	{
		$query = $this->db->select('id, title, content, icon, user_id, date, status, welcome_enable')
		                  ->from("custom_page")
		                  ->where(array('id' => $id))
		                  ->get();
		                  
		return $query->row();
	}
	
	// Edit a redirection
	function edit_language_redirection($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update("language_redirection", $data);
	}
	
	// Edit a page
	function edit_page($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update("custom_page", $data);
	}
	
	// Create a new user
	function add_page($title, $content, $icon, $user_id, $welcome_enable)
	{		    
	    $this->db->set(array('title' 		=> $title,
	    					 'content'		=> $content,
	    					 'icon'			=> $icon,
							 'user_id'		=> $user_id,
							 'welcome_enable' => $welcome_enable,
	    					 'status'		=> 1))
                ->set('date', 'UTC_TIMESTAMP()', false)
                ->insert("custom_page");
                
        return $this->db->insert_id();
	}
	
	function get_language_redirection($id)
	{
		$query = $this->db->select('*')
		                  ->from("language_redirection")
		                  ->where(array('id' => $id))
		                  ->get();
		                  
		return $query->row();
	}
	
	function update_settings($data) 
	{
		$this->db->update("admin", $data);
	}	
	
	function delete_page($page_id)
	{
		$this->db->delete('custom_page', array("id" => $page_id)); 
	}
	
	function delete_redirection($id)
	{
		$this->db->delete('language_redirection', array("id" => $id)); 
	}
	
	function get_pages()
	{
		$pages = $this->db->query("SELECT id, title, content, icon, user_id, date, status, welcome_enable
								   FROM custom_page
								   ORDER BY date DESC");
		
		return $pages;
	}
	
	function get_welcome_pages()
	{
		$pages = $this->db->query("SELECT id, title, content, icon, user_id, date, status
								   FROM custom_page
								   WHERE welcome_enable = 1
								   ORDER BY date DESC");
		
		return $pages;
	}
	
	function get_language_redirections()
	{
		$this->db->select("*");
		$this->db->from("language_redirection");
		
		$query = $this->db->get();
		
		return $query;
	}
	
	function get_website_settings()
	{
		$this->db->select("*");
		$this->db->from("admin");
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		return $query;
	}

}