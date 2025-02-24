<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends ResellerController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->data['title']         = 'Dashboard';
        $this->data['balance']       = $this->transaction_model->get_credit_balance($this->user['username']);
        $this->data['expired_users'] = $this->users_model->get_expired_users($this->user['username']);
        $this->data['active_users']  = $this->users_model->get_active_users($this->user['username']);
        $this->data['total_dealers'] = $this->db->where('username_owner', $this->user['username'])->get('users');
        $this->render('common/dashboard');
    }

}

/* End of file Dashboard.php */
