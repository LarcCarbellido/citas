<?php

class photo_model extends CI_Model 
{
	private $table = "photo";

	// Create a new user
	function add($url, $thumb_url, $user_id)
	{		    
	    $this->db->set(array('url' 		=> $url,
	    					 'thumb_url' => $thumb_url,
                			 'user_id'	=> $user_id,
                			 'status'			=> 1))
                ->set('date', 'UTC_TIMESTAMP()', false)
                ->insert($this->table);
                
        return $this->db->insert_id();
	}
	
	function count_user_photos($user_id)
	{
		$query = $this->db->query("SELECT id FROM photo WHERE user_id = ? AND status = 1", array($user_id));
		
		return $query->num_rows();
	}
	
	function update_views($id) 
	{
		$this->db->where('id', $id);
		$this->db->set('views', 'views+1', FALSE);
		$this->db->update('photo');
	}
	
	function delete_comment($com_id)
	{
		$this->db->delete("photo_comment", array("id" => $com_id));
	}
	
	function delete($pic_id)
	{
		// Delete the picture itself (Disable it)
		$this->db->where('id', $pic_id);
		$this->db->update($this->table, array("status" => 0));
	}
	
	function update($pic_id, $data)
	{
		$this->db->where('id', $pic_id);
		$this->db->update($this->table, $data);
	}
	
	// Get the previous picture id from another id (user)
	function get_next_user_picture_from_id($id, $user_id)
	{
		$query = $this->db->query("SELECT id
								   FROM photo
								   WHERE id > ? 
								   AND status = 1
								   AND user_id = ?
								   ORDER BY id
								   LIMIT 1", array($id, $user_id));
								   								   
		if($query->num_rows() == 0)
		{
			return $this->get_first_user_picture($user_id);
		} else {
			return $query->result();
		}
	}
	
	// Get first image (with the user type)
	function get_first_user_picture($user_id)
	{
		$query = $this->db->query("SELECT id
								   FROM photo
								   WHERE status = 1
								   AND user_id = ?
								   ORDER BY id
								   LIMIT 1", array($user_id));
								   
		return $query->result();
	}
	
	// Get last image (with the user type)
	function get_last_user_picture($user_id)
	{
		$query = $this->db->query("SELECT id
								   FROM photo
								   WHERE status = 1
								   AND user_id = ?
								   ORDER BY id
								   DESC LIMIT 1", array($user_id));
								   
		return $query->result();
	}
	
	// Get the previous picture id from another id
	function get_prev_user_picture_from_id($id, $user_id)
	{
		$query = $this->db->query("SELECT id
								   FROM photo
								   WHERE id < ? 
								   AND status = 1
								   AND user_id = ?
								   ORDER BY id
								   DESC LIMIT 1", array($id, $user_id));
								   
		if($query->num_rows() == 0)
		{
			return $this->get_last_user_picture($user_id);
		} else {
			return $query->result();
		}
	}
	
	function user_owns_photo_com($user_id, $com_id)
	{
		$query = $this->db->query("SELECT pc.id 
								   FROM photo_comment pc, photo p
								   WHERE pc.id = ? 
								   AND p.id = pc.photo_id
								   AND p.user_id = ?", array($com_id, $user_id));
		
		if($query->num_rows() == 0)
			return false;
		else
			return true; 
	}
	
	// Check if the user owns this comment
	function user_owns_comment($user_id, $com_id)
	{
		$query = $this->db->query("SELECT id FROM photo_comment WHERE id = ? AND user_id = ?", array($com_id, $user_id));
		
		if($query->num_rows() == 0)
			return false;
		else
			return true; 
	}
	
	// Check if the user owns this photo
	function user_owns_photo($user_id, $photo_id)
	{
		$query = $this->db->query("SELECT id FROM photo WHERE id = ? AND user_id = ?", array($photo_id, $user_id));
		
		if($query->num_rows() == 0)
			return false;
		else
			return true; 
	}
	
	// Add a comment
	function add_comment($pic_id, $user_id, $text)
	{		    
		$this->load->model('photo_model', 'picManager');
		
		$pic_info = $this->picManager->get($pic_id);
		
		if($pic_info == null)
		{
			return;
		} else {
		
			$this->db->where('id', $pic_id);
			$this->db->set('comments', 'comments+1', FALSE);
			$this->db->update('photo');
		

			$this->db->set(array('user_id'		=> (int) $user_id,
								 'photo_id'	=> (int) $pic_id,
								 'content'		=> $text,
			        			 'status'		=> 1))
			        ->set('date', "UTC_TIMESTAMP()", false)
			        ->insert("photo_comment");
				    
			        
			return $this->db->insert_id();
	     }

	}
	
	// Get the comments by the picture ID
	function get_comments($pic_id, $user_id = 0)
	{
	    
	    $lstComs = $this->db->query("SELECT c.id, c.date AS com_date, c.content, u.id AS uid, u.username, p.thumb_url, p.url, ui.gender, p.status AS photostatus
									 FROM photo_comment c, user u, user_info ui
									 LEFT JOIN photo p ON p.id = ui.main_photo
									 WHERE c.photo_id = ?
									 AND u.id = c.user_id
									 AND ui.user_id = u.id
									 ORDER BY com_date", array($pic_id));
						   
	    return $lstComs;
	}
	
	// Returns picture by its id
	function get($id, $user_id = 0)
	{
		$id = (int) $id;
		
		$query = $this->db->query("SELECT p.id AS pid, p.thumb_url, p.date, p.url, p.text, p.views, p.votes, p.comments, u.id AS uid, ui.gender, u.username, u.is_fake, IF(v.id IS NOT NULL, 1, 0) as st_vote
								   FROM photo p								  
								   JOIN user u ON u.id = p.user_id
								   JOIN user_info ui ON ui.user_id = u.id
								   LEFT JOIN photo_vote v ON v.photo_id = p.id AND v.user_id = ?
								   WHERE p.id = ? 
								   AND p.status = 1", array($user_id, $id));
		return $query;
	}
	
	function get_photo_id_by_com_id($com_id)
	{
		$query = $this->db->query("SELECT photo_id FROM photo_comment WHERE id = ?", array($com_id));
		
		return $query;
	}
	
	// [Action] Vote for a photo 
	// 1) Check if the photo has already been voted
	// 2) Add the vote
	function vote_for_photo_id($id, $user_id)
	{
		if($this->has_already_voted($id, $user_id))
		{	
			$this->decrease_vote_pic($id);
			
			$this->db->delete('photo_vote', array('photo_id' => $id, 'user_id' => $user_id)); 
			return false;
		} else {
			$this->increase_vote_pic($id);
		
			$this->db->set(
							array
								 (
									 'user_id' 		=> (int) $user_id,
		                			 'photo_id'		=> (int) $id,
		                			 'ip'			=> $_SERVER["REMOTE_ADDR"]
	                			 )
                		   )
	                 ->set('date', 'UTC_TIMESTAMP()', false)
	                 ->insert("photo_vote");
		
			return true;
		}
	}
	
	// [Verif] Check if the user has already vote for the photo
	function has_already_voted($id, $user_id)
	{
		$id = (int) $id;
		$user_id = (int) $user_id;
	
		$query = $this->db->query("SELECT user_id FROM photo_vote WHERE user_id = ? AND photo_id = ?", array($user_id, $id));
		
		if($query->num_rows() == 0)
			return false;
		else
			return true;
	}
		
	// Increase the number of votes on a picture
	function decrease_photo_comment($id) 
	{
		$this->db->where('id', $id);
		$this->db->set('comments', 'comments-1', FALSE);
		$this->db->update('photo');
	}
	
	// Increase the number of votes on a picture
	function increase_vote_pic($id) 
	{
		$this->db->where('id', $id);
		$this->db->set('votes', 'votes+1', FALSE);
		$this->db->update('photo');
	}
	
	// Decrease the number of votes on a picture
	function decrease_vote_pic($id) 
	{
		$this->db->where('id', $id);
		$this->db->set('votes', 'votes-1', FALSE);
		$this->db->update('photo');
	}
}
?>