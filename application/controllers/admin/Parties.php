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

class Parties extends CI_Controller {

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
		$election_id = get_cookie('selected_election');
		$uadmin = $this->session->userdata('admin');
		$u = $uadmin['electionid'];
		if($u == 1) {
		$data['election_id'] = $election_id;
		$data['elections'] = $this->Election->select_all();
		$data['parties'] = $this->Party->select_all_by_election_id($election_id);
		$admin['username'] = $this->admin['username'];
		$admin['title'] = e('admin_parties_title');
		$admin['body'] = $this->load->view('admin/parties', $data, TRUE);
		}
		else {
		$data['election_id'] = $election_id;
		$data['elections'] = $this->Election->select_one($u);
		$data['parties'] = $this->Party->select_all_by_election_id($election_id);
		$admin['username'] = $this->admin['username'];
		$admin['title'] = e('admin_parties_title');
		$admin['body'] = $this->load->view('admin/parties', $data, TRUE);
		}
		$this->load->view('admin', $admin);
	}

	function add()
	{
		$this->_party('add');
	}

	function edit($id)
	{
		$this->_party('edit', $id);
	}

	function delete($id) 
	{
		if ( ! $id)
		{
			redirect('admin/parties');
		}
		$party = $this->Party->select($id);
		if ( ! $party)
		{
			redirect('admin/parties');
		}
		if ($this->Party->in_running_election($id))
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_party_in_running_election')));
		}
		else if ($this->Party->in_use($id))
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_delete_party_in_use')));
		}
		else
		{
			$this->Party->delete($id);
			$this->session->set_flashdata('messages', array('positive', e('admin_delete_party_success')));
		}
		redirect('admin/parties');
	}

	function _party($case, $id = null)
	{
		if ($case == 'add')
		{
			$data['party'] = array('election_id' => get_cookie('selected_election'), 'party' => '', 'alias' => '', 'description' => '');
			$this->session->unset_userdata('party'); // so callback rules know that the action is add
		}
		else if ($case == 'edit')
		{
			if ( ! $id)
			{
				redirect('admin/parties');
			}
			$data['party'] = $this->Party->select($id);
			if ( ! $data['party'])
			{
				redirect('admin/parties');
			}
			if ($this->Party->in_running_election($id))
			{
				$this->session->set_flashdata('messages', array('negative', e('admin_party_in_running_election')));
				redirect('admin/parties');			
			}
			$this->session->set_userdata('party', $data['party']); // used in callback rules
		}
		if ($this->input->post('election_id'))
		{
			// set cookie again since the election might have changed
			set_cookie('selected_election', $this->input->post('election_id'), 0);
		}
		$this->form_validation->set_rules('election_id', e('admin_party_election'), 'required|callback__rule_running_election');
		$this->form_validation->set_rules('party', e('admin_party_party'), 'required|callback__rule_party_exists|callback__rule_dependencies');
		$this->form_validation->set_rules('alias', e('admin_party_alias'));
		$this->form_validation->set_rules('description', e('admin_party_description'));
		$this->form_validation->set_rules('logo', e('admin_party_logo'), 'callback__rule_logo');
		if ($this->form_validation->run())
		{
			$party['election_id'] = $this->input->post('election_id', TRUE);
			$party['party'] = $this->input->post('party', TRUE);
			$party['alias'] = $this->input->post('alias', TRUE);
			$party['description'] = $this->input->post('description', TRUE);
			if ($logo = $this->session->userdata('party_logo'))
			{
				$party['logo'] = $logo;
				$this->session->unset_userdata('party_logo');
			}
			if ($case == 'add')
			{
				$this->Party->insert($party);
				$this->session->set_flashdata('messages', array('positive', e('admin_add_party_success')));
				redirect('admin/parties/add');
			}
			else if ($case == 'edit')
			{
				$this->Party->update($party, $id);
				$this->session->set_flashdata('messages', array('positive', e('admin_edit_party_success')));
				redirect('admin/parties/edit/' . $id);
			}
		}
		$uadmin = $this->session->userdata('admin');
		$u = $uadmin['electionid'];
		if($u == 1) {
		$data['elections'] = $this->Election->select_all();
		$data['action'] = $case;
		$admin['title'] = e('admin_' . $case . '_party_title');
		$admin['body'] = $this->load->view('admin/party', $data, TRUE);
		$admin['username'] = $this->admin['username'];
		}
		else {
		$data['elections'] = $this->Election->select_one($u);
		$data['action'] = $case;
		$admin['title'] = e('admin_' . $case . '_party_title');
		$admin['body'] = $this->load->view('admin/party', $data, TRUE);
		$admin['username'] = $this->admin['username'];
		}
		$this->load->view('admin', $admin);
	}

	function _rule_party_exists()
	{
		$election_id = $this->input->post('election_id');
		$party = trim($this->input->post('party', TRUE));
		if ($test = $this->Party->select_by_election_id_and_party($election_id, $party))
		{
			$error = FALSE;
			if ($party = $this->session->userdata('party')) // edit
			{
				if ($test['id'] != $party['id'])
				{
					$error = TRUE;
				}
			}
			else
			{
				$error = TRUE;
			}
			if ($error)
			{
				$message = e('admin_party_exists') . ' (' . $test['party'] . ')';
				$this->form_validation->set_message('_rule_party_exists', $message);
				return FALSE;
			}
		}
		else
		{
			return TRUE;
		}
	}

	// placed in position so it comes up on top
	function _rule_dependencies()
	{
		if ($party = $this->session->userdata('party')) // edit
		{
			// don't check if chosen is empty
			if ($this->input->post('chosen') == FALSE)
			{
				return TRUE;
			}
			if ($this->Party->in_use($party['id']))
			{
				if ($party['election_id'] != $this->input->post('election_id'))
				{
					$this->form_validation->set_message('_rule_dependencies', e('admin_party_dependencies'));
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	function _rule_running_election()
	{
		if ($this->Election->is_running($this->input->post('election_id')))
		{
			$this->form_validation->set_message('_rule_running_election', e('admin_party_running_election'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	function _rule_logo()
	{
		if ($_FILES['logo']['error'] != UPLOAD_ERR_NO_FILE)
		{
			$config['upload_path'] = HALALAN_UPLOAD_PATH . 'logos/';
			$config['allowed_types'] = HALALAN_ALLOWED_TYPES;
			$this->upload->initialize($config);
			if ($party = $this->session->userdata('party')) // edit
			{
				// delete old logo first
				unlink($config['upload_path'] . $party['logo']);
			}
			if ( ! $this->upload->do_upload('logo'))
			{
				$message = $this->upload->display_errors('', '');
				$this->form_validation->set_message('_rule_logo', $message);
				return FALSE;
			}
			else
			{
				$upload_data = $this->upload->data();
				$return = $this->_resize($upload_data, 250);
				if (is_array($return))
				{
					$this->form_validation->set_message('_rule_logo', $return[0]);
					return FALSE;
				}
				else
				{
					// flashdata doesn't work I don't know why
					$this->session->set_userdata('party_logo', $return);
					return TRUE;
				}
			}
		}
		else
		{
			return TRUE;
		}
	}

	function _resize($upload_data, $n)
	{
		$width = $upload_data['image_width'];
		$height = $upload_data['image_height'];
		if ($width > $n || $height > $n)
		{
			$config['source_image'] = $upload_data['full_path'];
			$config['quality'] = '100%';
			$config['width'] = $n;
			$config['height'] = (($n * $height) / $width);
			$this->image_lib->initialize($config);
			if ( ! $this->image_lib->resize())
			{
				$error[] = $this->image_lib->display_errors('', '');
			}
			else
			{
				$name = $upload_data['file_name'];
			}
		}
		else
		{
			$name = $upload_data['file_name'];
		}
		if (empty($error))
		{
			return $name;
		}
		else
		{
			return $error;
		}
	}

}

/* End of file parties.php */
/* Location: ./system/application/controllers/admin/parties.php */
