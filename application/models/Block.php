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

class Block extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function insert($block)
	{
		if (isset($block['extra']))
		{
			$extra = $block['extra'];
			unset($block['extra']);
		}
		$this->db->insert('blocks', $block);
		if ( ! empty($extra))
		{
			$block_id = $this->db->insert_id();
			foreach ($extra as $e)
			{
				$election_id = $e['election_id'];
				$position_id = $e['position_id'];
				$this->db->insert('blocks_elections_positions', compact('block_id', 'election_id', 'position_id'));
			}
		}
		return TRUE;
	}

	function update($block, $id)
	{
		if (isset($block['extra']))
		{
			$extra = $block['extra'];
			unset($block['extra']);
			$this->db->where('block_id', $id);
			$this->db->delete('blocks_elections_positions');
		}
		$this->db->update('blocks', $block, compact('id'));
		if ( ! empty($extra))
		{
			$block_id = $id;
			foreach ($extra as $e)
			{
				$election_id = $e['election_id'];
				$position_id = $e['position_id'];
				$this->db->insert('blocks_elections_positions', compact('block_id', 'election_id', 'position_id'));
			}
		}
		return TRUE;
	}

	function delete($id)
	{
		$this->db->where('block_id', $id);
		$this->db->delete('blocks_elections_positions');
		$this->db->where(compact('id'));
		return $this->db->delete('blocks');
	}

	function select($id)
	{
		$this->db->from('blocks');
		$this->db->where(compact('id'));
		$query = $this->db->get();
		return $query->row_array();
	}

	function select_all()
	{
		$this->db->from('blocks');
		$this->db->order_by('block', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_all_by_election_id($election_id)
	{
		$this->db->distinct();
		$this->db->select('blocks.*');
		$this->db->from('blocks');
		$this->db->join('blocks_elections_positions', 'blocks.id = blocks_elections_positions.block_id');
		$this->db->where('election_id', $election_id);
		$this->db->order_by('block', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_by_block($block)
	{
		$this->db->from('blocks');
		$this->db->where('block', $block);
		$query = $this->db->get();
		return $query->row_array();
	}

	function in_use($block_id)
	{
		$this->db->from('voters');
		$this->db->where('block_id', $block_id);
		return $this->db->count_all_results() > 0 ? TRUE : FALSE;
	}

	function in_running_election($block_id)
	{
		$this->db->from('blocks_elections_positions');
		$this->db->where('block_id', $block_id);
		$this->db->where('election_id IN (SELECT id FROM elections WHERE status = 1)');
		return $this->db->count_all_results() > 0 ? TRUE : FALSE;
	}
    
    function select_election_id($id)
    {
        $this->db->where('block_id', $id);
	$this->db->where('election_id !=', 1);
        $this->db->limit(1);
        $result = $this->db->get('blocks_elections_positions');
        if($result->num_rows()==0){
            return 0;
        } else {
            return $result->row()->election_id;
        }
    }

}

/* End of file block.php */
/* Location: ./application/models/block.php */
