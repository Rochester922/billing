<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealers_users extends ResellerController {
    protected $module_name = 'Users';

    public function __construct() {
        parent::__construct();
        //$this->load->model('dealer_model');
        //$this->load->model('transaction_model');
        //$this->load->model('reseller_model');
    }

    public function index($dealer = NULL) {

        if (empty($dealer)) {
            show_404();
            exit;
        }
        $dealer_sql = $this->db->where(array('username_owner' => $this->user['username'], 'username' => $dealer))->get('users');
        if ($dealer_sql->num_rows() == 0) {
            show_404();
            exit;
        }
        $this->data['title']         = 'Manage ' . $this->module_name;
        $this->data['module']        = $this->module_name;
        $status                      = trim($this->input->get('status'));
        $this->data['total_users']   = $this->db->where('username', $dealer)->get('accounts')->num_rows();
        $this->data['expired_users'] = $this->users_model->get_expired_users($dealer);
        $this->data['active_users']  = $this->users_model->get_active_users($dealer);
        // $this->db->where('username', $this->user['username']);
        if (!empty($status) && $status == 'active') {
            $this->db->where('status', ACCOUNT_STATUS_ON);
        }
        if (!empty($status) && $status == 'inactive') {
            $this->db->where('status', ACCOUNT_STATUS_OFF);
        }
        if (!empty($status) && $status == 'expired') {
            $this->db->where(array('expires !=' => '0000-00-00 00:00:00', 'expires <' => date('Y-m-d H:i:s')));
        }

        $this->db->where('username', $dealer);
        $sql               = $this->db->get('accounts');
        $this->data['sql'] = $sql;
        // echo   $this->db->last_query(); exit;
        $this->data['del_row'] = $dealer_sql->row();
        $this->render('dealers/users');
    }

    public function activate($login = NULL) {
        if (empty($login)) {
            show_404();
            exit;
        } else {
            $is_match = $this->reseller_model->is_myuser($login, $this->user['username']);
            if ($is_match == false) {
                show_404();
                exit;
            }

            $sql = $this->users_model->get_user($login);
            if ($sql->num_rows() > 0) {
                $user         = $sql->row();
                $stalker_user = $this->users_model->get_stalker_user($login);
                if ($this->stalker_model->check_expired($user->expires) == "Expired") {
                    $this->msg("You can't activate expired box", 'danger');
                    redirect('reseller/dealers_users/index/' . $user->username, 'refresh');
                } else {
                    if ($user->status == ACCOUNT_STATUS_ON) {
                        $this->msg("The Box was already Active!", 'warning');
                        redirect('reseller/dealers_users/index/' . $user->username, 'refresh');
                    } else {

                        $this->db->set('status', ACCOUNT_STATUS_ON);
                        $this->db->where('account', $login);
                        $this->db->update('accounts');

                        $this->users_model->change_status(ACCOUNT_STATUS_ON, $login);
                        $this->stalker_model->cut_on($stalker_user->id);
                        //$this->stalker_model->restore_package($stalker_user->id);
                        $this->msg('STB Box was activated successfully!');
                        redirect('reseller/dealers_users/index/' . $user->username, 'refresh');
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
            $is_match = $this->reseller_model->is_myuser($login, $this->user['username']);
            if ($is_match == false) {
                show_404();
                exit;
            }
            $sql = $this->users_model->get_user($login);
            if ($sql->num_rows() > 0) {
                $user         = $sql->row();
                $stalker_user = $this->users_model->get_stalker_user($login);
                if ($this->stalker_model->check_expired($user->expires) == "Expired") {
                    $this->msg("You can't change expired box", 'danger');
                    redirect('reseller/dealers_users/index/' . $user->username, 'refresh');
                } else {
                    if ($user->status == ACCOUNT_STATUS_OFF) {
                        $this->msg("The Box was already blocked or expired!", 'danger');
                        redirect('reseller/dealers_users/index/' . $user->username, 'refresh');
                    } else {
                        $this->db->set('status', ACCOUNT_STATUS_OFF);
                        $this->db->where('account', $login);
                        $this->db->update('accounts');

                        $this->users_model->change_status(ACCOUNT_STATUS_OFF, $login);
                        $this->stalker_model->cut_off($stalker_user->id);
                        // $this->stalker_model->restore_package($stalker_user->id);
                        $this->msg('STB Box was blocked successfully!');
                        redirect('reseller/dealers_users/index/' . $user->username, 'refresh');
                    }

                }
            } else {
                show_404();
                exit;
            }
        }
    }

    public function delete($account = NULL) {
        if (empty($account)) {
            show_404();
            exit;
        }
        $is_match = $this->reseller_model->is_myuser($account, $this->user['username']);
        if ($is_match == false) {
            show_404();
            exit;
        }
        $sql = $this->db->where(array('account' => $account))->get('accounts');
        if ($sql->num_rows() === 0) {
            show_404();
            exit;
        }
        $row = $sql->row();
        if ($this->stalker_model->check_expired($row->expires) !== "Expired") {
            show_404();
            exit;
        }

        // clear all transaction history
        // $this->db->where('account', $account)->delete('transactions');

        // delete user account
        if ($this->users_model->delete($account) === true) {
            $this->msg('User account was deleted!');
            redirect('dealer/users', 'refresh');
        }
    }

}

/* End of file Dealers_users.php */
/* Location: ./application/modules/reseller/controllers/Dealers_users.php */
