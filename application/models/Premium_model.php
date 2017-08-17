<?php
class premium_model extends CI_Model 
{
	// Get coins
	function get_coins()
	{
		$query = $this->db->select('id, nb, price')
		                  ->from("coin")
		                  ->get();
		                  
		return $query;
	}
	
	// Get user coin
	function get_user_coin($user_id)
	{
		
		$query = $this->db->select('id, nb_coins')
                  ->from("user_coin")
                  ->where(array("user_id" => $user_id))
                  ->get();
		                  
		return $query;
	}
	
	function has_purchased($user_id, $purchase_name)
	{
		$query =  $this->db->select('id')
               ->from("user_purchase")
               ->where(array("user_id" => $user_id, "purchase_name" => $purchase_name))
               ->get();
						   
	    if($query->num_rows() == 0) {
		    return false;
	    } else {
		    return true;
	    }
	}
	
	// Get last registered users with pagination support & params
	function get_last_featured_users($limit, $age_from, $age_to, $gender, $country, $city, $user_id = 0)
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
		
		$array_params[] = $user_id;
		$array_params[] = $age_from;
		$array_params[] = $age_to;
		
		if($gender == 2) {
			$query .= "AND (gender = 0 OR gender = 1) ";
		} else if($gender == 0) {
			$query .= "AND gender = 0 ";
		} else {
			$query .= "AND gender = 1 ";
		}
		
		if($country != "0") {
			$query .= "AND country = ? ";
			$array_params[] = $this->db->escape_str($country);
		}
		
		if($city != "") {
			$query .= "AND city LIKE ? ";
			$array_params[] = $this->db->escape_str("%".$city."%");
		}
				
		$query.= "AND u.id IN (SELECT user_id FROM user_featured WHERE end_date >= DATE(UTC_TIMESTAMP())) ORDER BY RAND() DESC LIMIT $limit";
		
		$users = $this->db->query($query, $array_params);
										   
		return $users;
	}
	
	// Get one month featured
	function get_one_month_featured($user_id)
	{
		$users = $this->db->query("SELECT start_date, end_date, purchase_name
								   FROM user_featured 
								   WHERE user_id = ? 
								   AND
								   purchase_name = 'featured_one_month' 
								   AND
								   end_date >= DATE(UTC_TIMESTAMP())", array($user_id));
		
		return $users;
	}
	
	// Get one week featured
	function get_one_week_featured($user_id)
	{
		$users = $this->db->query("SELECT start_date, end_date, purchase_name
								   FROM user_featured 
								   WHERE user_id = ? 
								   AND
								   purchase_name = 'featured_one_week' 
								   AND
								   end_date >= DATE(UTC_TIMESTAMP())", array($user_id));
		
		return $users;
	}

	// Check if this coins value exists
	function check_coins($nb)
	{
		$query =  $this->db->select('id')
	                   ->from("coin")
	                   ->where("nb", $nb)
	                   ->get();
						   
	    if($query->num_rows() == 0) {
		    return false;
	    } else {
		    return true;
	    }
	}
	
	// Set user featured for one week
	function set_user_featured_one_week($user_id, $feature)
	{
		$this->db->set(array('user_id' 	=> $user_id, 'purchase_name' => $feature))
                ->set('start_date', 'UTC_TIMESTAMP()', false)
                ->set('end_date', 'UTC_TIMESTAMP() + interval 1 week', false)
                ->insert("user_featured"); 
                
        return $this->db->insert_id();
	}
	
	// Set user featured for one month
	function set_user_featured_one_month($user_id, $feature)
	{
		$this->db->set(array('user_id' 	=> $user_id, 'purchase_name' => $feature))
                ->set('start_date', 'UTC_TIMESTAMP()', false)
                ->set('end_date', 'UTC_TIMESTAMP() + interval 1 month', false)
                ->insert("user_featured"); 
                
        return $this->db->insert_id();
	}
	
	// Get user's purchases
	function get_user_purchases($user_id)
	{
		$query = $this->db->select('purchase_name')
                  		  ->from("user_purchase")
				  		  ->where(array("user_id" => $user_id))
				  		  ->get();
		                  
		return $query;
	}
	
	// Decrease user coins row
	function decrease_user_coins($user_id, $nb_coins)
	{
		$this->db->where('user_id', $user_id);
		$this->db->set('nb_coins', 'nb_coins-' . $nb_coins, FALSE);
		$this->db->update('user_coin');
	}
	
	// Update user coins row
	function update_user_coins($user_id, $nb_coins)
	{
		$this->db->where('user_id', $user_id);
		$this->db->set('nb_coins', 'nb_coins+' . $nb_coins, FALSE);
		$this->db->update('user_coin');
	}
	
	function set_user_coins($user_id, $nb_coins)
	{
		$this->db->where('user_id', $user_id);
		$this->db->set('nb_coins', $nb_coins);
		$this->db->update('user_coin');
	}
	
	// Delete all the coins
	function delete_coins()
	{
		$this->db->delete('coin', array("id >=" => 1)); 
	}
	
	// Create a coin
	function create_coin($nb_coin, $price)
	{		    
	    $this->db->set(array('nb'		=> $nb_coin,
	    					 'price'	=> $price))
                ->insert("coin");
                
        return $this->db->insert_id();
	}
	
	// Create user coins row
	function create_user_coins($user_id)
	{		    
	    $this->db->set(array('user_id'		=> $user_id,
	    					 'nb_coins'		=> 0))
                ->set('updated_date', 'UTC_TIMESTAMP()', false)
                ->insert("user_coin");
                
        return $this->db->insert_id();
	}
	
	// Check if the user coins exists
	function check_user_coins_exists($user_id)
	{
		$query =  $this->db->select('id')
               ->from("user_coin")
               ->where("user_id", $user_id)
               ->get();
						   
	    if($query->num_rows() == 0) {
		    return false;
	    } else {
		    return true;
	    }
	}
	
	// Get coin infos
	function get_coin_infos($nb)
	{
		$this->db->select('id, nb, price');
		$this->db->from("coin");
		$this->db->where("nb", $nb);
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		return $query;
	}
}