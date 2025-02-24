<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealers extends ResellerController {
    protected $module_name = 'Dealers';

    public function __construct() {
        parent::__construct();
        //$this->load->model('dealer_model');
        //$this->load->model('transaction_model');
        //$this->load->model('reseller_model');
    }

    public function index() {
        $this->data['title']  = 'Manage ' . $this->module_name;
        $this->data['module'] = $this->module_name;
        $status = $this->input->get('status');
        $this->data['sql']    = $this->db->where('type', 'RSLR')->where('username_owner',$this->user['username'])->order_by('username', 'asc')->get('users');
        // $this->data['is_ajax_table'] = false;
        $this->render('dealers/index');
    }
    
    public function add() {
        $rules = array(
            array(
                'field'  => 'username',
                'label'  => 'Username',
                'rules'  => 'trim|strtolower|required|is_unique[users.username]|alpha_numeric|min_length[3]|max_length[50]',
                'errors' => array('is_unique' => 'This username already exists! try new one.'),
            ),
            array('field' => 'name', 'label' => 'Name', 'rules' => 'trim|alpha_numeric_spaces'),
            array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|min_length[3]|max_length[50]'),
        );

        $this->data['title']     = 'Add ' . $this->module_name;
        $this->data['module']    = $this->module_name;
        $this->data['resellers'] = $this->reseller_model->get_all();
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == true) {
            $options = array(
                'name'           => $this->input->post('name'),
                'username'       => $this->input->post('username'),
                'password'       => $this->input->post('password'),
                'status'         => 'A',
                'type'           => 'RSLR',
                'username_owner' => $this->user['username'],
            );
            if ($this->db->insert('users', $options)) {
                $this->msg('Dealer account was created successfully!');
                redirect('reseller/dealers', 'refresh');
            }

        } else {

            $this->render('dealers/add');
        }
    }

    public function edit($username = NULL) {
        if (empty($username)) {
            show_404();
            exit;
        }
        $rules = array(
            array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|min_length[3]|max_length[50]'),
            array('field' => 'name', 'label' => 'Name', 'rules' => 'trim|alpha_numeric_spaces'),
        );
        $sql = $this->db->where(array('username' => $username, 'type' => 'RSLR', 'username_owner' => $this->user['username']))->get('users');
        if ($sql->num_rows() == 0) { show_404(); exit; }
        $this->data['row']       = $sql->row();
        $this->data['resellers'] = $this->reseller_model->get_all();
        $this->data['title']     = 'Edit ' . $this->module_name;
        $this->data['module']    = $this->module_name;
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == true) {
            $options = array(
                'name'           => $this->input->post('name'),
                'password'       => $this->input->post('password'),
                'status'         => $this->input->post('status'),
                'comments'       => $this->input->post('comments'),
                'type'           => 'RSLR',
                //'username_owner' => $this->input->post('username_owner'),

            );
            $this->db->where('username', $username);
            if ($this->db->update('users', $options)) {
                $this->msg('Dealer account was updated successfully!');
                redirect('reseller/dealers', 'refresh');
            }

        } else {

            $this->render('dealers/edit');
        }
    }

    public function transactions($username = NULL) {
        if (empty($username)) {
            show_404();
            exit;
        }

        $sql = $this->db->where(array('username' => $username, 'type' => 'RSLR', 'username_owner' => $this->user['username']))->get('users');
        if ($sql->num_rows() == 0) { show_404(); exit; }
        $rules = array(
            array('field' => 'credits', 'label' => 'Credits', 'rules' => 'trim|required|numeric|callback_check_credits'),
        );
        $this->form_validation->set_rules($rules);
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
                redirect('reseller/dealers/index/', 'refresh');
            } else {
                show_error('Error Occured, Please check your error log');
                die();
            }
        } else {
            $this->data['row'] = $sql->row();
            // $this->data['sql']    = $this->db->where('username', $username)->order_by('transaction', 'desc')->get('transactions');
            $this->data['sql']    = $this->transaction_model->get_all($username);
            $this->data['title']  = 'Transactions of ' . $this->module_name;
            $this->data['module'] = $this->module_name;
            $this->render('dealers/edit');
        }
    }

    public function check_credits($credits) {
        $type     = $this->input->post('type');
        $username = $this->uri->segment(4);
        // get reseller account
        $dealer            = $this->db->where('username', $username)->get('users')->row();
        $reseller_username = $dealer->username_owner;
        if ($type == "CRDT") {
            $balance = $this->transaction_model->get_credit_balance($reseller_username);
            $action  = 'add';
        } else {
            $balance = $this->transaction_model->get_credit_balance($username);
            $action  = 'recover';
        }
        if ($balance < $credits) {
            $this->form_validation->set_message('check_credits', "You can't " . $action . " credits more than he have!");
            return false;
        } else {
            return true;
        }
    }

    public function delete($username = NULL) {
        //check if reseller has users or dealers under his account
        if ($this->dealer_model->has_users($username) == true or empty($username)) {
            show_404();
            exit;
        } else {
            $sql = $this->db->where(array('username' => $username, 'username_owner' => $this->user['username']))->get('users');
            if ($sql->num_rows() == 0) {
                show_404();
                exit;
            }
            //recover credits before delete
            $remaining_credits = $this->transaction_model->get_credit_balance($username);
            $dealer            = $this->db->where('username', $username)->get('users')->row();
            if ($remaining_credits > 0) {
                //$this->transaction_model->add($remaining_credits, "CRDT", $dealer->username_owner);
            }
            //wipe all transaction records under his username
            //$this->db->where(array('username' => $username));
            //$this->db->delete('transactions');
            //remove dealer from database
            $this->db->where(array('username' => $username, 'type' => 'RSLR', 'username_owner' => $this->user['username']));
            $this->db->delete('users');
            $this->msg('Dealer account was deleted successfully!');
            redirect('reseller/dealers', 'refresh');
        }
    }

}

/* End of file Dealers.php */
/* Location: ./application/modules/reseller/controllers/Dealers.php */
