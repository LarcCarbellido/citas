<?php

class pm_model extends CI_Model 
{
	function get_last_conversations($user_id, $limit = 100)
	{
		$lstMessages = $this->db->query("SELECT 
										 p.id, 
										 p.last_answer_date, 
										 p.sender_id,
										 p.recipient_id,
										 p.is_read_sender,
										 p.last_answer_user_id,
										 p.is_read_recipient,
										 (SELECT m.read FROM pm_message m WHERE m.conv_id = p.id AND m.user_id != ? ORDER BY m.date DESC LIMIT 1) AS read_status,
										 (SELECT m.content FROM pm_message m WHERE m.conv_id = p.id ORDER BY m.date DESC LIMIT 1) AS last_message,
										 (SELECT u.username FROM user u WHERE (u.id = p.sender_id OR u.id = p.recipient_id) AND u.id != ? LIMIT 1) AS from_username,
										 (SELECT u.id FROM user u WHERE (u.id = p.sender_id OR u.id = p.recipient_id) AND u.id != ? LIMIT 1) AS from_userid,
										 (SELECT ui.gender FROM user_info ui WHERE (ui.user_id = p.sender_id OR ui.user_id = p.recipient_id) AND ui.user_id != ? LIMIT 1) AS from_gender 
										 FROM pm_conv p
										 WHERE p.sender_id = ? OR p.recipient_id = ?
										 ORDER BY p.last_answer_date DESC LIMIT ?", array($user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $limit));
						   
	    return $lstMessages;
	}
	
	// Delete conversations
	function delete_conversations($user_id)
	{
		$this->db->delete('pm_conv', array("sender_id" => $user_id));
		$this->db->delete('pm_conv', array("recipient_id" => $user_id)); 
	}
	
	function get_last_conversation($user_id)
	{
		$query = $this->db->query("SELECT *
								   FROM pm_conv 
								   WHERE sender_id = ? OR recipient_id = ? ORDER BY last_answer_date DESC", array($user_id, $user_id))->result();
		
		if($query == NULL)
			return NULL;
		else
			return $query[0];
	}
	
	function update_conv($conv_id, $data) 
	{
		$this->db->where(array('id' => $conv_id));
        $this->db->set($data);
		$this->db->update("pm_conv");
	}

	// Send a PM
	function new_conv($sender_id, $receiver_id)
	{
	    $this->db->set(array('sender_id' 			=> $sender_id,
                			 'recipient_id'			=> $receiver_id,
                			 'is_read_sender' 		=> 1,
                			 'last_answer_user_id'  => $sender_id))
                ->set('date', 'UTC_TIMESTAMP()', false)
                ->insert('pm_conv');
                
         return $this->db->insert_id();
	}
	
	// Send a PM
	function add_daily_notif($user_id)
	{
	    $this->db->set(array('user_id' 				=> $user_id))
                ->set('date', 'UTC_TIMESTAMP()', false)
                ->insert('pm_email_notif');
                
         return $this->db->insert_id();
	}
	
	// Check daily email notif
	function check_daily_notif($user_id)
	{
		$query = $this->db->query("SELECT id FROM pm_email_notif WHERE user_id = ? AND date >= UTC_TIMESTAMP() - INTERVAL 1 DAY", array($user_id));
		
		if($query->num_rows() == 0)
			return false;
		else
			return true;
	}
	
	function new_message_to_conv($conv_id, $message, $user_id) 
	{
		$this->db->set(array('content' 				=> $message,
                			 'conv_id'				=> $conv_id,
                			 'user_id' 				=> $user_id))
                 ->set('date', 'UTC_TIMESTAMP()', false)
                 ->insert('pm_message');
                 
         $inserted_id = $this->db->insert_id();
                 
         $this->db->where(array('id' => $conv_id));
         $this->db->set('last_answer_date', 'UTC_TIMESTAMP()', false);
		 $this->db->update("pm_conv");
                
         return $inserted_id;	
	}
	
	function get_conv_by_id_user_id($conv_id, $user_id)
	{
		$query = $this->db->query("SELECT pm.* 
								   FROM pm_conv pm WHERE (sender_id = ? OR recipient_id = ?) AND id = ?", array($user_id, $user_id, $conv_id))->result();
		
		if($query == NULL)
			return NULL;
		else
			return $query[0];
	}
	
	function get_conv($user_id_1, $user_id_2)
	{
		$query = $this->db->query("SELECT pm.* 
								   FROM pm_conv pm WHERE (sender_id = ? AND recipient_id = ?) OR (sender_id = ? AND recipient_id = ?)", array($user_id_1, $user_id_2, $user_id_2, $user_id_1))->result();
		
		if($query == NULL)
			return NULL;
		else
			return $query[0];
	}
	
	function get_last_messages_from_conv($conv_id, $last_message_id)
	{
		$lstMessages = $this->db->select('m.id AS mid, m.date, m.content, m.user_id, u.username, p.thumb_url, p.url, ui.gender, p.status AS photostatus')
				                ->from('pm_message m')
				                ->join('user u', 'u.id = m.user_id')
				                ->join('user_info ui', 'ui.user_id = u.id')
				                ->join('photo p', 'p.id = ui.main_photo', 'left')
				                ->where(array('m.conv_id' => (int) $conv_id, 'm.id >' => (int) $last_message_id))
				                ->order_by('m.id', "desc")
				                ->get();
						   
	    return $lstMessages;
	}
	
	function get_messages_from_conv($conv_id, $limit)
	{
		$lstMessages = $this->db->select('m.id, m.date, m.content, m.user_id, u.username, p.thumb_url, p.url, ui.gender, p.status AS photostatus')
				                ->from('pm_message m')
				                ->join('user u', 'u.id = m.user_id')
				                ->join('user_info ui', 'ui.user_id = u.id')
				                ->join('photo p', 'p.id = ui.main_photo', 'left')
				                ->where('m.conv_id', (int) $conv_id)
				                ->order_by('m.id', "desc")
				                ->limit($limit)
				                ->get();
						   
	    return $lstMessages;
	}
	
	function count_conversations($user_id)
	{
		$lstConv =  $this->db->select('id')
			                 ->from("pm_conv")
			                 ->where("sender_id", (int) $user_id)
			                 ->or_where("recipient_id", (int) $user_id)
							 ->get();
						   
	    return $lstConv->num_rows();	
	}
	
	// Set the conversation as read for a user 
	function mark_as_read($user_id, $conv_id)
	{
		$this->db->where(array('user_id' => $user_id, 'conv_id' => $conv_id));
		$this->db->update("pm_message", array("read" => 1));
	}
	
	function count_unread($user_id)
	{
		$query = $this->db->query("SELECT id FROM pm_conv WHERE (sender_id = ? AND is_read_sender = 0) OR (recipient_id = ? AND is_read_recipient = 0)", array($user_id, $user_id));
						   
	    return $query->num_rows();
	}
}
?>