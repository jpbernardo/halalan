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

class Position extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function insert($position)
	{
		return $this->db->insert('positions', $position);
	}

	function update($position, $id)
	{
		return $this->db->update('positions', $position, compact('id'));
	}

	function delete($id)
	{
		$this->db->where(compact('id'));
		return $this->db->delete('positions');
	}

	function select($id)
	{
		$this->db->from('positions');
		$this->db->where(compact('id'));
		$query = $this->db->get();
		return $query->row_array();
	}

	function select_name($id)
	{
		$this->db->from('positions');
		$this->db->where(compact('id'));
		$query = $this->db->get();
		return $query->row()->position;
	}

	function select_all_by_ids($ids)
	{
        if(!empty($ids)) {
            $this->db->from('positions');
            $this->db->where_in('id', $ids);
            $this->db->order_by('ordinality', 'ASC');
            $query = $this->db->get();
            return $query->result_array();
        }
        else {
            return [];
        }
	}

	function select_all_by_election_id($election_id)
	{
		$this->db->from('positions');
		$this->db->where('election_id', $election_id);
		$this->db->order_by('ordinality', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_by_election_id_and_position($election_id, $position)
	{
		$this->db->from('positions');
		$this->db->where(compact('election_id'));
		$this->db->where(compact('position'));
		$query = $this->db->get();
		return $query->row_array();
	}

	function in_use($position_id)
	{
		$this->db->from('candidates');
		$this->db->where(compact('position_id'));
		$has_candidates = $this->db->count_all_results() > 0 ? TRUE : FALSE;
		$this->db->from('blocks_elections_positions');
		$this->db->where(compact('position_id'));
		$has_blocks = $this->db->count_all_results() > 0 ? TRUE : FALSE;
		return $has_candidates || $has_blocks ? TRUE : FALSE;
	}

	function in_running_election($id)
	{
		$this->db->from('positions');
		$this->db->where(compact('id'));
		$this->db->where('election_id IN (SELECT id FROM elections WHERE status = 1)');
		return ($this->db->count_all_results() > 0) ? TRUE : FALSE;
	}

}

/* End of file position.php */
/* Location: ./application/models/position.php */
