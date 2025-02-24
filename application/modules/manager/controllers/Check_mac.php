<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Check_mac extends ManagerController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->data['title']  = 'Check MAC Address';
        $this->data['module'] = "Check MAC Address";
        $status               = '';
        $expired              = '';
        $this->form_validation->set_rules('mac', 'MAC', 'trim|required|valid_mac');
        if ($this->form_validation->run() === true) {
            $mac = $this->input->post('mac');
            $sql = $this->db->where('mac', $mac)->get('accounts');
            if ($sql->num_rows() === 1) {
                $user   = $sql->row();
                $status = '<span class="text-danger">MAC Address already exists</span>';
                if ($this->stalker_model->check_expired($user->expires) === "Expired") {
                    $expired = $this->stalker_model->expiry_date($user->expires);
                } else {
                    $expired = '<span class="text-success">MAC Adress is active</span>';
                }
            } else {
                $status = '<span class="text-success">MAC Address is available</span>';
            }
        }
        $this->data['status'] = $status;
        $this->data['expired'] = $expired;
        $this->render('common/check_mac');
    }

}

/* End of file Check_mac.php */
/* Location: ./application/modules/dealer/controllers/Check_mac.php */
