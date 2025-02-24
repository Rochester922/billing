<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealers extends ManagerController {
    protected $module_name = 'Dealers';

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->data['title']  = 'Manage ' . $this->module_name;
        $this->data['module'] = $this->module_name;
        $this->data['sql']    = $this->manager_model->get_all_dealers($this->user['username']);
        // $this->data['is_ajax_table'] = false;
        $this->render('dealers/index');
    }

    public function edit($username = NULL) {
        if (empty($username)) {
            show_404();
            exit;
        }
        $rules = array(
            array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|min_length[3]|max_length[50]'),
            array('field' => 'name', 'label' => 'Name', 'rules' => 'trim|alpha_numeric_spaces'),
            array('field' => 'reseller', 'label' => 'Reseller', 'rules' => 'trim|required|callback_check_reseller'),
        );
        if ($this->manager_model->is_mydealer($username, $this->user['username']) == false) {
            show_404();
            exit;
        }
        $sql = $this->db->where(array('username' => $username, 'type' => 'RSLR'))->get('users');
        if ($sql->num_rows() == 0) { show_404(); exit; }
        $this->data['row']    = $sql->row();
        $this->data['title']  = 'Edit ' . $this->module_name;
        $this->data['module'] = $this->module_name;
        // $this->data['managers'] = $this->manager_model->get_all();
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == true) {
            $options = array(
                'name'           => $this->input->post('name'),
                'password'       => $this->input->post('password'),
                'status'         => $this->input->post('status'),
                'comments'       => $this->input->post('comments'),
                'type'           => 'RSLR',
                'username_owner' => $this->input->post('reseller'),
            );
            $this->db->where('username', $username);
            if ($this->db->update('users', $options)) {
                $this->msg('Dealer account was updated successfully!');
                redirect('manager/dealers', 'refresh');
            }
        } else {
            $this->render('dealers/view');
        }
    }

    public function check_reseller($reseller) {
        $sql = $this->db->where(array('username' => $reseller, 'username_owner' => $this->user['username']))->get('users');
        if ($sql->num_rows() === 1) {
            return true;
        } else {
            $this->form_validation->set_message('check_reseller', 'Please select valid reseller!');
            return false;
        }
    }

    public function transactions($username = NULL) {
        if (empty($username)) {
            show_404();
            exit;
        }
        if ($this->manager_model->is_mydealer($username, $this->user['username']) == false) {
            show_404();
            exit;
        }
        $sql = $this->db->where(array('username' => $username,  'type' => 'RSLR'))->get('users');
        if ($sql->num_rows() == 0) {show_404();exit;}
        // $this->load->model('transaction_model');
        $this->form_validation->set_rules('credits', 'Credits', 'trim|required|numeric|callback_valid_credits');
        if ($this->form_validation->run() === true) {
            $type = $this->input->post('type');
            if ($type == "DBIT") {
                $action = 'Recovered';
            } else {
                $action = 'Added';
            }
            $credits = $this->input->post('credits');
            if ($this->transaction_model->add($credits, $type, $username) === true) {
                //debit from reseller account
                $dealer = $sql->row();
                if ($type == "CRDT") {
                    $this->transaction_model->add($credits, "DBIT", $dealer->username_owner);
                } else {
                    $this->transaction_model->add($credits, "CRDT", $dealer->username_owner);
                }
                $this->msg($credits . ' credits was ' . $action . ' successfully!');
                redirect('manager/dealers/index/', 'refresh');
            } else {
                show_error('Error Occured, Please check your error log');
                die();
            }
        } else {
            $this->data['row']    = $sql->row();
            $this->data['sql']    = $this->transaction_model->get_all($username);
            $this->data['title']  = 'Transactions of ' . $this->module_name;
            $this->data['module'] = $this->module_name;
            $this->render('dealers/view');
        }
    }

    public function valid_credits($credits) {
        $type     = $this->input->post('type');
        $account  = $this->uri->segment(4);
        $user     = $this->db->where('username', $account)->get('users')->row();
        $username = ($type == "CRDT") ? $this->user['username'] : $account;
        $balance  = $this->transaction_model->get_credit_balance($username);
        if ($balance < $credits) {
            $this->form_validation->set_message('valid_credits', "You don't have enough credits!");
            return false;
        } else {
            return true;
        }
    }

    public function status($username = NULL) {
        $action = $this->input->get('action');
        if (empty($username) or empty($action)) {
            show_404();
            exit;
        }
        // echo 'hai'; exit;
        /* protect from tampering any dealer account */
        if ($this->manager_model->is_mydealer($username, $this->user['username']) == false) {
            show_404();
            exit;
        }
        $sql = $this->db->where(array('type' => 'RSLR', 'username' => $username))->get('users');
        if ($sql->num_rows() == 0) {
            show_404();
            exit;
        }
        if ($action == "activate") {
            $status = 'A';
            $msg    = "Activated";
        } else if ($action == "block") {
            $status = "S";
            $msg    = "Blocked";
        } else {
            show_404();
            exit;
        }

        $this->db->set('status', $status);
        $this->db->where('username', $username);
        if ($this->db->update('users')) {
            $this->msg('Dealer account was ' . $msg . ' Successfully!');
            redirect('manager/dealers', 'refresh');
        }

    }

    /*public function delete($username = null)
{
//check if reseller has users or dealers under his account
if ($this->dealer_model->has_users($username) == true or empty($username)) {
show_404();
exit;
} else {
//recover credits before delete
$remaining_credits = $this->transaction_model->get_credit_balance($username);
$dealer            = $this->db->where('username', $username)->get('users')->row();
if ($remaining_credits > 0) {
$this->transaction_model->add($remaining_credits, "CRDT", $dealer->username_owner);
}
//wipe all transaction records under his username
//$this->db->where(array('username' => $username));
//$this->db->delete('transactions');
//remove dealer from database
$this->db->where(array('username' => $username, 'type' => 'RSLR'));
$this->db->delete('users');
redirect('admin/dealers', 'refresh');
}
}*/
}

/* End of file Dealers.php */
/* Location: ./application/modules/admin/controllers/Dealers.php */
