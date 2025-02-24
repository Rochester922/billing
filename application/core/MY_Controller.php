<?php (defined('BASEPATH')) or exit('No direct script access allowed');

// load the MX_Controller class 
require APPPATH . "third_party/MX/Controller.php";

class MY_Controller extends MX_Controller {
    protected $data = array();
    protected $user = array();

    public function __construct() {
        parent::__construct();
        $this->data['app_title']   = 'IPTV System';
        $this->form_validation->CI = &$this;
        $this->user                = $this->session->userdata('auth_info');
        // $this->form_validation->set_ci_reference($this);
    }

    protected function render($the_view = null, $template = 'master') {
        if ($template == 'json' || $this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode($this->data);
        } else {
            $this->data['auth_info'] = $this->user;
            $this->data['view_content'] = (is_null($the_view)) ? '' : $this->load->view($the_view, $this->data, true);
            $this->load->view('layout/' . $template, $this->data);
        }
    }

    public function msg($text, $type = "success", $icon = "fa fa-check-square") {
        $this->session->set_flashdata('msg', $text);
        $this->session->set_flashdata('msg_type', $type);
        return true;
    }

}

// admin controller
class AdminController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if (empty($this->user['username']) || $this->user['type'] !== 'ROOT') {
            redirect('login', 'refresh');
        }
    }

}

// Dealer Controller
class DealerController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if (empty($this->user['username']) || $this->user['type'] !== 'RSLR') {
            redirect('login', 'refresh');
        }
    }

}

// Reseller Controller
class ResellerController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if (empty($this->user['username']) || $this->user['type'] !== 'SRSLR') {
            redirect('login', 'refresh');
        }
    }

}

//Manager Controller
class ManagerController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if (empty($this->user['username']) || $this->user['type'] !== 'MNGR') {
            redirect('login', 'refresh');
        }
    }

}


/* End of file MY_Controller.php */
