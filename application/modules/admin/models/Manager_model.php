<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Manager_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_users($username, $query = null) {
        //get all resellers under his account
        $resellers    = array();
        $reseller_sql = $this->db->where(array('type' => 'SRSLR', 'username_owner' => $username))->get('users');
        if ($reseller_sql->num_rows() > 0) {
            foreach ($reseller_sql->result() as $reseller) {
                $resellers[] = $reseller->username;
            }
        }
        //get all dealers under his reseller's accounts
        $dealer_sql = $this->db->where_in('username_owner', $resellers)->get('users');
        if ($dealer_sql->num_rows() > 0) {
            foreach ($dealer_sql->result() as $dealers) {
                $resellers[] = $dealers->username;
            }
        }
        //get all users under the manager

        if (!empty($query)) {
            $ids = join("','", $resellers);
			//$sql = $this->db->query("select * from accounts where username IN('" . $ids . "') and (account LIKE '%" . $this->db->escape_like_str($query) . "%' or mac LIKE '%" . $this->db->escape_like_str($query) . "%' or ip LIKE '%" . $this->db->escape_like_str($query) . "%' or full_name LIKE '%" . $this->db->escape_like_str($query) . "%' or phone LIKE '%" . $this->db->escape_like_str($query) . "%')");
			$sql = $this->db->query("(select * from accounts where username IN('" . $ids . "') and (account LIKE '%" . $this->db->escape_like_str($query) . "%' or mac LIKE '%" . $this->db->escape_like_str($query) . "%' or ip LIKE '%" . $this->db->escape_like_str($query) . "%' or full_name LIKE '%" . $this->db->escape_like_str($query) . "%' or phone LIKE '%" . $this->db->escape_like_str($query) . "%')) UNION (select * from accounts where status='" . ACCOUNT_STATUS_OFF . "' and (account LIKE '%" . $this->db->escape_like_str($query) . "%' or mac LIKE '%" . $this->db->escape_like_str($query) . "%' or ip LIKE '%" . $this->db->escape_like_str($query) . "%' or full_name LIKE '%" . $this->db->escape_like_str($query) . "%' or phone LIKE '%" . $this->db->escape_like_str($query) . "%'))");
        } else {
            $this->db->where_in('username', $resellers);
            $sql = $this->db->get('accounts');
        }

		// $sql = $this->db->get('accounts');
		// echo $this->db->last_query(); exit;
		return $sql;
	}

	public function get_all_users_expired($username, $query = NULL)	{

		//get all resellers under his account
		$resellers    = array();
		$reseller_sql = $this->db->where(array('type' => 'SRSLR', 'username_owner' => $username))->get('users');
		if ($reseller_sql->num_rows() > 0) {
			foreach ($reseller_sql->result() as $reseller) {
				$resellers[] = $reseller->username;
			}
		}

		//get all dealers under his reseller's accounts
		$dealer_sql = $this->db->where_in('username_owner', $resellers)->get('users');
		if ($dealer_sql->num_rows() > 0) {
			foreach ($dealer_sql->result() as $dealers) {
				$resellers[] = $dealers->username;
			}
		}

		//get all users under the manager
		$this->db->where('expires <', "CURDATE()", false);
		$this->db->where_in('username', $resellers);

		$sql = $this->db->get('accounts');

		// $sql = $this->db->get('accounts');
		// echo $this->db->last_query(); exit;
		return $sql;

	}

    public function count_resellers($username) {
        $this->db->where('username_owner', $username);
        $count = $this->db->count_all_results('users');
        return $count;
    }

    public function get_all_dealers($username) {
        //get all resellers under his account
        $resellers    = array();
        $reseller_sql = $this->db->where(array('type' => 'SRSLR', 'username_owner' => $username))->get('users');
        if ($reseller_sql->num_rows() > 0) {
            foreach ($reseller_sql->result() as $reseller) {
                $resellers[] = $reseller->username;
            }
        }
        if (!empty($resellers)) { 
            //get all dealers under his reseller's accounts
            $dealer_sql = $this->db->where_in('username_owner', $resellers)->get('users');
        }else{
            $dealer_sql = null;
        }
        //get all users under the manager
        $sql = $dealer_sql;
        return $sql;
    }

    public function get_all() {
        $sql = $this->db->where('type', "MNGR")->get('users');
        return $sql;
    }

    public function get_manager_name($username) {
        //check if user exists on db
        $sql = $this->db->where('account', $username)->get('accounts');
        if ($sql->num_rows() > 0) {
            $user = $sql->row();
            //get dealer or reseller
            $onwer_sql = $this->db->where('username', $user->username)->get('users');
            $owner     = $onwer_sql->row();
            if ($owner->type == "SRSLR") {
                $manager = $this->db->where('username', $owner->username_owner)->get('users')->row();
            } else if ($owner->type == "RSLR") {
                $dealer = $this->db->where('username', $owner->username_owner)->get('users')->row();
                // $reseller = $this->db->where('username', $dealer->username_owner)->get('users')->row();
                $manager = $this->db->where('username', $dealer->username_owner)->get('users')->row();
            }
            $name = $manager->username;
            return $name;
        }
    }

    public function is_myuser($username, $manager_login) {
        $sql = $this->db->where('account', $username)->get('accounts');
        if ($sql->num_rows() > 0) {
            $user = $sql->row();
            //get dealer or reseller
            $onwer_sql = $this->db->where('username', $user->username)->get('users');
            $owner     = $onwer_sql->row();
            if ($onwer_sql->num_rows() == 0) {return false;exit;}
            if ($owner->type == "SRSLR") {
                $manager = $this->db->where('username', $owner->username_owner)->get('users')->row();
            } else if ($owner->type == "RSLR") {
                $reseller = $this->db->where('username', $owner->username_owner)->get('users')->row();
                if (empty($reseller->username_owner)) {
                    $manager = $reseller;
                } else {
                    $manager = $this->db->where('username', $reseller->username_owner)->get('users')->row();

                }
                // $manager = $this->db->where('username', $owner->username_owner)->get('users')->row();
            }
            // print_r($reseller); exit;
            if ($manager->username == $manager_login) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function is_mydealer($username, $manager_login) {
        $sql = $this->db->where('username', $username)->get('users');
        if ($sql->num_rows() > 0) {
            $user     = $sql->row();
            $reseller = $this->db->where('username', $user->username_owner)->get('users')->row();
            // $manager  = $this->db->where('username', $reseller->username_owner)->get('users')->row();
            if ($reseller->username_owner == $manager_login) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

}
/* End of file Manager_model.php */
/* Location: ./application/modules/admin/models/Manager_model.php */
