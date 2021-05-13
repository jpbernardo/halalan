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

class Logs extends CI_Controller {

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
		$this->load->model('logs_model');
		$data['logs'] = $this->logs_model->get_logs();
		$admin['username'] = $this->admin['username'];
		$admin['title'] = e('admin_home_title');
		$admin['body'] = $this->load->view('admin/logs/home', $data, TRUE);
		$this->load->view('admin', $admin);
		}
		else {

		}
	}

    function download()
	{
        $this->load->model('logs_model');
		$data['logs'] = $this->logs_model->get_all_logs();
        $timestamp = date('Y-m-d_h:i:s');
		
			$header = 'Admin ID,Voter ID,Block ID,Description,IP Address,Timestamp';
			$data[] = $header;
			foreach ($data['logs'] as $d)
			{
				$row = $d['adminid'] . ',' . $d['voterid'] . ',' . $d['blockid'] . ',' . $d['description'] . ',' . $d['ipaddress'] . ',' . $d['timestamp'];
				$data[] = $row;
			}
			$data = implode("\r\n", $data);
            
			force_download('halalan-logs-'.$timestamp.'.csv', $data);
		
	}


}


