<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reseller_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function has_dealers($username) {
        /*
         *  Check  if  reseller has dealers
         */
        $sql = $this->db->where('username_owner', $username)->get('users');
        if ($sql->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function count_dealers($username) {
        $this->db->where('username_owner', $username);
        $count = $this->db->count_all_results('users');
        return $count;
    }

    public function count_users($username) {
        $this->db->where('username', $username);
        $count = $this->db->count_all_results('accounts');
        return $count;
    }

    public function has_users($username) {
        $sql = $this->db->where('username', $username)->get('accounts');
        if ($sql->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_all() {
        $sql    = $this->db->where('type', 'SRSLR')->get('users');
        $result = $sql->result();
        return $result;
    }

    public function get($username) {
        $sql    = $this->db->where(array('type' => 'SRSLR', 'username' => $username))->get('users');
        $result = $sql->row();
        return $result;
    }

    public function match_parent($username, $reseller_login) {
        $sql = $this->db->where(array('account' => $username, 'username' => $reseller_login))->get('accounts');
        if ($sql->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function is_myuser($account, $match) {
        $sql = $this->db->where('account', $account)->get('accounts');
        if ($sql->num_rows() == 0) {
            return false;
        } else {
            $user = $sql->row();
            //get dealer
            $dealer_sql = $this->db->where('username', $user->username)->get('users');
            $dealer     = $dealer_sql->row();
            if ($dealer->username_owner == $match) {
                return true;
            } else {
                return false;
            }
        }
    }

}

/* End of file Reseller_model.php */
/* Location: ./application/modules/admin/models/Reseller_model.php */
