<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends AdminController {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('dashboard_model');
    }

    public function index() {
        $this->data['title']           = 'Dashboard';
        $this->data['total_users']     = $this->dashboard_model->users();
        $this->data['active_users']    = $this->dashboard_model->active_users();
        $this->data['expired_users']   = $this->dashboard_model->expired_users();
        $this->data['total_mangers']   = $this->dashboard_model->managers();
        $this->data['total_dealers']   = $this->dashboard_model->dealers();
        $this->data['total_resellers'] = $this->dashboard_model->resellers();

        log_error_msg('Inside modules->admin->controllers->Dashboard...');

        $this->render('common/dashboard');
    }

}

/* End of file Dashboard.php */
