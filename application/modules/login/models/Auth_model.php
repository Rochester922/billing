<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Auth_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
    }

    public function attempt($data = array()) {
        $sql = $this->db->where($data)->get('users');
        if ($sql->num_rows() == 1) {
            $user = $sql->row();
            $options = array(
                'is_loggged' => TRUE,
                'name'       => $user->name,
                'username'   => $user->username,
                'type'       => $user->type,
            );
            $this->session->set_userdata('auth_info', $options);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function role()
    {
        $user = $this->session->userdata('auth_info');
        if (empty($user['username'])) {
            return 'Guest';
        } else {
            $sql       = $this->db->where('username', $user['username'])->get('users');
            $role_data = $sql->row();
            return $role_data->type;
        }
    }

    public function verify()
    {
        $logged_session = $this->session->userdata('auth_info');
        if ($logged_session['is_loggged'] == true && !empty($logged_session['username'])) {
            return true;
        } else {
            redirect('login', 'refresh');
        }
    }

    public function redirect($role)
    {
        if ($role == 'ROOT') {
            $path = 'admin/dashboard';
        } else if ($role == 'RSLR') {
            $path = 'dealer/users/index';
        } else if ($role == 'SRSLR') {
            $path = 'reseller/dealers/index';
        } else if ($role == 'MNGR') {
            $path = 'manager/resellers/index';
        } else {
            // redirect('login','refresh');
            // exit;
            $path = 'Guest';
        }
        return $path;
        // redirect($path.'/dashboard','refresh');
    }

    public function update_ip($account)
    {
        
        $sql  = $this->db->where('username', $account)->get('users');
        $user = $sql->row();
        $last_login = $user->current_login_time;
        //update last login
        $this->db->set('last_login_time',$last_login);
        $this->db->where('username', $account);
        $this->db->update('users');
        //update current login
        $this->db->set('current_login_time','NOW()',false);
        $this->db->where('username', $account);
        $this->db->update('users');
    }

}
/* End of file Auth_model.php */
/* Location: ./application/modules/auth/models/Auth_model.php */
