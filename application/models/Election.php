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

class Election extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function insert($election)
	{
		$this->db->insert('elections', $election);
        $insert_id = $this->db->insert_id();

        return $insert_id;
	}
    
    function insert_admin($admin)
	{
		return $this->db->insert('admins', $admin);
	}

	function update($election, $id)
	{
		return $this->db->update('elections', $election, compact('id'));
	}
    
    function update_admin($election, $electionid)
	{
		return $this->db->update('admins', $election, compact('electionid'));
	}

	function delete($id)
	{
		$this->db->where(compact('id'));
		return $this->db->delete('elections');
	}

	function select($id)
	{
		$this->db->from('elections');
		$this->db->where(compact('id'));
		$query = $this->db->get();
		return $query->row_array();
	}
    
    function select_admin($electionid)
	{
		$this->db->from('admins');
		$this->db->where(compact('electionid'));
		$query = $this->db->get();
		return $query->row_array();
	}

	function select_all()
	{
		$this->db->from('elections');
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_one($uid)
	{
		$this->db->from('elections');
		$this->db->where('id', $uid);
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_blone($uid)
	{
		return $this->db->query("SELECT * FROM elections WHERE id = $uid OR id = 1")->result_array();
		//$this->db->from('elections');  
		//$this->db->where('id', $uid);
		//$this->db->where('id', 1);
		//$query = $this->db->get();
		//return $query->result_array();
	}

	function select_all_by_ids($ids)
	{
		if(!empty($ids)) {
        	    $this->db->from('elections');
	            $this->db->where_in('id', $ids);
        	    $query = $this->db->get();
		    return $query->result_array();
       		 }
       		 else {
           		 return [];
        	}
	}

	function select_all_by_level()
	{
		$this->db->from('elections');
		$this->db->where('parent_id', '0');
		$this->db->order_by('id', 'ASC');
		$query = $this->db->get();
		$parents = $query->result_array();
		$return = array();
		foreach ($parents as $parent)
		{
			$return[] = $parent;
			$this->db->from('elections');
			$this->db->where('parent_id', $parent['id']);
			$this->db->order_by('id', 'ASC');
			$query = $this->db->get();
			$return = array_merge($return, $query->result_array());
		}
		return $return;
	}

	function select_by_level($uid)
	{
		$this->db->from('elections');
		$this->db->where('id', $uid);
		$this->db->order_by('id', 'ASC');
		$query = $this->db->get();
		$parents = $query->result_array();
		$return = array();
		foreach ($parents as $parent)
		{
			$return[] = $parent;
			$this->db->from('elections');
			$this->db->where('parent_id', $parent['id']);
			$this->db->order_by('id', 'ASC');
			$query = $this->db->get();
			$return = array_merge($return, $query->result_array());
		}
		return $return;
	}

	function select_all_children_by_parent_id($id)
	{
		$this->db->from('elections');
		$this->db->where('parent_id', $id);
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_all_parents()
	{
		$this->db->from('elections');
		$this->db->where('parent_id', '0');
		$query = $this->db->get();
		return $query->result_array();
	}

	// elections with results should not be running
	function select_all_with_results()
	{
		$this->db->from('elections');
		$this->db->where('results', TRUE);
		$this->db->where('status', FALSE); // not running
		$query = $this->db->get();
		return $query->result_array();
	}

	function in_use($election_id)
	{
		$this->db->from('positions');
		$this->db->where(compact('election_id'));
		$has_positions = $this->db->count_all_results() > 0 ? TRUE : FALSE;
		$this->db->from('parties');
		$this->db->where(compact('election_id'));
		$has_parties = $this->db->count_all_results() > 0 ? TRUE : FALSE;
		return $has_positions OR $has_parties ? TRUE : FALSE;
	}

	function is_running($ids)
	{
		if ( ! is_array($ids))
		{
			$ids = array($ids);
		}
		$this->db->from('elections');
		$this->db->where('status', TRUE);
		$this->db->where_in('id', $ids);
		return ($this->db->count_all_results() > 0) ? TRUE : FALSE;
	}

}

/* End of file election.php */
/* Location: ./application/models/election.php */