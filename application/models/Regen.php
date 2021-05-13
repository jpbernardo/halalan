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

class Regen extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}

	function get_admins()
	{
		$this->db->from('admins');
		$this->db->where('electionid !=', 1);
		$query = $this->db->get();
		return $query->result_array();
	}

	function update($userpass, $id)
	{
		$this->db->set('password', $userpass);
		$this->db->where(compact('id'));
		$this->db->update('admins');
	}
    
    function update_key($userpass, $id)
	{
		$this->db->set('passkey', $userpass);
		$this->db->where(compact('id'));
		$this->db->update('admins');
	}

}

/* End of file regen.php */
/* Location: ./application/models/regen.php */
