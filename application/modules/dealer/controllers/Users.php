<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends DealerController {
    protected $module_name = 'users';

    public function __construct() {
        parent::__construct();
        $this->load->helper('deduction_helper');
    }

    public function index() {
        $this->data['title']         = 'Manage ' . $this->module_name;
        $this->data['module']        = $this->module_name;
        $query                       = trim($this->input->get('query'));
        $status                      = trim($this->input->get('status'));
        $this->data['total_users']   = $this->db->where('username', $this->user['username'])->get('accounts')->num_rows();
        $this->data['expired_users'] = $this->users_model->get_expired_users($this->user['username']);
        $this->data['active_users']  = $this->users_model->get_active_users($this->user['username']);
        $this->db->where('username', $this->user['username']);
        if (!empty($status) && $status == 'active') {
            $this->db->where('status', ACCOUNT_STATUS_ON);
        }
        if (!empty($status) && $status == 'inactive') {
            $this->db->where('status', ACCOUNT_STATUS_OFF);
        }
        if (!empty($status) && $status == 'expired') {
            $this->db->where(array('expires !=' => '0000-00-00 00:00:00', 'expires <' => date('Y-m-d H:i:s')));
        }
        if (!empty($query) && empty($status)) {
            $sql = $this->db->query("select * from accounts where username = '" . $this->db->escape_str($this->user['username']) . "' and (account LIKE '%" . $this->db->escape_like_str($query) . "%' or mac LIKE '%" . $this->db->escape_like_str($query) . "%' or ip LIKE '%" . $this->db->escape_like_str($query) . "%' or full_name LIKE '%" . $this->db->escape_like_str($query) . "%' or phone LIKE '%" . $this->db->escape_like_str($query) . "%')");
            // $this->db->or_like(array('account'=>$query,'mac'=>$query,'ip'=>$query,'full_name'=>$query,'phone'=>$query),'after');
        } else {
            $this->db->where('username', $this->user['username']);
            $sql = $this->db->get('accounts');
        }
        $this->data['sql'] = $sql;
        $this->data['query'] = $query;
        // echo   $this->db->last_query(); exit;
        $this->render('users/index');
    }

    public function add() {
        $this->data['title'] = 'Add ' . $this->module_name;
        $this->data['module'] = $this->module_name;
        $this->data['resellers'] = $this->reseller_model->get_all();
        $this->data['deduction'] = arrayDataCreditDeduction();
        $this->form_validation->set_rules('name', 'Name', 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('username', 'Username', 'trim|strtolower|alpha_numeric|required|valid_login');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[100]');
        $this->form_validation->set_rules('mac', 'MAC', 'trim|strtoupper|valid_mac|is_unique_mac');
        //$this->form_validation->set_rules('validity', 'Validity', 'trim|required|numeric|max_length[2]|callback_check_credits');
        //$this->form_validation->set_rules('validity', 'Validity', 'trim|required|callback_check_credits');
        $this->form_validation->set_rules('validity', 'Validity', 'trim|required|callback_check_validity');
        if ($this->form_validation->run() == true) {
            $validity = $this->input->post('validity');
            $username = $this->user['username'];
            $options  = array(
                'name'           => $this->input->post('name'),
                'account'        => $this->input->post('username'),
                'password'       => $this->input->post('password'),
                'status'         => ACCOUNT_STATUS_ON,
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
                redirect('dealer/users', 'refresh');
            }
        } else {
            $this->render('users/add');
        }
    }

    /*Edit user account*/
    public function edit($username = NULL) {
        $users = $this->db->where(array('username' => $this->user['username'], 'account' => $username))->get('accounts');
        if (empty($username) || $users->num_rows() == 0) {
            show_404();
            exit();
        }
        $current_mac_address = $users->row()->mac;
        $this->data['title'] = 'Edit ' . $this->module_name;
        $this->data['module'] = $this->module_name;
        $this->data['row'] = $users->row();
        $this->data['stalker'] = $this->users_model->get_stalker_user($username);
        $this->data['deduction'] = arrayDataCreditDeduction();
        $this->data['type']      = null;
        $this->form_validation->set_rules('name', 'Name', 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[100]');
        $this->form_validation->set_rules('mac', 'MAC', 'trim|strtoupper|valid_mac|callback_check_edited_mac[' . $username . ',' . $current_mac_address . ']');        
        $this->form_validation->set_rules('parent_password', 'Parent Pin', 'trim|required|exact_length[4]|is_numeric');
        if ($this->form_validation->run() == TRUE) {
            $username_owner = $this->user['username'];
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
                redirect('dealer/users', 'refresh');
            }
        } else {
            $this->render('users/edit');
        }
    }

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

        if ($validity < 1 or $validity > 24) {
            $this->form_validation->set_message($callback_name, "The specified period is not valid");
            return FALSE;
        }

        return TRUE;
    }

    private function check_validity_credits($validity, $callback_name) {
        if ($validity === 'FREE_TRIAL') { 
            return TRUE;
        }
        $username = $this->user['username'];
        $remaining_credits = $this->transaction_model->get_credit_balance($username);
        if ($remaining_credits < $validity) {
            $this->form_validation->set_message($callback_name, "You don't have enough credits to create account!");
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

        if ($this->users_model->is_free_trial_user($username) && $current_mac_address !== $mac) {
            //$this->form_validation->set_message('check_edited_mac', 'The MAC address \'' . $mac . '\' is in use');
            $this->form_validation->set_message('check_edited_mac', 'Cannot change the MAC of a Free Trial user');
            return FALSE;
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

        log_debug_msg("dealer/controllers/users.php/renew(): entering");

        $users = $this->db->where(array('username' => $this->user['username'], 'account' => $username))->get('accounts');
        if (empty($username) || $users->num_rows() == 0) {
            show_404();
            exit();
        }

        // Before update user_credit_summarize
        $this->creditsummarize_model->before_update($username);

        $type    = $this->input->post('type');

        $this->data['title']  = 'Renew ' . $this->module_name;
        $this->data['module'] = $this->module_name;
        $this->data['row']    = $users->row();
        $this->data['sql']    = $this->users_model->get_transactions($username);
        $this->data['deduction'] = arrayDataCreditDeduction();
        $this->data['type'] = $type;

        if (!is_null($type) && $type == "RENEW") {
            $this->form_validation->set_rules('validity', 'Validity', 'trim|required|callback_check_validity');
        } else {
            $this->form_validation->set_rules('credits', 'Credits', 'trim|required|numeric|max_length[2]|callback_check_renew_validity');
        }

        if ($this->form_validation->run() === true) {
            $credits = $this->input->post('credits');
            $validity = $this->input->post('validity');

            if ($type == "RENEW") {
                $credits = $validity;
            }
            
            if ($type == "RCDT") {
                log_debug_msg("dealer/controllers/users.php/renew(): trying to recover credits from user $username");
                if ($this->users_model->recover_credits($username, $credits) === true) {
                    log_debug_msg("dealer/controllers/users.php/renew(): $credits successfully recovered");
                    $this->msg($credits . ' credits were successfully recovered');
                    redirect('dealer/users/index/', 'refresh');
                } else {
                    log_debug_msg("dealer/controllers/users.php/renew(): there was an error while trying to recover $credits credits from user $username");
                    show_error('Error Occured, Please try again later');
                    die();
                }
            } else {
                log_debug_msg("dealer/controllers/users.php/renew(): trying to add months to user $username");
                if ($this->users_model->renew($username, $credits) === true) {
                    log_debug_msg("dealer/controllers/users.php/renew(): $credits months successfully added to user $username");
                    $this->msg($credits . ' months were successfully added');
                    redirect('dealer/users/index/', 'refresh');
                } else {
                    log_debug_msg("dealer/controllers/users.php/renew(): there was an error while trying to add $credits months to user $username");
                    show_error('Error Occured, Please try again later');
                    die();
                }

            }
        } else {
            log_debug_msg("dealer/controllers/users.php/renew(): in 'else' section of form validation");
            $this->data['stalker'] = $this->users_model->get_stalker_user($username);
            $this->render('users/edit');
        }
    }

    public function check_renew_validity($credits) {
        $username = $this->user['username'];
        $account  = $this->uri->segment(4);
        $type     = $this->input->post('type');
        $balance  = ($type == "RCDT") ? $this->users_model->get_balance($account) : $this->transaction_model->get_credit_balance($username);
        // $balance = ;
        if ($balance < $credits) {
            if ($type == "RCDT") {
                $validation_message = "You cannot recover $credits credits (recover max = $balance)";
            } else {
                $validation_message = "You dont have enough credits (remaining credits = $balance)";
            }
            $this->form_validation->set_message('check_renew_validity', $validation_message);
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function message($login = NULL) {
        $this->data['title']  = "Send Message to User";
        $this->data['module'] = "Send Mesage to User";
        $this->data['type'] = null;
        $sql                  = $this->db->where(array('account' => $login, 'username' => $this->user['username']))->get('accounts');
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
                    redirect('dealer/users', 'refresh');
                } else {
                    $this->msg('Message was failed to send, try again!', 'danger');
                    redirect('dealer/users', 'refresh');
                }
            } else {
                $this->data['events']  = $this->stalker_model->get_events($user->id);
                $this->data['row']     = $sql->row();
                $this->data['stalker'] = $this->users_model->get_stalker_user($login);
                $this->render('users/edit');
            }
        }
    }

    public function delete($account = NULL) {
        if (empty($account)) {
            show_404();
            exit;
        }

        $sql = $this->db->where(array('account' => $account, 'username' => $this->user['username']))->get('accounts');
        if ($sql->num_rows() === 0) {
            show_404();
            exit;
        }
        $row = $sql->row();
        if ($this->stalker_model->check_expired($row->expires) !== "Expired") {
            show_404();
            exit;
        }

        // clear all transaction history
        // Comment 2020-01-30
		// $this->db->where('account', $account)->delete('transactions');
        // delete user account
        if ($this->users_model->delete($account) === true) {
            $this->msg('User account was deleted!');
            redirect('dealer/users', 'refresh');
        }
    }
    
    public function renewOneMonth($username = NULL)
    {
        $users = $this->users_model->get_user($username);
        if (empty($username) || $users->num_rows() == 0) {
            show_404();
            exit();
        }

        // Before update user_credit_summarize
        $this->creditsummarize_model->before_update($username);

        $this->form_validation->set_rules('validity', 'Validity', 'trim|required|callback_check_validity');

        // Param query
        $query = $this->input->post('query');
        $credits = $this->input->post('validity');

        if ($this->form_validation->run() == TRUE) {
            if ($this->users_model->renew($username, $credits) === true) {
                log_debug_msg("admin/controllers/users.php/renew(): $credits months successfully added to user $username");
                $this->msg('Renewal successfully!');
            } else {
                log_debug_msg("admin/controllers/users.php/renew(): there was an error while trying to add $credits months to user $username");
                $this->msg('Error Occured, Please try again later', 'danger');
            }
        } else {
            $validity_error = form_error('validity');

            $this->msg($validity_error, 'danger');
        }
        
        redirect('dealer/users'.(!empty($query) ? '?query=' . $query : ''), 'refresh');
    }
}
/* End of file Users.php */
/* Location: ./application/modules/dealer/controllers/Users.php */
