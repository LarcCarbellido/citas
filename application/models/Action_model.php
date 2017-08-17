<?php

class action_model extends CI_Model 
{
	private $table = "user_action";
	
	// Add an action
	function add($from_user_id, $to_user_id, $text, $url = "#", $icon = "")
	{		    
	    $this->db->set(array('from_user_id' 		=> $from_user_id,
                			 'to_user_id'			=> $to_user_id,
                			 'text'					=> $text,
                			 'url'					=> $url,
                			 'icon'					=> $icon))
                ->set('date', 'UTC_TIMESTAMP()', false)
                ->insert($this->table);
        
        return true;
	}
	
	// Get last actions
	function get_last_from_user($user_id, $limit)
	{
		$actions = $this->db->select('a.id, date, a.text, a.url, a.icon')
		                ->from($this->table . ' a')
		                ->where("a.to_user_id", $user_id)
		                ->order_by('a.date', 'desc')
		                ->limit($limit)
		                ->get();
		                
		return $actions;
	}
	
	// Get more actions
	function get_more($from, $limit)
	{
		$actions = $this->db->select('a.id, date, a.text, a.url, a.icon')
		                ->from($this->table . ' a')
		                ->where("a.id <", $from)
		                ->order_by('a.date', 'desc')
		                ->limit($limit)
		                ->get();
		                
		return $actions;
	}
}
?>