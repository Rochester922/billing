<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends ManagerController {
    protected $module_name = 'Change Password';

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->data['title']  = $this->module_name;
        $this->data['module'] = $this->module_name;
        $this->form_validation->set_rules('old_password', 'Current Password', 'trim|required|min_length[3]|max_length[100]|callback_check_pwd');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[4]|max_length[12]');
        $this->form_validation->set_rules('new_confirm_passsword', 'Retype New Password', 'trim|required|min_length[4]|max_length[12]|matches[new_password]');
        if ($this->form_validation->run() === true) {
            $this->db->set('password', $this->input->post('new_confirm_passsword'));
            $this->db->where('username', $this->user['username']);
            if ($this->db->update('users')) {
                $this->session->unset_userdata('auth_info');
                redirect('login', 'refresh');
            }
        } else {
            $this->render('accounts/profile');
        }
    }

    public function check_pwd($password) {
        $username = $this->user['username'];
        $sql      = $this->db->where('username', $username)->get('users');
        $user     = $sql->row();
        if ($user->password === $password) {
            return true;
        } else {
            $this->form_validation->set_message('check_pwd', 'Current Password does not match!');
            return false;
        }
    }
    
}
/* End of file Profile.php */
/* Location: ./application/modules/admin/controllers/Profile.php */
