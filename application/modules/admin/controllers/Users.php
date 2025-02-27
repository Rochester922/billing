<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends AdminController {
    protected $module_name = 'users';

    public function __construct() {
        parent::__construct();
        $this->load->model('dashboard_model');
        $this->load->model('deduction_model');
        $this->load->helper('deduction_helper');
    }

    public function index() {
        $this->data['title']         = 'Manage ' . $this->module_name;
        $this->data['module']        = $this->module_name;
        $query                       = trim($this->input->get('query'));
        $status                      = trim($this->input->get('status'));
        $this->data['total_users']   = $this->dashboard_model->users();
        $this->data['active_users']  = $this->dashboard_model->active_users();
        $this->data['expired_users'] = $this->dashboard_model->expired_users();
        $status                      = $this->input->get('status');
        if (!empty($status) && $status == 'active') {
            $sql = $this->db->where('status', ACCOUNT_STATUS_ON)->get('accounts');
        }
        if (!empty($status) && $status == 'inactive') {
           $sql = $this->db->where('status', ACCOUNT_STATUS_OFF)->get('accounts');
        }
        if (!empty($status) && $status == 'expired') {
            $sql = $this->db->where(array('expires !=' => '0000-00-00 00:00:00', 'expires <' => date('Y-m-d H:i:s')))->get('accounts');
        }
         /*if (!empty($query)) {
            $this->db->or_where('mac', $query)->or_where('account', $query)->or_where('phone', $query)->or_where('note', $query)->get('accounts');
        }*/
        if (!empty($query)) {
            $sql = $this->db->query("select * from accounts where account LIKE '%" . $this->db->escape_like_str($query) . "%' or mac LIKE '%" . $this->db->escape_like_str($query) . "%' or ip LIKE '%" . $this->db->escape_like_str($query) . "%' or full_name LIKE '%" . $this->db->escape_like_str($query) . "%' or phone LIKE '%" . $this->db->escape_like_str($query) . "%'");
        } else {
            // $this->db->where_in('username', $resellers);
            $sql = '';
        }
        $this->data['sql'] = $sql;
        $this->render('users/index');
    }
   
    public function add() {
        $this->data['title']     = 'Add ' . $this->module_name;
        $this->data['module']    = $this->module_name;
        $this->data['resellers'] = $this->reseller_model->get_all();
        $this->data['deduction'] = arrayDataCreditDeduction();
        $this->form_validation->set_rules('name', 'Name', 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('username', 'Username', 'trim|strtolower|alpha_numeric|required|valid_login');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[100]');
        $this->form_validation->set_rules('mac', 'MAC', 'trim|strtoupper|valid_mac|is_unique_mac');
        //$this->form_validation->set_rules('validity', 'Validity', 'trim|required|numeric|max_length[2]|callback_check_credits');
        $this->form_validation->set_rules('validity', 'Validity', 'trim|required|callback_check_validity');
        $this->form_validation->set_rules('reseller', 'Reseller', 'trim|required');
        if ($this->form_validation->run() == true) {
            $dealer   = $this->input->post('dealer');
            $reseller = $this->input->post('reseller');
            $validity = $this->input->post('validity');
            $username = (empty($dealer)) ? $reseller : $dealer;
            $options  = array(
                'name'           => $this->input->post('name'),
                'account'        => $this->input->post('username'),
                'password'       => $this->input->post('password'),
                'status'         => $this->input->post('status'),
                'mac'            => $this->input->post('mac'),
                'tariff_plan_id' => $this->input->post('package'),
                'username'       => $username,
                'validity'       => $validity,
                'password'       => $this->input->post('password'),
            );
            $is_created = $this->users_model->create($options);
            if ($is_created['status'] == true) {
                // get custom package id from stalker
                $custom_pack_id = $this->stalker_model->get_custom_plan_id();
                if (isset($_POST['packs']) and $tariff = $custom_pack_id) {
                    $packages = $this->input->post('packs[]');
                    $user_id  = $is_created['id'];
                    $this->stalker_model->add_package($user_id, $packages);
                }
                $this->msg('User account was created successfully!');
                redirect('admin/users', 'refresh');
            }
        } else {
            $this->render('users/add');
        }
    }

    /*Edit user account*/
    public function edit($username = NULL) {
        $users = $this->users_model->get_user($username);
        if (empty($username) || $users->num_rows() == 0) {
            show_404();
            exit();
        }
        $current_mac_address = $users->row()->mac;
        $this->data['title']     = 'Edit ' . $this->module_name;
        $this->data['module']    = $this->module_name;
        $this->data['resellers'] = $this->reseller_model->get_all();
        $this->data['row']       = $users->row();
        $this->data['stalker']   = $this->users_model->get_stalker_user($username);
        $this->form_validation->set_rules('name', 'Name', 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[100]');
        //$this->form_validation->set_rules('mac', 'MAC', 'trim|strtoupper|valid_mac|callback_check_mac[' . $username . ']');
        $this->form_validation->set_rules('mac', 'MAC', 'trim|strtoupper|valid_mac|callback_check_edited_mac[' . $username . ',' . $current_mac_address . ']');
        $this->form_validation->set_rules('reseller', 'Reseller', 'trim|required');
        $this->form_validation->set_rules('parent_password', 'Parent Pin', 'trim|required|exact_length[4]|is_numeric');
        if ($this->form_validation->run() == TRUE) {
            $dealer = $this->input->post('dealer');
            $reseller = $this->input->post('reseller');
            $username_owner = (empty($dealer)) ? $reseller : $dealer;
            $options = array(
                'name'              => $this->input->post('name'),
                'account'           => $username,
                'password'          => $this->input->post('password'),
                'status'            => $this->input->post('status'),
                'mac'               => $this->input->post('mac'),
                'tariff_plan_id'    => $this->input->post('package'),
                'username'          => $username_owner,
                'phone'             => $this->input->post('phone'),
                'note'              => $this->input->post('note'),
                'password'          => $this->input->post('password'),
                'parent_password'   => $this->input->post('parent_password'),
            );
            $is_updated = $this->users_model->update($options);
            if ($is_updated['status'] == TRUE) {
                // get custom package id from stalker
                $custom_pack_id = $this->stalker_model->get_custom_plan_id();
                if (isset($_POST['packs']) and $tariff = $custom_pack_id) {
                    $packages = $this->input->post('packs[]');
                    $user_id  = $is_updated['id'];
                    $this->stalker_model->update_package($user_id, $packages);
                }
                $this->msg('User account was updated successfully!');
                redirect('admin/users', 'refresh');
            }
        } else {
            $this->render('users/view');
        }
    }

    public function delete($username = NULL) {
        //delete the user account only permitted by admin
        if ($this->users_model->delete($username)) {
            $this->msg('User account was deleted successfully!');
            redirect('admin/users', 'refresh');
        } else {
            $this->msg('Error Occured , Please try again later!', 'danger', 'fa fa-warning');
            redirect('admin/users', 'refresh');
        }
    }

    /*Ajax Drop down for Resellers Section */
    public function get_dealer_dropdown() {
        $reseller    = $this->input->post('reseller');
        $sql         = $this->db->where('username_owner', $reseller)->order_by('name', 'asc')->get('users');
        echo $prefix = '<select class="form-control" name="dealer">';
        echo '<option value="" selected="selected">Please Select Dealer</option>';
        if ($sql->num_rows() > 0) {
            foreach ($sql->result() as $dealer) {
                echo '<option value="' . $dealer->username . '">' . $dealer->name . '</option>';
            }
        }
        echo $suffix = '</select>';
    }

    /*
    public function check_credits($validity)
    {
        $dealer         = $this->input->post('dealer');
        $reseller       = $this->input->post('reseller');
        $username       = (empty($dealer)) ? $reseller : $dealer;
        $remain_credits = $this->transaction_model->get_credit_balance($username);
        if ($remain_credits < $validity) {
            $this->form_validation->set_message('check_credits', $username . " don't have enough credits to create account!");
            return false;
        } else {
            return true;
        }
    }
    public function check_mac($mac,$username)
    {
        $stalker   = $this->load->database('stalker', true);
        // $edit_mode = $this->uri->segment(4);
        $sql = $stalker->where('login !=', $username)->where('mac', $mac)->get('users');
        if ($sql->num_rows() == 0 || empty($mac)) {
            return true;
        } else {
            $this->form_validation->set_message('check_mac', 'This MAC Address was already exists!');
            return false;
        }
    }
    */

    private function check_validity_format($validity, $callback_name) {
        if ($validity === 'FREE_TRIAL') { 
            return TRUE;
        }

        if (is_numeric($validity)) {
            $validity = intval($validity);
        } else {
            $this->form_validation->set_message($callback_name, "The specified option is not valid");
            return FALSE;
        }

        if ($validity < 1 or $validity > 12) {
            $this->form_validation->set_message($callback_name, "The specified period is not valid");
            return FALSE;
        }

        return TRUE;
    }

    private function check_validity_credits($validity, $callback_name) {
        if ($validity === 'FREE_TRIAL') { 
            return TRUE;
        }

        $dealer = $this->input->post('dealer');
        $reseller = $this->input->post('reseller');
        $username = (empty($dealer)) ? $reseller : $dealer;
        $remain_credits = $this->transaction_model->get_credit_balance($username);
        if ($remain_credits < $validity) {
            $this->form_validation->set_message($callback_name, $username . " don't have enough credits to create account!");
            return FALSE;
        }

        return TRUE;
    }

    private function check_validity_free_trial($validity, $callback_name) {
        if ($validity !== 'FREE_TRIAL') { 
            return TRUE;
        }

        $mac = $this->input->post('mac');
        $mac_db_data = $this->db->where(array('mac' => $mac))->get('free_trial_users');
        if ($mac_db_data->num_rows() != 0) {
            $this->form_validation->set_message($callback_name, "The specified MAC cannot use another free trial");
            return FALSE;
        }

        return TRUE;
    }

    public function check_validity($validity) {

        $callback_name = 'check_validity';

        if (!$this->check_validity_format($validity, $callback_name)) {
            return FALSE;
        }

        if (!$this->check_validity_credits($validity, $callback_name)) {
            return FALSE;
        }

        if (!$this->check_validity_free_trial($validity, $callback_name)) {
            return FALSE;
        }

        return TRUE;
    }

    //public function check_edited_mac($mac, $username) {
    public function check_edited_mac($mac, $extra_params) {
        // a bit of a hack to be able to pack more then 2 params on the callback 
        // (see "https://stackoverflow.com/questions/8740973/in-codeigniter-how-to-pass-a-third-parameter-to-a-callback-form-validation")
        $params = preg_split('/,/', $extra_params);
        $username = $params[0];
        $current_mac_address = $params[1];      

        if (FALSE) { // the following code is currently not applicable for admin (thus admin can change the MAC of a Free Trial user)
            if ($this->users_model->is_free_trial_user($username) && $current_mac_address !== $mac) {
                $this->form_validation->set_message('check_edited_mac', 'Cannot change the MAC of a Free Trial user');
                return FALSE;
            }
        }   

        $stalker = $this->load->database('stalker', TRUE);
        // $edit_mode = $this->uri->segment(4);
        $sql = $stalker->where('login !=', $username)->where('mac', $mac)->get('users');
        if ($sql->num_rows() == 0 || empty($mac)) {
            return TRUE;
        } else {
            //$this->form_validation->set_message('check_edited_mac', 'This MAC address is in use');
            $this->form_validation->set_message('check_edited_mac', 'The MAC address \'' . $mac . '\' is in use');
            return FALSE;
        }
    }

    public function renew($username = NULL) {

        log_debug_msg("admin/controllers/users.php/renew(): entering");

        $users = $this->users_model->get_user($username);
        if (empty($username) || $users->num_rows() == 0) {
            show_404();
            exit();
        }

        $this->data['title']   = 'Renew ' . $this->module_name;
        $this->data['module']  = $this->module_name;
        $this->data['row']     = $users->row();
        $this->data['sql']     = $this->users_model->get_transactions($username);
        $this->data['stalker'] = $this->users_model->get_stalker_user($username);

        $this->form_validation->set_rules('credits', 'Credits', 'trim|required|numeric|max_length[2]|callback_check_renew_validity');
        if ($this->form_validation->run() === true) {
            $credits = $this->input->post('credits');
            $type    = $this->input->post('type');
            if ($type == "RCDT") {
                log_debug_msg("admin/controllers/users.php/renew(): trying to recover credits from user $username");
                if ($this->users_model->recover_credits($username, $credits) === true) {
                    log_debug_msg("admin/controllers/users.php/renew(): $credits successfully recovered");
                    $this->msg($credits . ' credits were successfully recovered');
                    redirect('admin/users/index/', 'refresh');
                } else {
                    log_debug_msg("admin/controllers/users.php/renew(): there was an error while trying to recover $credits credits from user $username");
                    show_error('Error Occured, Please try again later');
                    die();
                }
            } else {
                log_debug_msg("admin/controllers/users.php/renew(): trying to add months to user $username");
                if ($this->users_model->renew($username, $credits) === true) {
                    log_debug_msg("admin/controllers/users.php/renew(): $credits months successfully added to user $username");
                    $this->msg($credits . ' months were successfully added');
                    redirect('admin/users/index/', 'refresh');
                } else {
                    log_debug_msg("admin/controllers/users.php/renew(): there was an error while trying to add $credits months to user $username");
                    show_error('Error Occured, Please try again later');
                    die();
                }

            }
        } else {
            log_debug_msg("admin/controllers/users.php/renew(): in 'else' section of form validation");
            $this->render('users/view');
        }
    }

    /*
    public function check_validity_renew_orig($credits)
    {
        $username  = $this->uri->segment(4);
        $owner_sql = $this->users_model->get_user($username);
        $owner     = $owner_sql->row();
        $balance  = ($type == "RCDT") ? $this->users_model->get_balance($username) : $this->transaction_model->get_credit_balance($owner->username);

        log_debug_msg("admin/controllers/users.php/check_validity(): \$username = $username");
        log_debug_msg("admin/controllers/users.php/check_validity(): \$owner_sql = $owner_sql");
        log_debug_msg("admin/controllers/users.php/check_validity(): \$owner = $owner");
        log_debug_msg("admin/controllers/users.php/check_validity(): \$balance = $balance");

        // $balance   = $this->transaction_model->get_credit_balance($owner->username);
        if ($balance < $credits) {
            $this->form_validation->set_message('check_validity', "Your dealer or reseller have don't have enough credits!");
            return false;
        } else {
            return true;
        }
    }
    */

    public function check_renew_validity($credits) {
        //$username = $this->user['username'];
        $username  = $this->uri->segment(4);
        $owner_sql = $this->users_model->get_user($username);
        $owner     = $owner_sql->row();
        $owner_username = $owner->username;
        $type      = $this->input->post('type');

        $balance   = ($type == "RCDT") ? $this->users_model->get_balance($username) : $this->transaction_model->get_credit_balance($owner_username);

        log_debug_msg("admin/controllers/users.php/check_renew_validity(): \$credits = $credits");
        log_debug_msg("admin/controllers/users.php/check_renew_validity(): \$username = $username");
        log_debug_msg("admin/controllers/users.php/check_renew_validity(): \$type = $type");
        log_debug_msg("admin/controllers/users.php/check_renew_validity(): \$owner_username = $owner_username");
        log_debug_msg("admin/controllers/users.php/check_renew_validity(): \$balance = $balance");

        if ($balance < $credits) {
            if ($type == "RCDT") {
                $validation_message = "Cannot recover $credits credits from user (recover max = $balance)";
            } else {
                $validation_message = "Your dealer or reseller don't have enough credits (remaining credits = $balance)";
            }
            $this->form_validation->set_message('check_renew_validity', $validation_message);
            log_debug_msg("admin/controllers/users.php/check_renew_validity(): returning false");
            return false;
        } else {
            log_debug_msg("admin/controllers/users.php/check_renew_validity(): returning true");
            return true;
        }
    }

    public function message($login = NULL) {
        $this->data['title']  = "Send Message to User";
        $this->data['module'] = "Send Mesage to User";
        $sql                  = $this->users_model->get_user($login);
        if ($sql->num_rows() == 0 || empty($login)) {
            show_404();
        } else {
            $user = $this->users_model->get_stalker_user($login);
            $this->form_validation->set_rules('message', 'Message', 'trim|required');
            if ($this->form_validation->run() == true) {
                $message  = $this->input->post('message');
                $send_msg = $this->stalker_model->send_message($user->id, $message);
                if ($send_msg == true) {
                    $this->msg('Message was send!');
                    redirect('admin/users/edit/'.$login, 'refresh');
                } else {
                    $this->msg('Message was failed to send, try again!', 'danger');
                    redirect('admin/users/edit/'.$login, 'refresh');
                }
            } else {
                $this->data['events'] = $this->stalker_model->get_events($user->id);
                $this->data['row']    = $sql->row();
                $this->data['stalker']   = $this->users_model->get_stalker_user($login);
                $this->render('users/view');
            }
        }
    }
}
/* End of file Users.php */
/* Location: ./application/modules/admin/controllers/Users.php */
