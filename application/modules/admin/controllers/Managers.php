<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Managers extends AdminController {
    protected $module_name = 'Managers';

    public function __construct() {
        parent::__construct();
        //$this->load->model('reseller_model');
        //$this->load->model('transaction_model');
    }

    public function index() {
        $this->data['title']  = 'Manage ' . $this->module_name;
        $this->data['module'] = $this->module_name;
        $this->data['sql']    = $this->db->where('type', 'MNGR')->order_by('username', 'asc')->get('users');
        $this->render('managers/index');
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
        $this->data['title']  = 'Add ' . $this->module_name;
        $this->data['module'] = $this->module_name;
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == true) {
            $options = array(
                'name'     => $this->input->post('name'),
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'status'   => 'A',
                'type'     => 'MNGR',
            );
            if ($this->db->insert('users', $options)) {
                $this->msg('Manager account was created successfully!');
                redirect('admin/managers', 'refresh');
            }

        } else {

            $this->render('managers/add');
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
        $sql = $this->db->where(array('username' => $username, 'type' => 'MNGR'))->get('users');
        if ($sql->num_rows() == 0) {show_404();exit;}
        $this->data['row']    = $sql->row();
        $this->data['title']  = 'Edit ' . $this->module_name;
        $this->data['module'] = $this->module_name;
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == true) {
            $options = array(
                'name'     => $this->input->post('name'),
                'password' => $this->input->post('password'),
                'status'   => $this->input->post('status'),
                'comments' => $this->input->post('comments'),
                'type'     => 'MNGR',
            );
            $this->db->where('username', $username);
            if ($this->db->update('users', $options)) {
                $this->msg('Manager account was updated successfully!');
                redirect('admin/managers', 'refresh');
            }

        } else {

            $this->render('managers/view');
        }
    }

    public function view($username = NULL) {
        if (empty($username)) {
            show_404();
            exit;
        }
        $sql = $this->db->where(array('username' => $username, 'type' => 'MNGR'))->get('users');
        if ($sql->num_rows() == 0) {show_404();exit;}
        $this->data['row']    = $sql->row();
        $this->data['title']  = 'Edit ' . $this->module_name;
        $this->data['module'] = $this->module_name;
        $this->render('managers/view');
    }

    public function transactions($username = NULL) {
        if (empty($username)) {
            show_404();
            exit;
        }
        $sql = $this->db->where(array('username' => $username, 'type' => 'MNGR'))->get('users');
        if ($sql->num_rows() == 0) {show_404();exit;}
        // $this->load->model('transaction_model');
        $this->form_validation->set_rules('credits', 'Credits', 'trim|required|numeric|check_credits');
        if ($this->form_validation->run() === true) {
            $type = $this->input->post('type');
            if ($type == "DBIT") {
                $action = 'Recovered';
            } else {
                $action = 'Added';
            }
            $credits = $this->input->post('credits');

            if ($this->transaction_model->add($credits, $type, $username) === true) {
                $this->msg($credits . ' credits was ' . $action . ' successfully!');
                redirect('admin/managers/index/', 'refresh');
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
            $this->render('managers/view');
        }

    }

    public function check_credits($credits) {
        $type = $this->input->post('type');
        if ($type == 'CRDT') {
            return true;
        } else {
            $username = $this->uri->segment(4);
            $balance  = $this->transaction_model->get_credit_balance($username);
            if ($balance < $credits) {
                $this->form_validation->set_message('check_credits', "You can't recover credits more than he have!");
                return false;
            } else {
                return true;
            }
        }
    }

    public function delete($username = NULL) {
        //check if reseller has users or dealers under his account
        if ($this->reseller_model->has_dealers($username) == true or $this->reseller_model->has_users($username) == true or empty($username)) {
            show_404();
            exit;
        } else {
            $this->db->where(array('username' => $username, 'type' => 'MNGR'));
            $this->db->delete('users');
            $this->msg('Manager account was deleted successfully!');
            redirect('admin/managers', 'refresh');
        }
    }

}

/* End of file Managers.php */
/* Location: ./application/modules/admin/controllers/Managers.php */
