<?php
/**
 * Copyright (C) 2006-2012 University of the Philippines Linux Users' Group
 *
 * This file is part of Halalan.
 *
 * Halalan is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Halalan is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Halalan.  If not, see <http://www.gnu.org/licenses/>.
 */

class Logs_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}

	function get_logs()
	{
		$this->db->from('logs');
        $this->db->order_by('timestamp', 'DESC');
        $this->db->limit(20);
		$query = $this->db->get();
		return $query->result_array();
	}

    function get_all_logs()
	{
		$this->db->from('logs');
        $this->db->order_by('timestamp', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}
    
    function get_userid($username)
    {
        $this->db->where('username', $username);        
        $result = $this->db->get('admins');
        if($result->num_rows()==0){
            return 0;
        } else {
            return $result->row()->id;
        }
    }
    
    function insert_log($username, $id, $type, $ipaddress, $user)
    {
        $userid = $this->get_userid($username);
        if($type == "voter")
        {
            $desc = "Password reset for ".$user." by ".$username;
            $this->db->set('adminid', $userid);
            $this->db->set('voterid', $id);
            $this->db->set('ipaddress', $ipaddress);
            $this->db->set('description', $desc);
            $this->db->insert('logs');
        }
        elseif($type == "block")
        {
            $desc = "Generate password for ".$user." block by ".$username;
            $this->db->set('adminid', $userid);
            $this->db->set('blockid', $id);
            $this->db->set('ipaddress', $ipaddress);
            $this->db->set('description', $desc);
            $this->db->insert('logs');
        }
    }

}

/* End of file logs_model.php */
/* Location: ./application/models/logs_model.php */
