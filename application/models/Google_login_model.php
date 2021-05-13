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

class Google_login_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
    
    function Is_already_registered($email)
	{
		$this->db->from('voters');
		$this->db->where('username', $email);
		$query = $this->db->get();
        
        if($query->num_rows() > 0)
        {
            return true; //return $query->row_array();
        }
        else
        {
            return false;
        }
                         
        
    function Update_user_data($data, $id)
    {
        
    }
                         
		
	}

}

/* End of file abmin.php */
/* Location: ./application/models/abmin.php */