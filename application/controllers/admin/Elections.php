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

class Elections extends CI_Controller {

	var $admin;
	var $settings;

	function __construct()
	{
		parent::__construct();
		$this->admin = $this->session->userdata('admin');
		if ( ! $this->admin)
		{
			$this->session->set_flashdata('messages', array('negative', e('common_unauthorized')));
			redirect('gate/admin');
		}
		$this->settings = $this->config->item('halalan');
	}
	
	function index()
	{
		$uadmin = $this->session->userdata('admin');
		$u = $uadmin['electionid'];
		if($u == 1) {
		$data['elections'] = $this->Election->select_all_by_level();
		$admin['username'] = $this->admin['username'];
		$admin['title'] = e('admin_elections_title');
		$admin['body'] = $this->load->view('admin/elections', $data, TRUE);
		}
		else {
		$data['elections'] = $this->Election->select_by_level($u);
		$admin['username'] = $this->admin['username'];
		$admin['title'] = e('admin_elections_title');
		$admin['body'] = $this->load->view('admin/elections', $data, TRUE);
		}
		$this->load->view('admin', $admin);
	}

	function add()
	{
		$this->_election('add');
	}

	function edit($id)
	{
		$this->_election('edit', $id);
	}

	function delete($id) 
	{
		if ( ! $id)
		{
			redirect('admin/elections');
		}
		$election = $this->Election->select($id);
		if ( ! $election)
		{
			redirect('admin/elections');
		}
		if ($election['status'])
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_delete_election_running')));
		}
		else if ($this->Election->in_use($id))
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_delete_election_in_use')));
		}
		else
		{
			$this->Election->delete($id);
			$this->session->set_flashdata('messages', array('positive', e('admin_delete_election_success')));
		}
		redirect('admin/elections');
	}

	function options($case, $id)
	{
		if ($case == 'status' || $case == 'results')
		{
			$election = $this->Election->select($id);
			if ($election)
			{
				$data = array();
				if ($case == 'status')
				{
					$data['status'] = ! $election['status'];
				}
				else
				{
					$data['results'] = ! $election['results'];
				}
				if ($election['parent_id'] == 0)
				{
					$children = $this->Election->select_all_children_by_parent_id($id);
					foreach ($children as $child)
					{
						$this->Election->update($data, $child['id']);
					}
				}
				$this->Election->update($data, $id);
				$this->session->set_flashdata('messages', array('positive', e('admin_options_election_success')));
			}
		}
		if ($case == 'download')
        	{
                $header = '';
                $header = 'Position,Candidate,Party,Total Votes';
                $data[] = $header;
                $elections = $this->Election->select_all_by_ids($id);
                foreach ($elections as $key1 => $election)
                {
                    $positions = $this->Position->select_all_by_election_id($election['id']);
                    foreach ($positions as $key2 => $position)
                    {

                        $votes = $this->Vote->count_all_by_election_id_and_position_id($election['id'], $position['id']);
                        foreach ($votes as $vote)
                        {
                            $candy = $this->Candidate->select($vote['candidate_id']);
                            $candidate = $this->Position->select_name($position['id']) . "," . $this->Candidate->select_name($vote['candidate_id']) . ",".$this->Party->select_name($candy['party_id']) . ",".$vote['votes'];
                            $data[] = $candidate;
                        }
                        $candidate = ",Abstain,," . $this->Abstain->count_all_by_election_id_and_position_id($election['id'], $position['id']);
                        $data[] = $candidate;
                    }
                }            
                $data = implode("\r\n", $data);   
                force_download('votes.csv', $data);
        }
		redirect('admin/elections');
	}

	function _election($case, $id = null)
	{
		if ($case == 'add')
		{
			$data['election'] = array('election' => '', 'parent_id' => '');
            $data['admin'] = array('username' => '', 'email' => '', 'last_name' => '', 'first_name' => '');
		}
		else if ($case == 'edit')
		{
			if ( ! $id)
			{
				redirect('admin/elections');
			}
			$data['election'] = $this->Election->select($id);
            $data['admin'] = $this->Election->select_admin($id);
			if ( ! $data['election'])
			{
				redirect('admin/elections');
			}
			if ($data['election']['status'])
			{
				$this->session->set_flashdata('messages', array('negative', e('admin_edit_election_running')));
				redirect('admin/elections');
			}
		}
		$this->form_validation->set_rules('election', e('admin_election_election'), 'required');
		$this->form_validation->set_rules('parent_id', e('admin_election_parent'));
        $this->form_validation->set_rules('username', e('admin_election_username'), 'required');
        $this->form_validation->set_rules('last_name', e('admin_election_last_name'), 'required');
        $this->form_validation->set_rules('first_name', e('admin_election_first_name'), 'required');
        $this->form_validation->set_rules('email', e('admin_election_email'), 'required|valid_email');
		if ($this->form_validation->run())
		{
			$election['election'] = $this->input->post('election', TRUE);
			$election['parent_id'] = $this->input->post('parent_id', TRUE);
            $admin['email'] = $this->input->post('email', TRUE);
            $admin['username'] = $this->input->post('username', TRUE);
            $admin['last_name'] = $this->input->post('last_name', TRUE);
            $admin['first_name'] = $this->input->post('first_name', TRUE);
			if ($case == 'add')
			{
                $admin['electionid'] = $this->Election->insert($election);
                $this->Election->insert_admin($admin);
				$this->session->set_flashdata('messages', array('positive', e('admin_add_election_success')));
				redirect('admin/elections/add');
			}
			else if ($case == 'edit')
			{
				$this->Election->update($election, $id);
                $this->Election->update_admin($admin, $id);
				$this->session->set_flashdata('messages', array('positive', e('admin_edit_election_success')));
				redirect('admin/elections/edit/' . $id);
			}
		}
		$data['parents'] = $this->Election->select_all_parents();
		$data['action'] = $case;
		$admin['title'] = e('admin_' . $case . '_election_title');
		$admin['body'] = $this->load->view('admin/election', $data, TRUE);
		$admin['username'] = $this->admin['username'];
		$this->load->view('admin', $admin);
	}

}

/* End of file elections.php */
/* Location: ./system/application/controllers/admin/elections.php */