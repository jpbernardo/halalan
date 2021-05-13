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

class Candidate extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function insert($candidate)
	{
		return $this->db->insert('candidates', $candidate);
	}

	function update($candidate, $id)
	{
		return $this->db->update('candidates', $candidate, compact('id'));
	}

	function delete($id)
	{
		$this->db->where(compact('id'));
		return $this->db->delete('candidates');
	}

	function select($id)
	{
		$this->db->where(compact('id'));
		$this->db->from('candidates');
		$query = $this->db->get();
		return $query->row_array();
	}

	function select_name($id)
	{
		$this->db->where(compact('id'));
		$this->db->from('candidates');
		$query = $this->db->get();
		return $query->row()->first_name . " " . $query->row()->last_name;
	}

	function select_all_by_election_id_and_position_id($election_id, $position_id)
	{
		$this->db->from('candidates');
		$this->db->where(compact('election_id', 'position_id'));
		$this->db->order_by('last_name', 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_by_election_id_and_name_and_alias($election_id, $first_name, $last_name, $alias)
	{
		$this->db->from('candidates');
		$this->db->where(compact('election_id'));
		if (empty($alias))
		{
			$this->db->where(compact('first_name', 'last_name'));
		}
		else
		{
			$this->db->where(compact('first_name', 'last_name', 'alias'));
		}
		$query = $this->db->get();
		return $query->row_array();
	}

	function in_use($candidate_id)
	{
		$this->db->from('votes');
		$this->db->where(compact('candidate_id'));
		return ($this->db->count_all_results() > 0) ? TRUE : FALSE;
	}

	function in_running_election($candidate_id)
	{
		$this->db->from('candidates');
		$this->db->where('id', $candidate_id);
		$this->db->where('election_id IN (SELECT id FROM elections WHERE status = 1)');
		return ($this->db->count_all_results() > 0) ? TRUE : FALSE;
	}

	function select_all_by_election_id($election_id)
	{
		$this->db->from('candidates');
		$this->db->where(compact('election_id'));
		$this->db->order_by('last_name', 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_parties_by_election_id_and_position_id($election_id, $position_id)
	{
		$this->db->distinct();
		$this->db->select('party_id');
		$this->db->from('candidates');
		$this->db->where(compact('election_id', 'position_id'));
		$this->db->order_by('party_id', 'ASC');
		$query = $this->db->get();
		$results = $query->result_array();
		$parties = array();
		foreach ($results as $result)
		{
			$this->db->from('parties');
			$this->db->where('id', $result['party_id']);
			$query = $this->db->get();
			$parties[] = $query->row_array();
		}
		return $parties;
	}

	function select_all_by_election_id_and_position_id_and_party_id($election_id, $position_id, $party_id)
	{
		$this->db->from('candidates');
		$this->db->where(compact('election_id', 'position_id', 'party_id'));
		$this->db->order_by('last_name', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

}

/* End of file candidate.php */
/* Location: ./application/models/candidate.php */
