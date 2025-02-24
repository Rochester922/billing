<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function has_users($username) {
        $sql = $this->db->where('username', $username)->get('accounts');
        if ($sql->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_last_login($username) {
        $sql = $this->db->where('username', $username)->get('users');
        $user = $sql->row();
        return $user->last_login_time;
    }

    public function count_users($username) {
        $this->db->where('username', $username);
        $count = $this->db->count_all_results('accounts');
        return $count;
    }

    public function get_all($reseller = null) {
        if ($reseller == null or empty($reseller)) {
            $sql = $this->db->where('type', 'RSLR')->get('users');
        } else {
            $sql = $this->db->where('type', 'RSLR')->where('username_owner', $reseller)->get('users');
        }
        return $sql->result();
    }

    public function is_dealer($username) {
        $sql = $this->db->where(array('username' => $username, 'type' => 'RSLR'))->get('users');
        if ($sql->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_dealer($username) {
		$sql    = $this->db->where(array('username' => $username, 'type' => 'RSLR'))->get('users');
        $dealer = $sql->row();
        return $dealer;
    }

    public function get_reseller($username) {
        $sql      = $this->db->where(array('username' => $username, 'type' => 'SRSLR'))->get('users');
        $reseller = $sql->row();
        $name     = $reseller->username;
        return $name;
    }

    public function get_manager($username) {
        $sql = $this->db->where(array('username' => $username, 'type' => 'SRSLR'))->get('users');
        if ($sql->num_rows() == 0) {
            return '';
        } else {
            $reseller = $sql->row();
            $name     = $reseller->username_owner;
            return $name;
        }
    }

	public function tickets_enable($username) {
		$Sql = $this->db->where(array('username'=>$username,'type'=>'RSLR'))->get('users');
		if ($Sql->num_rows() == 0) {
			return "";
		} else {
			$data = $Sql->row();
			return $data->tickets_enable;
		}
	}

	public function dealer_id($username) {
		$Sql = $this->db->where(array('username'=>$username,'type'=>'RSLR'))->get('users');
		if ($Sql->num_rows() == 0) {
			return "";
		} else {
			$data = $Sql->row();
			return $data->id;
		}
	}

    public function get_user_id($username) {
        $sql    = $this->db->where(array('username' => $username))->get('users');
        $dealer = $sql->row();
        return $dealer;
    }

}
/* End of file Dealer_model.php */
/* Location: ./application/modules/admin/models/Dealer_model.php */
