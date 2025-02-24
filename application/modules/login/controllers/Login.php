<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('login/auth_model', 'auth');
        $this->session->unset_userdata('auth_info');
    }

    public function index() {
        $this->form_validation->set_rules('login', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == true) {
            $login = $this->input->post('login');
            $password = $this->input->post('password');
            if ($this->auth->attempt(array('username' => $login, 'password' => $password, 'status' => 'A'))) {
                $role = $this->auth->role();
                $settings = $this->db->order_by('id', 'desc')->get('settings')->row();
                $this->session->set_flashdata('global_msg', $settings->global_msg);
                //update last login
                $this->auth->update_ip($login);
                $path = $this->auth->redirect($role);
                redirect($path, 'refresh');
            } else {
                $this->session->set_flashdata('msg', '<div class="alert bg-danger has-icon alert-dismissible fade in"> <div class="alert-body"> <button type="button" class="close" data-dismiss="alert"> <span>×</span> </button> Invalid username and password!. </div> </div>');
                redirect('login', 'refresh');
            }
        } else {
            $data['title'] = "Login";
            $this->load->view('login', $data);
        }
    }

    // This function can and should be added to crontab, with a line like:
    // * * * * * wget -O/dev/null -q  http://165.22.209.150/index.php/login/cron/?hash=e52b8910d3dd2b91e6981a5b0df632b7
    //
    // This function blocks (sets status=1) expired users 
	public function cron() {

        log_debug_msg("login/controllers/Login.php/cron(): entering");

		if($this->input->get('hash')=='e52b8910d3dd2b91e6981a5b0df632b7') {

			//$query = $this->db->query('SELECT * FROM `accounts` WHERE expires < CURDATE() AND ( status = 0 OR status IS NULL )');
            $query_str = "SELECT * FROM accounts WHERE expires < NOW() AND ( status = " . ACCOUNT_STATUS_ON . " OR status IS NULL )";
            $query = $this->db->query($query_str);

			//echo '<pre>';
			foreach ($query->result() as $row) {
				$stalker_user = $this->users_model->get_stalker_user($row->account);
				$this->db->set('status', ACCOUNT_STATUS_OFF);
				$this->db->where('account', $row->account);
				$this->db->update('accounts');

				$this->users_model->change_status(ACCOUNT_STATUS_OFF, $row->account);
                $this->stalker_model->cut_off($stalker_user->id);

                log_debug_msg("login/controllers/Login.php/cron(): [stalker uid: " . $stalker_user->id . ", login: " . $row->account . "]: user found");

				//$this->stalker_model->cut_on($stalker_user->id); // this is from the original code but it was wrong, if we are blocking users we must call cut_off instead of cut_on 
				//$this->stalker_model->restore_package($stalker_user->id); // this is from the original code, we are not using remove_package()/restore_package() at all now 

				//var_dump($row);
			}
			//echo '</pre>';

		} else {
            log_debug_msg("login/controllers/Login.php/cron(): returning to caller");
            die();            
        }

        log_debug_msg("login/controllers/Login.php/cron(): returning to caller");

	}

    // IMPORTANT: This function shouldn't be used and actually should not be called by cron, because it reactivates users that are not yet expired
    // by setting status=0 on such users, and that would make the blocking of users based on the status field totally useless
    //
    // This function reactivates (sets status=0) users that are not yet expired
	public function cron2()	{

        return true; // safeguard (read the above comments)

		$this->stb = $this->load->database('stalker', true);
        $query_str = "SELECT id, mac, expire_billing_date, status FROM users WHERE status=" . ACCOUNT_STATUS_OFF . " and date(expire_billing_date) > date(curdate())";
		$query = $this->stb->query($query_str);

		echo '<pre>';
		foreach ($query->result() as $row)
		{
			var_dump($row->id);
		}
		echo '</pre>';

        $query_str = "UPDATE users SET status = " . ACCOUNT_STATUS_ON . " WHERE status=" . ACCOUNT_STATUS_OFF . " and date(expire_billing_date) > date(curdate())";
		echo $this->stb->query($query_str);
	}

}
/* End of file login.php */
