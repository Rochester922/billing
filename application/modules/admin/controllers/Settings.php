<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends AdminController {
    protected $module_name = 'Settings';

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->data['title']    = $this->module_name;
        $this->data['module']   = $this->module_name;
        $setting                = $this->db->order_by('id', 'desc')->get('settings')->row();
        $this->data['settings'] = $setting;
        $this->form_validation->set_rules('title', 'App Title', 'trim|required|min_length[3]|max_length[50]');
        if ($this->form_validation->run() === true) {
            $options = array(
                'title'      => $this->input->post('title'),
                'email'      => $this->input->post('email'),
                'global_msg' => $this->input->post('global_msg'),
            );
            $this->db->where('id', $setting->id);
            $this->db->update('settings', $options);
            $this->msg('Setting was saved successfully!');
            redirect('admin/settings', 'refresh');
        } else {
            $this->render('accounts/settings');
        }
    }
    
}
/* End of file Settings.php */
/* Location: ./application/modules/admin/controllers/Settings.php */
