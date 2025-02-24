<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends ManagerController {
    protected $module_name = 'users';

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->data['title']  = 'Manage ' . $this->module_name;
        $this->data['module'] = $this->module_name;
        $query                = trim($this->input->get('query'));
        if (!empty($query)) {
            $sql = $this->manager_model->get_all_users($this->user['username'],$query);
        } else {
           $sql = '';
        }
        $this->data['sql'] = $sql;

		$sql_expired = $this->manager_model->get_all_users_expired($this->user['username']);
		$this->data['sql_expired'] = $sql_expired;

		$this->render('users/index');
    }

    public function delete($username = NULL) {
        //delete the user account only permitted by admin
        if (empty($username)) {
            show_404();
            exit;
        }
        if ($this->manager_model->is_myuser($username, $this->user['username']) == false) {
            show_404();
            exit;
        }
        if ($this->users_model->delete($username)) {
            $this->msg('User account was deleted successfully!');
            redirect('manager/users', 'refresh');
        } else {
            $this->msg('Error Occured , Please try again later!', 'danger');
            redirect('manager/users', 'refresh');
        }
    }

    public function edit($login = NULL) {
        $this->data['title']  = "Send Message to User";
        $this->data['module'] = "Send Mesage to User";

        $sql = $this->users_model->get_user($login);
        if ($sql->num_rows() == 0 || empty($login)) {
            show_404();
        } else {
            if ($this->manager_model->is_myuser($login, $this->user['username']) !== true) {show_404();exit;}
            $user = $this->users_model->get_stalker_user($login);
            $this->form_validation->set_rules('message', 'Message', 'trim|required');
            if ($this->form_validation->run() == true) {
                $message  = $this->input->post('message');
                $send_msg = $this->stalker_model->send_message($user->id, $message);
                if ($send_msg == true) {
                    $this->msg('Message was send!');
                    redirect('manager/users/index/', 'refresh');
                } else {
                    $this->msg('Message was failed to send, try again!', 'danger');
                    redirect('manager/users/index/', 'refresh');
                }
            } else {
                $this->data['events'] = $this->stalker_model->get_events($user->id);
                $this->data['row']    = $sql->row();
                $this->render('users/edit');
            }
        }
    }

    public function activate($login = NULL) {
        if (empty($login)) {
            show_404();
            exit;
        } else {
            $is_match = $this->manager_model->is_myuser($login, $this->user['username']);
            if ($is_match == false) {
                show_404();
                exit;
            }
            $sql = $this->users_model->get_user($login);
            if ($sql->num_rows() > 0) {
                $user         = $sql->row();
                $stalker_user = $this->users_model->get_stalker_user($login);
                if ($this->stalker_model->check_expired($user->expires) == "Expired") {
                    show_error("You can't activate expired box");
                    die;
                } else {
                    if ($user->status == ACCOUNT_STATUS_ON) {
                        show_error("The Box was already Active!");
                        die;
                    } else {

                        $this->db->set('status', ACCOUNT_STATUS_ON);
                        $this->db->where('account', $login);
                        $this->db->update('accounts');

                        $this->users_model->change_status(ACCOUNT_STATUS_ON, $login);
                        $this->stalker_model->cut_on($stalker_user->id);
                        //$this->stalker_model->restore_package($stalker_user->id);
                        $this->msg('STB Box was activated successfully!');
                        redirect('manager/users', 'refresh');
                    }

                }
            } else {
                show_404();
                exit;
            }
        }
    }

    public function block($login = NULL) {
        if (empty($login)) {
            show_404();
            exit;
        } else {
            $is_match = $this->manager_model->is_myuser($login, $this->user['username']);
            if ($is_match == false) {
                show_404();
                exit;
            }
            $sql = $this->users_model->get_user($login);
            if ($sql->num_rows() > 0) {
                $user         = $sql->row();
                $stalker_user = $this->users_model->get_stalker_user($login);
                if ($this->stalker_model->check_expired($user->expires) == "Expired") {
                    show_error("You can't change expired box");
                    die;
                } else {
                    if ($user->status == ACCOUNT_STATUS_OFF) {
                        show_error("The Box was already blocked or expired!");
                        die;
                    } else {
                        $this->db->set('status', ACCOUNT_STATUS_OFF);
                        $this->db->where('account', $login);
                        $this->db->update('accounts');

                        $this->users_model->change_status(ACCOUNT_STATUS_OFF, $login);
                        $this->stalker_model->cut_off($stalker_user->id);
                        // $this->stalker_model->restore_package($stalker_user->id);
                        $this->msg('STB Box was blocked successfully!');
                        redirect('manager/users', 'refresh');
                    }

                }
            } else {
                show_404();
                exit;
            }
        }
    }

}
/* End of file Users.php */
/* Location: ./application/modules/admin/controllers/Users.php */
