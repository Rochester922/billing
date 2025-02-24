<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        //Do your magic here
    }

    public function users() {
        $sql = $this->db->count_all_results('accounts');
        return $sql;
    }

    public function active_users() {
        $this->db->where('status', ACCOUNT_STATUS_ON);
        $sql = $this->db->count_all_results('accounts');
        return $sql;
    }

    public function expired_users() {
        $this->db->where(array('expires !=' => NULL, 'expires <' => date('Y-m-d H:i:s')));
        $sql = $this->db->count_all_results('accounts');
        return $sql;
    }

    public function dealers() {
        $this->db->where('type', "RSLR");
        $sql = $this->db->count_all_results('users');
        return $sql;
    }

    public function resellers() {
        $this->db->where('type', "SRSLR");
        $sql = $this->db->count_all_results('users');
        return $sql;
    }

    public function managers() {
        $this->db->where('type', "MNGR");
        $sql = $this->db->count_all_results('users');
        return $sql;
    }

    /*
    public function get_all_stalker_users() {
        $testdb = $this->load->database('stalker', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.
        $users = $testdb->select('login, fname, created, expire_billing_date')->get('users');
        return $users;
    } 
    */    

}

/* End of file Dashboard_model.php */
/* Location: ./application/modules/admin/models/Dashboard_model.php */
