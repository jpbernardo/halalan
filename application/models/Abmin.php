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

class Abmin extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}

	function authenticate($username, $password)
	{
		$salt = $this->get_salt($username);
        $password = strtoupper($password);
        $password = $salt.''.$password;
        $password = hash('sha256', $password);
        $password = strtoupper($password);
        $this->db->from('admins');
		$this->db->where(compact('username', 'password'));
		$query = $this->db->get();
		return $query->row_array();
	}
    
    function check_key($username, $passkey)
	{
		$this->db->from('admins');
		$this->db->where(compact('username', 'passkey'));
		$query = $this->db->get();
		return $query->row_array();
	}
    
    function select($id)
	{
		$this->db->from('admins');
		$this->db->where(compact('id'));
		$query = $this->db->get();
		return $query->row_array();
	}
    
    function get_salt($username)
    {
        $this->db->select('salt');
        $this->db->from('admins');
        $this->db->where('username',$username);
        return $this->db->get()->row()->salt;

    }
    
    function reset_db()
    {
        $this->db->truncate('abstains');
        $this->db->truncate('blocks');
        $this->db->truncate('blocks_elections_positions');
        $this->db->truncate('candidates');
        $this->db->truncate('captchas');
        $this->db->truncate('elections');
        $this->db->truncate('logs');
        $this->db->truncate('parties');
        $this->db->truncate('positions');
        $this->db->truncate('voted');
        $this->db->truncate('voters');
        $this->db->truncate('votes');
        
        $this->db->where('id !=', 1);
		return $this->db->delete('admins');
    }

}

/* End of file abmin.php */
/* Location: ./application/models/abmin.php */