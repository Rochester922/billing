<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Resellers extends ManagerController {
    protected $module_name = 'Resellers';

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->data['title']  = 'Manage ' . $this->module_name;
        $this->data['module'] = $this->module_name;
        $this->data['sql']    = $this->db->where('type', 'SRSLR')->where('username_owner', $this->user['username'])->order_by('username', 'asc')->get('users');
        // $this->data['is_ajax_table'] = false;
        $this->render('resellers/index');
    }

    public function add() {
        $balance =  $this->transaction_model->get_credit_balance($this->user['username']);
        if ($balance <= 0) {
            show_error("You don't have credits to create account, minimum 1 credit needed!");
            exit;
        }
        $rules = array(
            array(
                'field'  => 'username',
                'label'  => 'Username',
                'rules'  => 'trim|strtolower|required|is_unique[users.username]|alpha_numeric|min_length[3]|max_length[50]',
                'errors' => array('is_unique' => 'This username already exists! try new one.'),
            ),
            array('field'=>'name','label'=>'Name','rules'=>'trim|alpha_numeric_spaces'),
            array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|min_length[3]|max_length[50]'),
            
        );
        $this->data['title']    = 'Add ' . $this->module_name;
        $this->data['module']   = $this->module_name;
        $this->data['managers'] = $this->manager_model->get_all();
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == true) {
            $options = array(
                'name'           => $this->input->post('name'),
                'username'       => $this->input->post('username'),
                'password'       => $this->input->post('password'),
                'status'         => 'A',
                'type'           => 'SRSLR',
                'username_owner' => $this->user['username'],
            );
            if ($this->db->insert('users', $options)) {
                $this->msg('Reseller account was created successfully!');
                redirect('manager/resellers', 'refresh');
            }
        } else {
            $this->render('resellers/add');
        }
    }

    public function edit($username = NULL) {
        if (empty($username)) {
            show_404();
            exit;
        }
        $rules = array(
            array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|min_length[3]|max_length[50]'),
            array('field'=>'name','label'=>'Name','rules'=>'trim|alpha_numeric_spaces'),
        );
        $sql = $this->db->where(array('username' => $username, 'username_owner' => $this->user['username'], 'type' => 'SRSLR'))->get('users');
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
                'type'           => 'SRSLR',
                'username_owner' => $this->user['username'],
            );
            $this->db->where('username', $username);
            if ($this->db->update('users', $options)) {
                $this->msg('Reseller account was updated successfully!');
                redirect('manager/resellers', 'refresh');
            }
        } else {
            $this->render('resellers/view');
        }
    }

    public function transactions($username = NULL) {
        if (empty($username)) {
            show_404();
            exit;
        }
        $sql = $this->db->where(array('username' => $username, 'username_owner' => $this->user['username'], 'type' => 'SRSLR'))->get('users');
        if ($sql->num_rows() == 0) { show_404(); exit; }
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
                redirect('manager/resellers/index/', 'refresh');
            } else {
                show_error('Error Occured, Please check your error log');
                die();
            }
        } else {
            $this->data['row']    = $sql->row();
            $this->data['sql']    = $this->transaction_model->get_all($username);
            $this->data['title']  = 'Transactions of ' . $this->module_name;
            $this->data['module'] = $this->module_name;
            $this->render('resellers/view');
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

    public function delete($username = NULL) {
        //check if reseller has users or dealers under his account
        if ($this->reseller_model->has_dealers($username) == true or $this->reseller_model->has_users($username) == true or empty($username)) {
            show_404();
            exit;
        } else {
            $this->db->where(array('username' => $username, 'type' => 'SRSLR'));
            $this->db->delete('users');
            $this->msg('Reseller account was deleted successfully!');
            redirect('manager/resellers', 'refresh');
        }
    }

}
/* End of file Resellers.php */
/* Location: ./application/modules/manager/controllers/Resellers.php */
