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

class Home extends CI_Controller {

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
		
		$data['settings'] = $this->settings;
		$admin['username'] = $this->admin['username'];
		$admin['title'] = e('admin_home_title');
		$admin['body'] = $this->load->view('admin/home', $data, TRUE);
		$this->load->view('admin', $admin);
	}

	function do_regenerate()
	{
		$uadmin = $this->session->userdata('admin');
        $u = $uadmin['electionid'];
        $uname = $uadmin['username'];
        $error = array();
		if ( ! $this->input->post('username'))
		{
			if ($this->settings['password_pin_generation'] == 'web')
			{
				$error[] = e('admin_regenerate_no_username');
			}
			else if ($this->settings['password_pin_generation'] == 'email')
			{
				$error[] = e('admin_regenerate_no_email');
			}
		}
		else
		{
			if ( ! $voter = $this->Boter->select_by_username($this->input->post('username')))
			{
				$error[] = e('admin_regenerate_not_exists');
			}
            elseif($u != 1) {
                if($u != $this->Block->select_election_id($voter['block_id']))
                {
                    echo $voter['block_id'];
                    $error[] = "Voter does not belong to your election.";
                }
            }
		}
		if ($this->settings['password_pin_generation'] == 'email')
		{
			if ( ! $this->form_validation->valid_email($this->input->post('username')))
			{
				$error[] = e('admin_regenerate_invalid_email');
			}
		}
		if (empty($error))
		{
            if($u == 1)
            {
                $password = random_string($this->settings['password_pin_characters'], $this->settings['password_length']);
                $userpass = hash('sha256', $password);
                $userpass = strtoupper($userpass);
                $salt = $this->Boter->get_salt($this->input->post('username'));
                $userpass = $salt.''.$userpass;
                $userpass = hash('sha256', $userpass);
                $userpass = strtoupper($userpass);
                $voter['password'] = $userpass;
                if ($this->settings['pin'])
                {
                    if ($this->input->post('pin'))
                    {
                        $pin = random_string($this->settings['password_pin_characters'], $this->settings['pin_length']);
                        $voter['pin'] = sha1($pin);
                    }
                }
                if ($this->input->post('login'))
                {
                    $voter['login'] = NULL;
                }
                $this->Boter->update($voter, $voter['id']);
                $success = array();
                $success[] = e('admin_regenerate_success');
                if ($this->settings['password_pin_generation'] == 'web')
                {
                    $success[] = 'Username: '. $voter['username'];
                    $success[] = 'Password: '. $password;
                    if ($this->settings['pin'])
                    {
                        if ($this->input->post('pin'))
                            $success[] = 'PIN: '. $pin;
                    }
		            $this->load->model('logs_model');
                    $ipaddress = $this->input->ip_address();
                    $this->logs_model->insert_log($uname, $voter['id'], "voter", $ipaddress, $voter['username']);
                }
                else if ($this->settings['password_pin_generation'] == 'email')
                {
                    $this->email->from($this->admin['email'], $this->admin['first_name'] . ' ' . $this->admin['last_name']);
                    $this->email->to($voter['username']);
                    $this->email->subject('Halalan Login Credentials');
                    $message = "Hello $voter[first_name] $voter[last_name],\n\nThe following are your login credentials:\nEmail: $voter[username]\n";
                    $message .= "Password: $password\n";
                    if ($this->settings['pin'])
                    {
                        if ($this->input->post('pin'))
                            $message .= "PIN: $pin\n";
                    }
                    $message .= "Vote through ".base_url()."\n";
                    $message .= ($this->admin['first_name'] . ' ' . $this->admin['last_name']);
                    $message .= "\n";
                    $message .= 'Halalan Administrator';
                    $this->email->message($message);
                    $this->email->send();
                    //echo $this->email->print_debugger();
                    $success[] = e('admin_regenerate_email_success');
                }
                $this->session->set_flashdata('messages', array_merge(array('positive'), $success));
            }
            else
            {
                $passkey = $this->input->post('passkey');
                if (strlen($passkey) != 40)
                {
                    $passkey = sha1($passkey);
                }
                if($user = $this->Abmin->check_key($uname, $passkey))
                {
                    $password = random_string($this->settings['password_pin_characters'], $this->settings['password_length']);
                    $userpass = hash('sha256', $password);
                    $userpass = strtoupper($userpass);
                    $salt = $this->Boter->get_salt($this->input->post('username'));
                    $userpass = $salt.''.$userpass;
                    $userpass = hash('sha256', $userpass);
                    $userpass = strtoupper($userpass);
                    $voter['password'] = $userpass;
                    if ($this->settings['pin'])
                    {
                        if ($this->input->post('pin'))
                        {
                            $pin = random_string($this->settings['password_pin_characters'], $this->settings['pin_length']);
                            $voter['pin'] = sha1($pin);
                        }
                    }
                    if ($this->input->post('login'))
                    {
                        $voter['login'] = NULL;
                    }
                    $this->Boter->update($voter, $voter['id']);
                    $success = array();
                    $success[] = e('admin_regenerate_success');
                    if ($this->settings['password_pin_generation'] == 'web')
                    {
                        $success[] = 'Username: '. $voter['username'];
                        $success[] = 'Password: '. $password;
                        if ($this->settings['pin'])
                        {
                            if ($this->input->post('pin'))
                                $success[] = 'PIN: '. $pin;
                        }
                        $this->load->model('logs_model');
                        $ipaddress = $this->input->ip_address();
                        $this->logs_model->insert_log($uname, $voter['id'], "voter", $ipaddress, $voter['username']);
                    }
                    else if ($this->settings['password_pin_generation'] == 'email')
                    {
                        $this->email->from($this->admin['email'], $this->admin['first_name'] . ' ' . $this->admin['last_name']);
                        $this->email->to($voter['username']);
                        $this->email->subject('Halalan Login Credentials');
                        $message = "Hello $voter[first_name] $voter[last_name],\n\nThe following are your login credentials:\nEmail: $voter[username]\n";
                        $message .= "Password: $password\n";
                        if ($this->settings['pin'])
                        {
                            if ($this->input->post('pin'))
                                $message .= "PIN: $pin\n";
                        }
                        $message .= "Vote through ".base_url()."\n";
                        $message .= ($this->admin['first_name'] . ' ' . $this->admin['last_name']);
                        $message .= "\n";
                        $message .= 'Halalan Administrator';
                        $this->email->message($message);
                        $this->email->send();
                        //echo $this->email->print_debugger();
                        $success[] = e('admin_regenerate_email_success');
                    }
                    $this->session->set_flashdata('messages', array_merge(array('positive'), $success));
                }
                else
                {
                    $error = array();
                    $error[] = "Passkey did not match.";
                    $this->session->set_flashdata('messages', array_merge(array('negative'), $error));
                }
            }
		}
		else
		{
			$this->session->set_flashdata('messages', array_merge(array('negative'), $error));
		}
		redirect('admin/home');
	}
    
    function reset_db()
	{
		$this->Abmin->reset_db();
        redirect('admin/home');
	}

}

/* End of file home.php */
/* Location: ./system/application/controllers/admin/home.php */