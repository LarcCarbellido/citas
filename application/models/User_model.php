<?php
class user_model extends CI_Model 
{
	private $table = "user";
	
	// Verifiy if the FB user id exists
	function check_facebook_user_exists($fb_user_id)
	{
		$query = $this->db->query("SELECT fb_id FROM user WHERE fb_id = ?", array($fb_user_id));
		
		if($query->num_rows() == 0)
			return false;
		else
			return true; 
	}
	
	// Get the user by his FB ID
	function get_facebook_user($fb_id) 
	{
		$this->db->select('u.id as uid, u.fb_id, u.banned, u.username, is_fake, u.register_date, u.email, u.last_login_date, u.last_activity_date, IF(u.last_activity_date >= UTC_TIMESTAMP() - INTERVAL ' . ONLINE_DELAY . ' MINUTE, 1, 0) as is_online, u.first_step_form, u.allow_social_featuring, ui.gender, ui.browse_invisibly, u.rank, ui.birthday, ui.about, ui.main_photo, ui.country, ui.city, ui.interested_in, p.url, p.thumb_url, p.status AS photostatus', false);
		$this->db->from("user u");
		$this->db->join('user_info ui', 'u.id = ui.user_id', 'left');
		$this->db->join('photo p', 'p.id = ui.main_photo', 'left');
		$this->db->where('u.fb_id', intval($fb_id));
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		return $query;
	}
	
	// Create social account
	function create_social_account($social_id, $name, $email, $rank = 0)
	{
	    $this->db->set(array('username' 		=> $name,
		        			 'email'			=> $email,
		        			 'status'			=> 1,
		        			 'fb_id'			=> $social_id,
		        			 'rank'				=> $rank,
		        			 'is_fake'			=> 0))
        ->set('register_date', 'NOW()', false)
        ->set('last_activity_date', 'NOW()', false)
        ->insert("user");
                
        return $this->db->insert_id();
	}
	
	// Create a new user
	function create($username, $password, $email, $status, $encrypt_id, $fb_id = "", $referer = "", $rank = 0, $is_fake = 0)
	{		    
	    $this->db->set(array('username' 		=> $username,
                			 'password'			=> sha1($password),
                			 'email'			=> $email,
                			 'status'			=> 1,
                			 'encrypt_id'		=> $encrypt_id,
                			 'fb_id'			=> $fb_id,
                			 'referer'			=> $referer,
                			 'rank'				=> $rank,
                			 'is_fake'			=> $is_fake))
                ->set('register_date', 'UTC_TIMESTAMP()', false)
                ->set('last_activity_date', 'UTC_TIMESTAMP()', false)
                ->insert($this->table);
                
        return $this->db->insert_id();
	}
	
	// Report a user
	function report($from_user_id, $profile_id, $type = 0)
	{
		$this->db->set(array('from_user_id' => $from_user_id, "profile_id" => $profile_id, "type" => $type))
                 ->set('date', 'UTC_TIMESTAMP()', false)
                 ->insert("report");
                
        return $this->db->insert_id();
	}
	
	// Verify if the user exists by its ID
	function check_user_exists($user_id)
	{
		$query = $this->db->query("SELECT id FROM user WHERE id = " . intval($user_id));
		
		if($query->num_rows() == 0)
			return false;
		else
			return true; 
	}
	
	// Update the profile visit date
	function update_profile_visit($profile_id, $user_id)
	{
		$this->db->where(array("profile_id" => $profile_id, "user_id" => $user_id));
		$this->db->set('viewed', '0');
		$this->db->set('date', 'UTC_TIMESTAMP()', false);
		$this->db->update("user_profile_visit");
	}
	
	// Update the last activity date
	function update_last_activity($user_id)
	{
		$this->db->where(array("id" => $user_id));
		$this->db->set('last_activity_date', 'UTC_TIMESTAMP()', false);
		$this->db->update("user");
	}
	
	// Set user_friends as status = 1 (friends)
	function update_request_to_friend($user_id, $to_user_id)
	{
		$this->db->where(array("from_user_id" => $user_id, "to_user_id" => $to_user_id));
		$this->db->set('status', 1);
		$this->db->update("user_friend");
	}
	
	// Set friends as seen
	function update_friends_to_seen($user_id)
	{
		$this->db->where(array("user_id" => $user_id));
		$this->db->set('seen', 1);
		$this->db->update("friend_notif");
	}
	
	// Set user_friends as seen
	function update_requests_to_seen($user_id)
	{
		$this->db->where(array("to_user_id" => $user_id));
		$this->db->set('seen', 1);
		$this->db->update("user_friend");
	}
	
	// Update profile visits to viewed
	function update_profile_visits_to_viewed($user_id) 
	{
		$this->db->where(array("profile_id" => $user_id));
		$this->db->set('viewed', '1');
		$this->db->update("user_profile_visit");
	}
	
	function count_new_friends($user_id)
	{
		$query = $this->db->query("SELECT id FROM friend_notif WHERE user_id = ? AND seen = 0", array($user_id));
		
		return $query->num_rows();
	}
	
	function count_new_loves($user_id)
	{
		$query = $this->db->query("SELECT id FROM user_love WHERE to_user_id = ? AND viewed = 0", array($user_id));
		
		return $query->num_rows();
	}
	
	function count_new_profile_visits($user_id)
	{
		$query = $this->db->query("SELECT id FROM user_profile_visit WHERE profile_id = ? AND viewed = 0", array($user_id));
		
		return $query->num_rows();
	}
	
	// Check if users are already friends
	function check_already_friend($user_id_1, $user_id_2)
	{
		$query = $this->db->query("SELECT from_user_id, to_user_id FROM user_friend WHERE ((from_user_id = ? AND to_user_id = ?) OR (from_user_id = ? AND to_user_id = ?)) AND status = 1", array($user_id_1, $user_id_2, $user_id_2, $user_id_1));
		
		if($query->num_rows() == 0)
			return false;
		else
			return true;
	}
	
	// Add a profile visit
	function add_profile_visit($profile_id, $user_id) {
		$this->db->set(
						array(
								'profile_id'			=> $profile_id,
								'user_id'				=> $user_id
							 )
					  )
				 ->set('date', 'UTC_TIMESTAMP()', false)
                 ->insert("user_profile_visit");
                
        return $this->db->insert_id();
	}
	
	// Verify if the user has already visited this profile
	function has_already_visited($profile_id, $user_id)
	{
		$query = $this->db->query("SELECT id FROM user_profile_visit WHERE user_id = ? AND profile_id = ?", array($user_id, $profile_id));
		
		if($query->num_rows() == 0)
			return false;
		else
			return true; 
	}
	
	function send_request($profile_id, $user_id)
	{
		$this->db->set(
				array(
						'to_user_id'			=> $user_id,
						'from_user_id'			=> $profile_id,
						'seen'					=> 0,
						'status'				=> 0
					 )
			  )
		 ->set('date', 'UTC_TIMESTAMP()', false)
         ->insert("user_friend");
                
        return $this->db->insert_id();
	}
	
	function has_pending_request($profile_id, $user_id)
	{
		$query = $this->db->query("SELECT id FROM user_friend WHERE to_user_id = ? AND from_user_id = ? AND status = 0", array($profile_id, $user_id));
		
		if($query->num_rows() == 0)
			return false;
		else
			return true; 
	}
	
	function has_already_requested($profile_id, $user_id)
	{
		$query = $this->db->query("SELECT id FROM user_friend WHERE from_user_id = ? AND to_user_id = ? AND status = 0", array($profile_id, $user_id));
		
		if($query->num_rows() == 0)
			return false;
		else
			return true; 
	}
	
	// Delete user
	function delete_user($user_id)
	{
		$this->db->delete('user', array("id" => $user_id)); 
	}
	
	// Delete user_infos
	function delete_user_info($user_id)
	{
		$this->db->delete('user_info', array("user_id" => $user_id)); 
	}
	
	// Get user's photos
	function get_photos($user_id, $limit)
	{
		$photos = $this->db->query("SELECT id, url, thumb_url, date, votes, comments
									FROM photo
									WHERE user_id = ?
									AND status = 1
									ORDER BY date LIMIT ?", array($user_id, $limit));
		
		return $photos;
	}
	
	function get_user_by_username_like($username)
	{
		$user = $this->db->query("SELECT id
								  FROM user
								  WHERE username LIKE ?
								  AND status = 1
								  LIMIT 1", array("%".$username."%"));
		
		return $user;
	}
	
	function count_new_requests($user_id)
	{
		$query = $this->db->query("SELECT from_user_id, to_user_id FROM user_friend WHERE to_user_id = ? AND status = 0 AND seen = 0", array($user_id));
		
		return $query->num_rows();
	}
	
	// Block a user
	function block($from_user_id, $profile_id)
	{
		$this->db->set(array('from_user_id' => $from_user_id, "to_user_id" => $profile_id))
                 ->set('date', 'UTC_TIMESTAMP()', false)
                 ->insert("user_block");
                
        return $this->db->insert_id();
	}
	
		
	// Unblock user
	function unblock($from_user_id, $to_user_id)
	{
		$this->db->delete('user_block', array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id)); 
	}
	
	
	// Check blocked
	function check_blocked($from_user_id, $to_user_id) 
	{
		$query = $this->db->query("SELECT id FROM user_block WHERE from_user_id = ? AND to_user_id = ?", array($from_user_id, $to_user_id));
		
		if($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	
	// Create user info
	function create_info($user_id, $gender, $interested_in)
	{		    
	    $this->db->set(array('user_id' 		=> $user_id, 'gender' => $gender, 'interested_in' => $interested_in))
                 ->insert("user_info");
                
        return $this->db->insert_id();
	}
	
	// Get the user by his email
	function get_by_email($email) 
	{
		$this->db->select('u.id as uid, u.username');
		$this->db->from("user u");
		$this->db->where('u.email', $email);
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		return $query;
	}
	
	function check_password_recovery($encrypt_id, $user_id)
	{
		$query = $this->db->query("SELECT id FROM user_password_recovery WHERE encrypt_id = ? AND user_id = ?", array($encrypt_id, $user_id));
		
		if($query->num_rows() == 0)
			return false;
		else
			return true;
	}
	
	// Remove password recovery record
	function remove_password_recovery($encrypt_id, $user_id)
	{
		$this->db->delete('user_password_recovery', array("encrypt_id" => $encrypt_id, "user_id" => $user_id)); 
	}
	
	
	// Add a password recovery entry
	function add_password_recovery($encrypt_id, $user_id)
	{
		$this->db->set(array('encrypt_id' => $encrypt_id, "user_id" => $user_id))
                 ->set('date', 'UTC_TIMESTAMP()', false)
                 ->insert("user_password_recovery");
                
        return $this->db->insert_id();
	}
	
	// Login
	function login($username, $password)
	{
		$query = $this->db->query("SELECT user.id, username, password, user.status, user.email, banned, rank, last_login_date, first_step_form, p.url, p.thumb_url, p.status AS photostatus, ui.gender 
								   FROM user
								   JOIN user_info ui ON user.id = ui.user_id
								   LEFT JOIN photo p ON p.id = ui.main_photo 
								   WHERE (username = ? OR email = ?)
								   AND password = ?
								   LIMIT 1", array($username, $username, sha1($password)));

						
		if($query->num_rows() == 1)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}
	
	// Update the last connection date
	function update_login($id)
	{
		$this->db->where('id', $id);
		$this->db->set('last_login_date', 'UTC_TIMESTAMP()', false);
		$this->db->update($this->table);
	}
	
	function insert_user_extra_field($user_id, $extra_field, $extra_val)
	{
		$this->db->set(array('user_id' 		=> $user_id,
        			  		 'attr_name'	=> $extra_field,
        			  		 'attr_val'		=> $extra_val))
		        ->insert("user_custom_field"); 
                
        return $this->db->insert_id();
	}
	
	function update_user_extra_field($user_id, $extra_field, $extra_val)
	{
		$this->db->where(array("user_id" => $user_id, "attr_name" => $extra_field));
		$this->db->set('attr_val', $extra_val);
		$this->db->update("user_custom_field");
	}
	
	function get_user_extra($user_id)
	{
		$this->db->select('attr_name, attr_val');
		$this->db->from("user_custom_field");
		$this->db->where('user_id', intval($user_id));
		
		$query = $this->db->get();
		
		return $query;
	}
	
	function check_extra_field_exists($user_id, $extra_field)
	{
		$query = $this->db->query("SELECT user_id, attr_name, attr_val FROM user_custom_field WHERE user_id = ? AND attr_name = ?", array($user_id, $extra_field));

						
		if($query->num_rows() >= 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	// Get the user by his ID
	function get($profile_id, $user_id = 0) 
	{
		$this->db->select('u.id as uid, u.username, is_fake, u.register_date, u.email, u.last_login_date, u.last_activity_date, IF(u.last_activity_date >= UTC_TIMESTAMP() - INTERVAL ' . ONLINE_DELAY . ' MINUTE, 1, 0) as is_online, u.first_step_form, u.allow_social_featuring, ui.gender, ui.browse_invisibly, u.rank, ui.birthday, ui.about, ui.main_photo, ui.country, ui.city, ui.interested_in, p.url, p.thumb_url, p.status AS photostatus, IF(ub.id IS NOT NULL, 1, 0) as st_blocked', false);
		$this->db->from("user u");
		$this->db->join('user_info ui', 'u.id = ui.user_id');
		$this->db->join('photo p', 'p.id = ui.main_photo', 'left');
		$this->db->join('user_block ub', 'ub.to_user_id = u.id AND ub.from_user_id = ' . $user_id, 'left');
		$this->db->where('u.id', intval($profile_id));
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		return $query;
	}
	
	// Get the user by his ID
	function get_complete_user_fb($profile_id, $user_id = 0) 
	{
		$this->db->select('u.id as uid, u.username, is_fake, u.register_date, u.email, u.last_login_date, u.last_activity_date, IF(u.last_activity_date >= UTC_TIMESTAMP() - INTERVAL ' . ONLINE_DELAY . ' MINUTE, 1, 0) as is_online, u.first_step_form, u.allow_social_featuring, ui.gender, ui.browse_invisibly, u.rank, ui.birthday, ui.about, ui.main_photo, ui.country, ui.city, ui.interested_in, p.url, p.thumb_url, p.status AS photostatus, IF(ub.id IS NOT NULL, 1, 0) as st_blocked', false);
		$this->db->from("user u");
		$this->db->join('user_info ui', 'u.id = ui.user_id', 'left');
		$this->db->join('photo p', 'p.id = ui.main_photo', 'left');
		$this->db->join('user_block ub', 'ub.to_user_id = u.id AND ub.from_user_id = ' . $user_id, 'left');
		$this->db->where('u.id', intval($profile_id));
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		return $query;
	}
	
	function get_user_language($user_id)
	{
		$this->db->select("language_id, language");
		$this->db->from("user_info");
		$this->db->join("language_redirection", "user_info.language_id = language_redirection.id");
		$this->db->where('user_id', $user_id);
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		return $query;
	}
	
	function update($user_id, $data) 
	{
		$this->db->where('id', $user_id);
		$this->db->update($this->table, $data);
	}
	
	function update_info($user_id, $data) 
	{
		$this->db->where('user_id', $user_id);
		$this->db->update("user_info", $data);
	}
	
	function get_captcha() 
	{
		$this->db->select('*');
		$this->db->from("captcha");
		$this->db->order_by("id", "random");
		$this->db->limit(1);	
		
		$query = $this->db->get();
		
		return $query->result();
	}
	
	function get_captcha_answer($id) 
	{
		$this->db->select('*');
		$this->db->from("captcha");
		$this->db->where("id", $id);
		$this->db->limit(1);	
		
		$query = $this->db->get()->result();
		
		return $query[0]->answer;
	}
	
	function is_username_taken($username)
	{
		$query = $this->db->query("SELECT id FROM " . $this->table . " WHERE username = ?", array($username));
		
		if($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	function is_username_taken_user($username, $user_id)
	{
		$query = $this->db->query("SELECT id FROM " . $this->table . " WHERE username = ? AND id != ?", array($username, $user_id));
		
		if($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	function is_email_taken_user($email, $user_id)
	{
		$query = $this->db->query("SELECT id FROM " . $this->table . " WHERE email = ? AND id != ?", array($email, $user_id));
		
		if($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	function is_email_taken($email)
	{
		$query = $this->db->query("SELECT id FROM " . $this->table . " WHERE email = ?", array($email));
		
		if($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	
	// Get profile_visit
	function get_profile_visits($user_id, $limit)
	{
		$visits = $this->db->query("SELECT DISTINCT(upv.user_id), u.id AS uid, u.username, is_fake, ui.gender, ui.country, ui.city, ui.birthday, p.thumb_url, p.status as photostatus, p.url, ui.gender, p.status AS photostatus, IF(u.last_activity_date >= UTC_TIMESTAMP() - INTERVAL " . ONLINE_DELAY . " MINUTE, 1, 0) as is_online,  IF(ul.id IS NOT NULL, 1, 0) as st_love
									FROM user u
									JOIN user_profile_visit upv ON u.id = upv.user_id
									JOIN user_info ui ON ui.user_id = u.id
									LEFT JOIN photo p ON p.id = ui.main_photo
									LEFT JOIN user_love ul ON ul.to_user_id = u.id AND ul.from_user_id = ?
									WHERE upv.profile_id = ?
									AND first_step_form = 1
									ORDER BY upv.date DESC LIMIT ?", array($user_id, $user_id, $limit));
		
		return $visits;
	}
	
	// Update loves to viewed
	function update_loves_to_viewed($user_id) 
	{
		$this->db->where(array("to_user_id" => $user_id));
		$this->db->set('viewed', '1');
		$this->db->update("user_love");
	}
	
	function get_lasts_users_who_loved($user_id, $limit)
	{
		$users = $this->db->query("SELECT ul.date as lovedate, u.id AS uid, u.username, is_fake, p.url, p.thumb_url, p.status AS photostatus, ui.gender, ui.country, ui.city, ui.birthday, IF(u.last_activity_date >= UTC_TIMESTAMP() - INTERVAL " . ONLINE_DELAY . " MINUTE, 1, 0) as is_online, DATE_FORMAT(UTC_TIMESTAMP(), '%Y') - DATE_FORMAT(birthday, '%Y') - (DATE_FORMAT(UTC_TIMESTAMP(), '00-%m-%d') < DATE_FORMAT(birthday, '00-%m-%d')) AS age, IF(ul2.id IS NOT NULL, 1, 0) as st_love
								   FROM user_love ul
								   JOIN user u ON u.id = ul.from_user_id
								   JOIN user_info ui ON ui.user_id = u.id 
								   LEFT JOIN photo p ON p.id = ui.main_photo 
								   LEFT JOIN user_love ul2 ON ul2.to_user_id = u.id AND ul2.from_user_id = ?
								   WHERE u.first_step_form = 1
								   AND u.status = 1 
								   AND ul.to_user_id = ?
								   ORDER BY ul.date DESC LIMIT ?", array($user_id, $user_id, $limit));
		
		return $users;
	}
	
	// Insert a friend notif
	function add_friend_notif($user_id)
	{
		 $this->db->set(array('user_id' 		=> $user_id,
                			  'seen'			=> 0))
                ->set('date', 'UTC_TIMESTAMP()', false)
                ->insert("friend_notif"); 
                
        return $this->db->insert_id();
	}
	
	// Activate an in-app purchase
	function activate_purchase($user_id, $purchase_name)
	{
		 $this->db->set(array('user_id' 		=> $user_id,
                			  'purchase_name'	=> $purchase_name,
                			  'status'			=> 1))
                ->set('date', 'UTC_TIMESTAMP()', false)
                ->insert("user_purchase"); 
                
        return $this->db->insert_id();
	}
	
	function has_purchased_product($user_id, $purchase_name)
	{
		$query = $this->db->query("SELECT id FROM user_purchase WHERE user_id = ? AND purchase_name = ? AND status = 1", array($user_id, $purchase_name));
		
		if($query->num_rows() == 0)
			return false;
		else
			return true;
	}
	
	function record_last_received_requests_count($user_id)
	{
		$query = $this->db->query("SELECT id FROM user_friend WHERE to_user_id = ? AND status = 0", array($user_id));
		
		return $query->num_rows();
	}
	
	function record_last_friends_count($user_id)
	{
		$query = $this->db->query("SELECT id FROM user_friend WHERE (to_user_id = ? OR from_user_id = ?) AND status = 1", array($user_id, $user_id));
		
		return $query->num_rows();
	}
	
	function get_last_friends($limit, $page, $user_id = 0)
	{
		$users = $this->db->query("SELECT DISTINCT username, user.id as uid, email, ui.gender, ui.country, ui.city, ui.birthday, IF(last_activity_date >= UTC_TIMESTAMP() - INTERVAL " . ONLINE_DELAY . " MINUTE, 1, 0) as is_online, p.thumb_url, IF(ul.id IS NOT NULL, 1, 0) as st_love, DATE_FORMAT(UTC_TIMESTAMP(), '%Y') - DATE_FORMAT(birthday, '%Y') - (DATE_FORMAT(UTC_TIMESTAMP(), '00-%m-%d') < DATE_FORMAT(birthday, '00-%m-%d')) AS age
									FROM user_friend pk
									RIGHT JOIN user ON
									(user.`id` = pk.`to_user_id`
									OR
									user.`id` = pk.`from_user_id`)
									AND
									user.`id` != ?
									AND
									pk.`status` = '1'
									JOIN user_info ui ON user.`id` = ui.`user_id`
									LEFT JOIN photo p ON ui.`main_photo` = p.`id`
									LEFT JOIN user_love ul ON ul.`to_user_id` = user.`id` AND ul.`from_user_id` = ?
									WHERE
									(pk.`to_user_id` = ?
									OR
									pk.`from_user_id` = ?)
									GROUP BY user.id
									ORDER BY pk.id 
									DESC LIMIT $page, $limit", array($user_id, $user_id, $user_id, $user_id));
								   
		
		return $users;
	}
	
	function get_last_received_requests($limit, $page, $user_id = 0)
	{
		$users = $this->db->query("SELECT u.id AS uid, u.username, is_fake, p.url, p.thumb_url, p.status AS photostatus, ui.gender, ui.country, ui.city, ui.birthday, IF(u.last_activity_date >= UTC_TIMESTAMP() - INTERVAL " . ONLINE_DELAY . " MINUTE, 1, 0) as is_online,  IF(ul.id IS NOT NULL, 1, 0) as st_love
								   FROM user_friend pk
								   JOIN user u ON u.id = pk.from_user_id 
								   JOIN user_info ui ON ui.user_id = u.id 
								   LEFT JOIN photo p ON p.id = ui.main_photo 
								   LEFT JOIN user_love ul ON ul.to_user_id = u.id AND ul.from_user_id = ?
								   WHERE first_step_form = 1
								   AND u.status = 1
								   AND pk.status = 0
								   AND pk.to_user_id = ?
								   ORDER BY u.register_date DESC LIMIT $page,$limit", array($user_id, $user_id));
								   
		
		return $users;
	}
	
	function get_last_registered_users_page_admin($limit, $page, $user_id = 0)
	{
		$users = $this->db->query("SELECT u.id AS uid, u.username, is_fake, p.url, p.thumb_url, p.status AS photostatus, ui.gender, ui.country, ui.city, ui.birthday, IF(u.last_activity_date >= UTC_TIMESTAMP() - INTERVAL " . ONLINE_DELAY . " MINUTE, 1, 0) as is_online,  IF(ul.id IS NOT NULL, 1, 0) as st_love
								   FROM user u 
								   JOIN user_info ui ON ui.user_id = u.id 
								   LEFT JOIN photo p ON p.id = ui.main_photo 
								   LEFT JOIN user_love ul ON ul.to_user_id = u.id AND ul.from_user_id = ?
								   WHERE first_step_form = 1
								   AND u.status = 1
								   ORDER BY u.register_date DESC LIMIT $page,$limit", array($user_id));
								   
		
		return $users;
	}
	
	// Get last registered users with pagination support
	function get_last_registered_users_page($limit, $page, $user_id = 0)
	{
		$users = $this->db->query("SELECT u.id AS uid, u.username, is_fake, p.url, p.thumb_url, p.status AS photostatus, ui.gender, ui.country, ui.city, ui.birthday, IF(u.last_activity_date >= UTC_TIMESTAMP() - INTERVAL " . ONLINE_DELAY . " MINUTE, 1, 0) as is_online,  IF(ul.id IS NOT NULL, 1, 0) as st_love
								   FROM user u 
								   JOIN user_info ui ON ui.user_id = u.id 
								   LEFT JOIN photo p ON p.id = ui.main_photo 
								   LEFT JOIN user_love ul ON ul.to_user_id = u.id AND ul.from_user_id = ?
								   WHERE first_step_form = 1
								   AND u.status = 1
								   AND u.id NOT IN (SELECT user_id FROM user_featured WHERE end_date >= DATE(UTC_TIMESTAMP()))
								   ORDER BY u.register_date DESC LIMIT $page,$limit", array($user_id));
								   
		
		return $users;
	}
	
	function get_last_reported_photos_page($limit, $page, $user_id = 0)
	{
		$users = $this->db->query("SELECT r.id AS rid, p.url, p.id AS pid, p.thumb_url, p.status AS photostatus, u.id AS uid, u.username
								   FROM photo p
								   JOIN report r ON p.id = r.profile_id
								   JOIN user u ON u.id = p.user_id
								   WHERE r.status = 0
								   AND r.type = 1
								   ORDER BY r.date DESC LIMIT $page,$limit");
								   
		
		return $users;	
	}
	
	function get_last_reported_users_page($limit, $page, $user_id = 0)
	{
		$users = $this->db->query("SELECT r.id AS rid, u.id AS uid, u.username, is_fake, p.url, p.thumb_url, p.status AS photostatus, ui.gender, ui.country, ui.city, ui.birthday, IF(u.last_activity_date >= UTC_TIMESTAMP() - INTERVAL " . ONLINE_DELAY . " MINUTE, 1, 0) as is_online,  IF(ul.id IS NOT NULL, 1, 0) as st_love
								   FROM user u 
								   JOIN user_info ui ON ui.user_id = u.id 
								   LEFT JOIN photo p ON p.id = ui.main_photo 
								   JOIN report r ON u.id = r.profile_id
								   LEFT JOIN user_love ul ON ul.to_user_id = u.id AND ul.from_user_id = ?
								   WHERE first_step_form = 1
								   AND u.status = 1
								   AND r.status = 0
								   AND r.type = 0
								   ORDER BY r.date DESC LIMIT $page,$limit", array($user_id));
								   
		
		return $users;
	}
	
	function record_count_by_settings($age_from, $age_to, $gender, $country, $city)
	{
		$array_params = array();
		
		$query =  "SELECT COUNT(*) AS cpt
				   FROM user u 
				   JOIN user_info ui ON ui.user_id = u.id 
				   WHERE first_step_form = 1 ";
				   
		$query.= "AND ui.main_photo != 0 ";
		$query.= "AND TIMESTAMPDIFF(YEAR, birthday, CURDATE()) >= ? AND TIMESTAMPDIFF(YEAR, birthday, CURDATE()) <= ? ";
		
		
		array_push($array_params, $age_from, $age_to);
		
		if($gender == 2) {
			$query .= "AND (gender = 0 OR gender = 1) ";
		} else if($gender == 0) {
			$query .= "AND gender = 0 ";
		} else {
			$query .= "AND gender = 1 ";
		}
		
		if($country != "0") {
			$query .= "AND country = ? ";
			array_push($array_params, $country);
		}
		
		if($city != "") {
			$query .= "AND city LIKE ? ";
			array_push($array_params, "%".$city."%");
		}
		
		$query.= "AND u.id NOT IN (SELECT user_id FROM user_featured WHERE end_date >= DATE(UTC_TIMESTAMP())) ORDER BY register_date DESC";
				   
		$users = $this->db->query($query, $array_params)->result_array();
		
		$users_return = $users[0]["cpt"];
		
		return $users_return;
	}
	
	// Get last registered users with pagination support & params
	function get_last_registered_users_page_with_param($limit, $page, $age_from, $age_to, $gender, $country, $city, $sort_by = "register_date", $user_id = 0)
	{
		$array_params = array();
		
		$query =  "SELECT u.id AS uid, u.username, is_fake, p.url, p.thumb_url, p.status AS photostatus, ui.gender, ui.country, ui.city, ui.birthday, IF(u.last_activity_date >= UTC_TIMESTAMP() - INTERVAL " . ONLINE_DELAY . " MINUTE, 1, 0) as is_online,  IF(ul.id IS NOT NULL, 1, 0) as st_love
				   FROM user u 
				   JOIN user_info ui ON ui.user_id = u.id 
				   LEFT JOIN photo p ON p.id = ui.main_photo 
				   LEFT JOIN user_love ul ON ul.to_user_id = u.id AND ul.from_user_id = ? 
				   WHERE first_step_form = 1 ";
				   
		$query.= "AND u.status = 1 ";
		$query.= "AND TIMESTAMPDIFF(YEAR, birthday, CURDATE()) >= ? AND TIMESTAMPDIFF(YEAR, birthday, CURDATE()) <= ? ";
		
		array_push($array_params, $user_id, $age_from, $age_to);
		
		if($gender == 2) {
			$query .= "AND (gender = 0 OR gender = 1) ";
		} else if($gender == 0) {
			$query .= "AND gender = 0 ";
		} else {
			$query .= "AND gender = 1 ";
		}
		
		if($country != "0") {
			$query .= "AND country = ? ";
			array_push($array_params, $this->db->escape_str($country));
		}
		
		if($city != "") {
			$query .= "AND city LIKE ? ";
			array_push($array_params, "%".$city."%");
		}
		
		if($sort_by == 0) {
			$sort_by = "register_date";
		} else {
			$sort_by = "last_activity_date";
		}
		
		$query.= "AND u.id NOT IN (SELECT user_id FROM user_featured WHERE end_date >= DATE(UTC_TIMESTAMP())) ORDER BY $sort_by DESC LIMIT $page,$limit";
				   
		$users = $this->db->query($query, $array_params);
								   
		return $users;
	}
	
	function record_count_purchases()
	{
		$query = $this->db->query("SELECT id FROM user_purchase WHERE status = 1");
		
		return $query->num_rows();
	}
	
	function record_reported_count($type)
	{
		$query = $this->db->query("SELECT id FROM report WHERE status = 0 AND type = ?", array($type));
		
		return $query->num_rows();
	}
	
	function record_count()
	{
		$query = $this->db->query("SELECT id FROM user WHERE status = 1");
		
		return $query->num_rows();
	}
	
	// Get the numbers of new members in the last 24 hours 
	function count_today_new_users()
	{
		$query = $this->db->query("SELECT id FROM user WHERE register_date BETWEEN CONCAT(CURDATE(), ' ', '00:00:00') AND CONCAT(CURDATE(), ' ', '23:59:59')");
		
		return $query->num_rows();
	}
	
	// Add a love
	function add_love($from_user_id, $to_user_id) {
		$this->db->set(
						array(
								'from_user_id'			=> $from_user_id,
								'to_user_id'			=> $to_user_id,
								'viewed'				=> 0
							 )
					  )
				 ->set('date', 'UTC_TIMESTAMP()', false)
                 ->insert("user_love");
                
        return $this->db->insert_id();
	}
	
	function cancel_report($report_id)
	{
		$this->db->delete('report', array("id" => $report_id)); 
	}
	
	function delete_friends($user_id)
	{
		$this->db->delete('user_friend', array("from_user_id" => $user_id)); 
		$this->db->delete('user_friend', array("to_user_id" => $user_id)); 
	}
	
	function delete_loves($user_id)
	{
		$this->db->delete('user_love', array("from_user_id" => $user_id)); 
		$this->db->delete('user_love', array("to_user_id" => $user_id)); 
	}
	
	// Delete profile visits
	function delete_profile_visits($user_id)
	{
		$this->db->delete('user_profile_visit', array("user_id" => $user_id)); 
		$this->db->delete('user_profile_visit', array("profile_id" => $user_id)); 
	}
	
	// Remove love
	function remove_love($from_user_id, $to_user_id)
	{
		$this->db->delete('user_love', array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id)); 
	}
	
	// Check if love exists
	function check_love_exist($from_user_id, $to_user_id)
	{
		$query = $this->db->query("SELECT id FROM user_love WHERE from_user_id = " . intval($from_user_id) . " AND to_user_id = " . intval($to_user_id));
		
		if($query->num_rows() == 0)
			return false;
		else
			return true; 
	}
}