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

class Regenerate extends CI_Controller {

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
		$this->load->model('regen');
		$data['admins'] = $this->regen->get_admins();
		$admin['username'] = $this->admin['username'];
		$admin['title'] = e('admin_home_title');
		$admin['body'] = $this->load->view('admin/regenerate/home', $data, TRUE);
		$this->load->view('admin', $admin);
		}
		else {

		}
	}

	function do_regenerate($user, $id)
	{
		$error = array();
		if (empty($error))
		{
			$password = random_string($this->settings['password_pin_characters'], $this->settings['password_length']);
			$userpass = hash('sha256', $password);
            $userpass = strtoupper($userpass);
            $salt = $this->abmin->get_salt($user);
            $userpass = $salt.''.$userpass;
            $userpass = hash('sha256', $userpass);
            $userpass = strtoupper($userpass);
			$this->load->model('regen');
			$this->regen->update($userpass, $id);
			$success = array();
			$success[] = e('admin_regenerate_success');
			if ($this->settings['password_pin_generation'] == 'web')
			{
				$success[] = 'Username: '. $user;
				$success[] = 'Password: '. $password;
			}
			$this->session->set_flashdata('messages', array_merge(array('positive'), $success));
		}
		else
		{
			$this->session->set_flashdata('messages', array_merge(array('negative'), $error));
		}
		redirect('admin/regenerate');
	}
    
    function update_key($user, $id)
	{
		$error = array();
		if (empty($error))
		{
			$passkey = random_string($this->settings['password_pin_characters'], $this->settings['password_length']);
			$userkey = sha1($passkey);
			$this->load->model('regen');
			$this->regen->update_key($userkey, $id);
			$success = array();
			$success[] = e('admin_regenerate_success');
			if ($this->settings['password_pin_generation'] == 'web')
			{
				$success[] = 'College/User: '. $user;
				$success[] = 'Passkey: '. $passkey;
			}
			$this->session->set_flashdata('messages', array_merge(array('positive'), $success));
		}
		else
		{
			$this->session->set_flashdata('messages', array_merge(array('negative'), $error));
		}
		redirect('admin/regenerate');
	}

	function _rule_running_election()
	{
		if ($this->Election->is_running($this->input->post('election_id')))
		{
			$this->form_validation->set_message('_rule_running_election', e('admin_candidate_running_election'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

}


