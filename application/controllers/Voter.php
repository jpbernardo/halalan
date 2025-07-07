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

class Voter extends CI_Controller {

	var $voter;
	var $settings;

	function __construct()
	{
		parent::__construct();
		$this->voter = $this->session->userdata('voter');
		if ( ! $this->voter)
		{
			$error[] = e('common_unauthorized');
			$this->session->set_flashdata('error', $error);
			redirect('gate/voter');
		}
		$this->settings = $this->config->item('halalan');
	}

	function index()
	{
        $this->_no_cache();
		$election_ids = array();
		$chosen = $this->Block_Election_Position->select_all_by_block_id($this->voter['block_id']);
		foreach ($chosen as $c)
		{
			$election_ids[] = $c['election_id'];
		}
		$voted = array();
		$tmp = $this->Voted->select_all_by_voter_id($this->voter['id']);
		foreach ($tmp as $t)
		{
			$voted[] = $t['election_id'];
		}
		$data['election_ids'] = $election_ids;
		$data['elections'] = $this->Election->select_all_by_level();
		$data['voted'] = $voted;
		$voter['index'] = TRUE; // flag to determine what to show in the main voter template
		$voter['username'] = $this->voter['username'];
		$voter['title'] = e('voter_index_title');
		$voter['body'] = $this->load->view('voter/index', $data, TRUE);
		$this->load->view('voter', $voter);
	}

	function vote()
	{
		$this->_no_cache();
		$rules = array('position_count' => 0, 'candidate_count' => array()); // used in checking in do_vote
		$array = array();
		$chosen = $this->Block_Election_Position->select_all_by_block_id($this->voter['block_id']);
		foreach ($chosen as $c)
		{
			$array[$c['election_id']][] = $c['position_id'];
		}
		$elections = $this->Election->select_all_by_ids(array_keys($array));
		$elections = $this->_filter($elections);
		if (empty($elections))
		{
			redirect('voter/index');
		}
		foreach ($elections as $key1 => $election)
		{
			$positions = $this->Position->select_all_by_ids($array[$election['id']]);
			foreach ($positions as $key2 => $position)
			{
				$candidates = $this->Candidate->select_all_by_election_id_and_position_id($election['id'], $position['id']);
				foreach ($candidates as $key3 => $candidate)
				{
					$candidates[$key3]['party'] = $this->Party->select($candidate['party_id']);
				}
				$positions[$key2]['candidates'] = $candidates;
				if ( ! empty($candidates))
				{
					$rules['position_count']++;
					$rules['candidate_max'][$election['id'] . '|' . $position['id']] = $position['maximum'];
				}
			}
			$elections[$key1]['positions'] = $positions;
		}
		
		/* for USC column by party */
		define('USC_ID', 1);
		$usc_positions = $this->Position->select_all_by_election_id(USC_ID);
		foreach ($usc_positions as $key1 => $usc_position)
		{
			$usc_parties = $this->Candidate->select_parties_by_election_id_and_position_id(USC_ID, $usc_position['id']);
			foreach ($usc_parties as $key2 => $usc_party)
			{
				$usc_candidates = $this->Candidate->select_all_by_election_id_and_position_id_and_party_id(USC_ID, $usc_position['id'], $usc_party['id']);
				$usc_parties[$key2]['candidates'] = $usc_candidates;
			}
			$usc_positions[$key1]['parties'] = $usc_parties;
		}
		$data['usc_positions'] = $usc_positions;

		$this->session->set_userdata('rules', $rules);
		$data['elections'] = $elections;
		if ($votes = $this->session->userdata('votes'))
		{
			$data['votes'] = $votes;
		}
		$data['settings'] = $this->settings;
		$voter['username'] = $this->voter['username'];
		$voter['title'] = e('voter_vote_title');
		$voter['body'] = $this->load->view('voter/vote', $data, TRUE);
		$this->load->view('voter', $voter);
	}

	function do_vote()
	{
		$error = array();
		$votes = $this->input->post('votes');
		// check if there are selected candidates
		if (empty($votes))
		{
			$error[] = e('voter_vote_no_selected');
		}
		else
		{
			$rules = $this->session->userdata('rules');
			// check if all positions have selected candidates
			$count = 0;
			foreach ($votes as $election_id => $positions)
			{
				$count += count(array_values($positions));
			}
			if ($rules['position_count'] != $count)
			{
				$error[] = e('voter_vote_not_all_selected');
			}
			else
			{
				foreach ($votes as $election_id => $positions)
				{
					// check if the number of selected candidates does not exceed the maximum allowed for each position
					foreach ($positions as $position_id => $candidate_ids)
					{
						if ($rules['candidate_max'][$election_id . '|' . $position_id] < count($candidate_ids))
						{
							$error[] = e('voter_vote_maximum');
						}
						else
						{
							// check if abstain is selected with other candidates
							if (in_array('abstain', $candidate_ids) && count($candidate_ids) > 1)
							{
								$error[] = e('voter_vote_abstain_and_others');
							}
						}
					}
				}
			}
		}
		// save the votes in session
		$this->session->set_userdata('votes', $votes);
		if (empty($error))
		{
			redirect('voter/verify');
		}
		else
		{
			$this->session->set_flashdata('messages', array_merge(array('negative'), $error));
			redirect('voter/vote');
		}
	}

	function verify()
	{
		$this->_no_cache();
		$votes = $this->session->userdata('votes');
		if (empty($votes))
		{
			redirect('voter/vote');
		}
		$data['votes'] = $votes;
		$array = array();
		$chosen = $this->Block_Election_Position->select_all_by_block_id($this->voter['block_id']);
		foreach ($chosen as $c)
		{
			$array[$c['election_id']][] = $c['position_id'];
		}
		$elections = $this->Election->select_all_by_ids(array_keys($array));
		$elections = $this->_filter($elections);
		if (empty($elections))
		{
			redirect('voter/index');
		}
		foreach ($elections as $key1 => $election)
		{
			$positions = $this->Position->select_all_by_ids($array[$election['id']]);
			foreach ($positions as $key2 => $position)
			{
				$candidates = $this->Candidate->select_all_by_election_id_and_position_id($election['id'], $position['id']);
				foreach ($candidates as $key3 => $candidate)
				{
					$candidates[$key3]['party'] = $this->Party->select($candidate['party_id']);
				}
				$positions[$key2]['candidates'] = $candidates;
			}
			$elections[$key1]['positions'] = $positions;
		}
		/* for USC column by party */
		define('USC_ID', 1);
		$usc_positions = $this->Position->select_all_by_election_id(USC_ID);
		foreach ($usc_positions as $key1 => $usc_position)
		{
			$usc_parties = $this->Candidate->select_parties_by_election_id_and_position_id(USC_ID, $usc_position['id']);
			foreach ($usc_parties as $key2 => $usc_party)
			{
				$usc_candidates = $this->Candidate->select_all_by_election_id_and_position_id_and_party_id(USC_ID, $usc_position['id'], $usc_party['id']);
				$usc_parties[$key2]['candidates'] = $usc_candidates;
			}
			$usc_positions[$key1]['parties'] = $usc_parties;
		}
		$data['usc_positions'] = $usc_positions;
		$data['elections'] = $elections;
		if ($this->settings['captcha'])
		{
			$this->load->helper('captcha');
			$vals = array(
					'word' => random_string('alnum', $this->settings['captcha_length']),
					'img_path' => './public/captcha/',
					'img_url' => base_url('public/captcha') . '/',
					'font_path' => './public/fonts/Vera.ttf',
					'img_width' => 150,
					'img_height' => 60,
					'expiration' => 1800
				);
			$captcha = create_captcha($vals);
			$data['captcha'] = $captcha;
			// insert into DB
			$kaptcha = array(
					'captcha_time' => $captcha['time'],
					'ip_address' => $this->input->ip_address(),
					'word' => $captcha['word']
				);
			$query = $this->db->insert_string('captchas', $kaptcha);
			$this->db->query($query);
		}
		$data['settings'] = $this->settings;
		$voter['username'] = $this->voter['username'];
		$voter['title'] = e('voter_confirm_vote_title');
		$voter['body'] = $this->load->view('voter/confirm_vote', $data, TRUE);
		$this->load->view('voter', $voter);
	}

	function do_verify()
	{
		$this->load->library('form_validation');
		if($this->settings['captcha'])
		{
			$this->form_validation->set_rules('captcha', 'Captcha', 'required|alpha_numeric|exact_length['.$this->settings["captcha_length"].']');	
		}
		else{
			$this->form_validation->set_rules('captcha', 'Captcha','min_length[0]');
		}

		if ($this->form_validation->run())
		{
			$error = array();
			if ($this->settings['captcha'])
			{
				$captcha = $this->input->post('captcha', TRUE);

				if (empty($captcha))
				{
					$error[] = e('voter_confirm_vote_no_captcha');
				}
				else
				{
					// from CI doc
					// First, delete old captchas
					$expiration = time() - 1800; // 30-minute limit
					$this->db->query('DELETE FROM captchas WHERE captcha_time < ' . $expiration);
					// Then see if a captcha exists:
					$sql = 'SELECT COUNT(*) AS count FROM captchas WHERE word = ? AND ip_address = ? AND captcha_time > ?';
                    if(filter_var($this->input->ip_address(), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)  || filter_var($this->input->ip_address(), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
                    {
                        $captchaip = $this->input->ip_address();
                    }
                    else
                    {
                        $captchaip = '0.0.0.0';
                    }
                    $binds = array($captcha, $captchaip, $expiration);
					$query = $this->db->query($sql, $binds);
					$row = $query->row();
					if ($row->count == 0)
					{
						$error[] = e('voter_confirm_vote_not_captcha');
					}
				}
			}
			if ($this->settings['pin'])
			{
				$pin = $this->input->post('pin');
				if (empty($pin))
				{
					$error[] = e('voter_confirm_vote_no_pin');
				}
				else
				{
					if (sha1($pin) != $this->voter['pin'])
						$error[] = e('voter_confirm_vote_not_pin');
				}
			}
			if (empty($error))
			{
				$voter_id = $this->voter['id'];
				$timestamp = date("Y-m-d H:i:s");
				$votes = $this->session->userdata('votes');
				foreach ($votes as $election_id=>$positions)
				{
					foreach ($positions as $position_id=>$candidate_ids)
					{
						$abstain = FALSE;
						foreach ($candidate_ids as $candidate_id)
						{
							if ($candidate_id == 'abstain')
							{
								$abstain = TRUE;
							}
							else
							{
								$this->Vote->insert(compact('candidate_id', 'voter_id', 'timestamp'));
							}
						}
						if ($abstain)
						{
							$this->Abstain->insert(compact('election_id', 'position_id', 'voter_id', 'timestamp'));
						}
					}
					$this->Voted->insert(compact('election_id', 'voter_id', 'timestamp'));
				}
				$this->session->unset_userdata('votes');
				if ($this->settings['generate_image_trail'])
				{
					$this->_generate_image_trail($votes);
				}
				redirect('voter/logout');
			}
			else
			{
				$this->session->set_flashdata('messages', array_merge(array('negative'), $error));
				redirect('voter/verify');
			}
		}
		else{
			$this->session->set_flashdata('messages', array('negative', e('voter_confirm_vote_not_captcha')));
			redirect('voter/verify');
		}
	}

	function logout()
	{
		$this->_no_cache();
		$this->Boter->update(array('logout' => date('Y-m-d H:i:s')), $this->voter['id']);
		setcookie('halalan_abstain', '', time() - 3600, '/'); // used in abstain alert
		setcookie('selected_election', '', time() - 3600, '/'); // used in remembering selected election
		setcookie('selected_position', '', time() - 3600, '/'); // used in remembering selected position
		setcookie('selected_block', '', time() - 3600, '/'); // used in remembering selected block
		$this->session->sess_destroy();
		$voter['title'] = e('voter_logout_title');
		$voter['meta'] = '<meta http-equiv="refresh" content="5;URL=' . base_url() . '" />';
		$voter['body'] = $this->load->view('voter/logout', '', TRUE);
		$this->load->view('voter', $voter);
	}

	function votes_general($case,$election_id = 1)
	{
		// if url is not votes/view or votes/print
		if ( ! in_array($case, array('view', 'print', 'download')))
		{
			redirect('voter/index');
		}

		// get all elections and positions assigned to the voter
		$array = array();
		$chosen = $this->Block_Election_Position->select_all_by_block_id($this->voter['block_id']);
		foreach ($chosen as $c)
		{
			$array[$c['election_id']][] = $c['position_id'];
		}
		// check if election id is assigned to the voter
		if ( ! array_key_exists($election_id, $array))
		{
			redirect('voter/index');
		}
		// get all elections voted in by the voter
		$voted = array();
		$tmp = $this->Voted->select_all_by_voter_id($this->voter['id']);
		foreach ($tmp as $t)
		{
			$voted[] = $t['election_id'];
		}
		// check if election id has been voted in
		if ( ! in_array($election_id, $voted))
		{
			redirect('voter/index');
		}
		$election = $this->Election->select($election_id);
		// check if election id exists
		if (empty($election))
		{
			redirect('voter/index');
		}
		// get votes for viewing and printing
		if (in_array($case, array('view', 'print')))
		{
			// get all voted candidate ids
			$votes = $this->Vote->select_all_by_voter_id($this->voter['id']);
			$candidate_ids = array();
			foreach ($votes as $vote)
			{
				$candidate_ids[] = $vote['candidate_id'];
			}
			// get the positions assigned to the voter for the selected election
			$positions = $this->Position->select_all_by_ids($array[$election['id']]);
			foreach ($positions as $key2=>$position)
			{
				$count = 0;
				$candidates = $this->Candidate->select_all_by_election_id_and_position_id($election['id'], $position['id']);
				foreach ($candidates as $key3=>$candidate)
				{
					if (in_array($candidate['id'], $candidate_ids))
					{
						$candidates[$key3]['voted'] = TRUE;
					}
					else
					{
						$candidates[$key3]['voted'] = FALSE;
						$count++;
					}
					$candidates[$key3]['party'] = $this->Party->select($candidate['party_id']);
				}
				if ($count == count($candidates))
				{
					$positions[$key2]['abstains'] = TRUE;
				}
				else
				{
					$positions[$key2]['abstains'] = FALSE;
				}
				$positions[$key2]['candidates'] = $candidates;
			}
			$election['positions'] = $positions;
			$data['election'] = $election;
		}
		if ($case == 'view')
		{
			$data['settings'] = $this->settings;
			define('USC_ID', 1);
			$usc_positions = $this->Position->select_all_by_election_id(USC_ID);
			foreach ($usc_positions as $key1 => $usc_position)
			{
				$count = 0;
				$total_candidates = 0;
				$usc_parties = $this->Candidate->select_parties_by_election_id_and_position_id(USC_ID, $usc_position['id']);
				foreach ($usc_parties as $key2 => $usc_party)
				{
					$usc_candidates = $this->Candidate->select_all_by_election_id_and_position_id_and_party_id(USC_ID, $usc_position['id'], $usc_party['id']);
					foreach ($usc_candidates as $key3 => $usc_candidate)
					{
						if (in_array($usc_candidate['id'], $candidate_ids))
						{
							$usc_candidates[$key3]['voted'] = TRUE;
						}
						else
						{
							$usc_candidates[$key3]['voted'] = FALSE;
							$count++;
						}
						$total_candidates++;
					}
					$usc_parties[$key2]['candidates'] = $usc_candidates;
				}
				if ($count == $total_candidates)
				{
					$usc_positions[$key1]['abstains'] = TRUE;
				}
				else
				{
					$usc_positions[$key1]['abstains'] = FALSE;
				}
				$usc_positions[$key1]['parties'] = $usc_parties;
			}
			$data['usc_positions'] = $usc_positions;
			$voter['view_votes'] = TRUE; // flag to determine what to show in the main voter template
			$voter['username'] = $this->voter['username'];
			$voter['title'] = e('voter_votes_title');
			$voter['body'] = $this->load->view('voter/votes_general', $data, TRUE);
			$this->load->view('voter', $voter);
		}
		else if ($case == 'print')
		{
			$data['voter'] = $this->voter;
			$this->load->view('voter/print_votes', $data);
		}
		else if ($case == 'download')
		{
			if ( ! $this->settings['generate_image_trail'])
			{
				$this->session->set_flashdata('messages', array('negative', e('voter_votes_image_trail_disabled')));
				redirect('voter/index');
			}
			$voted = $this->Voted->select($election_id, $this->voter['id']);
			$path = $this->settings['image_trail_path'] . $election_id . '/';
			$name = $election_id . '_' . $voted['image_trail_hash'] . '_' . $this->voter['id'] . '.png';
			$contents = file_get_contents($path . $name);
			if ($contents == FALSE)
			{
				$this->session->set_flashdata('messages', array('negative', e('voter_votes_image_trail_not_found')));
				redirect('voter/index');
			}
			force_download($name, $contents);
		}
	}

	function votes($case, $election_id = 0)
	{
		$this->_no_cache();
		// if url is not votes/view or votes/print
		if ( ! in_array($case, array('view', 'print', 'download')))
		{
			redirect('voter/index');
		}
		
		if ($election_id == 1) {
		    redirect('voter/votes_general/'.$case.'/'.$election_id);
		}
		
		// get all elections and positions assigned to the voter
		$array = array();
		$chosen = $this->Block_Election_Position->select_all_by_block_id($this->voter['block_id']);
		foreach ($chosen as $c)
		{
			$array[$c['election_id']][] = $c['position_id'];
		}
		// check if election id is assigned to the voter
		if ( ! array_key_exists($election_id, $array))
		{
			redirect('voter/index');
		}
		// get all elections voted in by the voter
		$voted = array();
		$tmp = $this->Voted->select_all_by_voter_id($this->voter['id']);
		foreach ($tmp as $t)
		{
			$voted[] = $t['election_id'];
		}
		// check if election id has been voted in
		if ( ! in_array($election_id, $voted))
		{
			redirect('voter/index');
		}
		$election = $this->Election->select($election_id);
		// check if election id exists
		if (empty($election))
		{
			redirect('voter/index');
		}
		// get votes for viewing and printing
		if (in_array($case, array('view', 'print')))
		{
			// get all voted candidate ids
			$votes = $this->Vote->select_all_by_voter_id($this->voter['id']);
			$candidate_ids = array();
			foreach ($votes as $vote)
			{
				$candidate_ids[] = $vote['candidate_id'];
			}
			// get the positions assigned to the voter for the selected election
			$positions = $this->Position->select_all_by_ids($array[$election['id']]);
			foreach ($positions as $key2 => $position)
			{
				$count = 0;
				$candidates = $this->Candidate->select_all_by_election_id_and_position_id($election['id'], $position['id']);
				foreach ($candidates as $key3 => $candidate)
				{
					if (in_array($candidate['id'], $candidate_ids))
					{
						$candidates[$key3]['voted'] = TRUE;
					}
					else
					{
						$candidates[$key3]['voted'] = FALSE;
						$count++;
					}
					$candidates[$key3]['party'] = $this->Party->select($candidate['party_id']);
				}
				if ($count == count($candidates))
				{
					$positions[$key2]['abstains'] = TRUE;
				}
				else
				{
					$positions[$key2]['abstains'] = FALSE;
				}
				$positions[$key2]['candidates'] = $candidates;
			}
			$election['positions'] = $positions;
			$data['election'] = $election;
		}
		if ($case == 'view')
		{
			$data['settings'] = $this->settings;
			$voter['view_votes'] = TRUE; // flag to determine what to show in the main voter template
			$voter['username'] = $this->voter['username'];
			$voter['title'] = e('voter_votes_title');
			$voter['body'] = $this->load->view('voter/votes', $data, TRUE);
			$this->load->view('voter', $voter);
		}
		else if ($case == 'print')
		{
			$data['voter'] = $this->voter;
			$this->load->view('voter/print_votes', $data);
		}
		else if ($case == 'download')
		{
			if ( ! $this->settings['generate_image_trail'])
			{
				$this->session->set_flashdata('messages', array('negative', e('voter_votes_image_trail_disabled')));
				redirect('voter/index');
			}
			$voted = $this->Voted->select($election_id, $this->voter['id']);
			$path = $this->settings['image_trail_path'] . $election_id . '/';
			$name = $election_id . '_' . $voted['image_trail_hash'] . '_' . $this->voter['id'] . '.png';
			$contents = file_get_contents($path . $name);
			if ($contents == FALSE)
			{
				$this->session->set_flashdata('messages', array('negative', e('voter_votes_image_trail_not_found')));
				redirect('voter/index');
			}
			force_download($name, $contents);
		}
	}

	// some code taken from CI's captcha plugin
	function _generate_image_trail($votes)
	{
		$font = './public/fonts/Vera.ttf';
		foreach ($votes as $election_id => $positions)
		{
			$text = array();
			$election = $this->Election->select($election_id);
			$text[] = array('type' => 'break');
			$text[] = array('type' => 'election', 'text' => $election['election']);
			$text[] = array('type' => 'break');
			$position_max = '';
			$candidate_max = '';
			$height = 10 + 16 + 10; // break + 16 for election + break
			foreach ($positions as $position_id => $candidate_ids)
			{
				$position = $this->Position->select($position_id);
				$text[] = array('type' => 'break');
				$text[] = array('type' => 'break');
				$text[] = array('type' => 'position', 'text' => $position['position'] . ' (' . $position['maximum'] . ')');
				$text[] = array('type' => 'break');
				$height += 10 + 10 + 14 + 10; // break + break + position + break
				if (strlen($position['position'] . ' (' . $position['maximum'] . ')') > strlen($position_max))
				{
					$position_max = $position['position'] . ' (' . $position['maximum'] . ')';
				}
				$abstain = FALSE;
				foreach ($candidate_ids as $candidate_id)
				{
					if ($candidate_id == 'abstain')
					{
						$abstain = TRUE;
					}
					else
					{
						$candidate = $this->Candidate->select($candidate_id);
						$name = $candidate['first_name'];
						if ( ! empty($candidate['alias']))
						{
							$name .= ' "' . $candidate['alias'] . '"';
						}
						$name .= ' ' . $candidate['last_name'];
						$party = $this->Party->select($candidate['party_id']);
						if ( ! empty($party))
						{
							$name .= ', ';
							if (empty($party['alias']))
							{
								$name .= $party['party'];
							}
							else
							{
								$name .= $party['alias'];
							}
						}
						$text[] = array('type' => 'candidate', 'text' => $name);
						$text[] = array('type' => 'break');
						$height += 12 + 10; // 12 for candidate + break
						if (strlen($name) > strlen($candidate_max))
						{
							$candidate_max = $name;
						}
					}
				}
				if ($abstain)
				{
					$text[] = array('type' => 'candidate', 'text' => 'ABSTAIN');
					$text[] = array('type' => 'break');
					$height += 12 + 10; // 12 for candidate + break
				}
			}
			$bbox1 = imagettfbbox(16, 0, $font, $election['election']);
			$bbox2 = imagettfbbox(14, 0, $font, $position_max);
			$bbox3 = imagettfbbox(12, 0, $font, $candidate_max);
			$img_width = 20 + max($bbox1[2], $bbox2[2], $bbox3[2]); // lower right corner, X position
			if ($img_width < 500)
			{
				$img_width = 500;
			}
			$img_height = 20 + $height;
			if ($img_height < 500)
			{
				$img_height = 500;
			}
			// PHP.net recommends imagecreatetruecolor(), but it isn't always available
			if (function_exists('imagecreatetruecolor'))
			{
				$im = imagecreatetruecolor($img_width, $img_height);
			}
			else
			{
				$im = imagecreate($img_width, $img_height);
			}
			$bg_color = imagecolorallocate ($im, 255, 255, 255);
			$border_color = imagecolorallocate ($im, 0, 0, 0);
			$text_color = imagecolorallocate ($im, 0, 0, 0);
			imagefilledrectangle($im, 0, 0, $img_width, $img_height, $bg_color);
			$y = 0;
			foreach ($text as $t)
			{
				if ($t['type'] == 'election')
				{
					$y += 16;
					imagettftext($im, 16, 0, 10, $y, $text_color, $font, $t['text']);
				}
				else if ($t['type'] == 'position')
				{
					$y += 14;
					imagettftext($im, 14, 0, 10, $y, $text_color, $font, $t['text']);
				}
				else if ($t['type'] == 'candidate')
				{
					$y += 12;
					imagettftext($im, 12, 0, 10, $y, $text_color, $font, $t['text']);
				}
				else
				{
					$y += 10;
					imagettftext($im, 10, 0, 10, $y, $text_color, $font, '');
				}
			}
			imagettftext($im, 5, 0, 10, $img_height - 10, $text_color, $font, 'Generated on ' . date('Y-m-d H:i:s'));
			imagerectangle($im, 0, 0, $img_width-1, $img_height-1, $border_color);
			$path = $this->settings['image_trail_path'] . $election_id . '/';
			mkdir($path);
			$name = $election_id . '_' . $this->voter['id'] . '.png';
			imagepng($im, $path . $name);
			imagedestroy($im);
			$config['source_image'] = $path . $name;
			$config['wm_overlay_path'] = './public/images/logo_small.png';
			$config['wm_type'] = 'overlay';
			$config['wm_vrt_alignment'] = 'bottom';
			$config['wm_hor_alignment'] = 'right';
			$config['wm_opacity'] = 25;
			$this->image_lib->initialize($config);
			$this->image_lib->watermark();
			$hash = sha1_file($path . $name);
			$voted['image_trail_hash'] = $hash;
			$this->Voted->update($voted, $election_id, $this->voter['id']);
			rename($path . $name, $path . $election_id . '_' . $hash . '_' . $this->voter['id'] . '.png');
		}
	}

	// get only running elections
	function _filter($elections)
	{
		$parents = array();
		$children = array();
		$running = array();
		foreach ($elections as $election)
		{
			if ($election['parent_id'] == 0)
			{
				$parents[] = $election;
			}
			else
			{
				$children[] = $election;
			}
		}
		foreach ($parents as $parent)
		{
			if ($parent['status'])
			{
				$running[$parent['id']]['parent'] = $parent;
			}
		}
		foreach ($children as $child)
		{
			if ($child['status'])
			{
				$running[$child['parent_id']][] = $child;
			}
			else
			{
				// don't show the parent election if the child election is not running
				unset($running[$child['parent_id']]);
			}
		}
		// flatten the array and remove elections that have been voted in by the voter
		// the format of $running is like that so we can put the children elections after their parents
		$voted = $this->Voted->select_all_by_voter_id($this->voter['id']);
		$election_ids = array();
		foreach ($voted as $v)
		{
			$election_ids[] = $v['election_id'];
		}
		$tmp = array();
		foreach ($running as $r)
		{
			if (isset($r['parent']))
			{
				if ( ! in_array($r['parent']['id'], $election_ids))
				{
					$tmp[] = $r['parent'];
				}
				unset($r['parent']);
			}
			foreach ($r as $value)
			{
				if ( ! in_array($value['id'], $election_ids))
				{
					$tmp[] = $value;
				}
			}
		}
		return $tmp;
	}

	function _no_cache()
	{
		// from http://stackoverflow.com/questions/49547/making-sure-a-web-page-is-not-cached-across-all-browsers
		header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
		header('Pragma: no-cache'); // HTTP 1.0.
		header('Expires: 0'); // Proxies.
	}

}

/* End of file voter.php */
/* Location: ./system/application/controllers/voter.php */
