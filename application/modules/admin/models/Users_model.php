<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model {

    protected $stb;

    public function __construct() {
        parent::__construct();
        $this->stb = $this->load->database('stalker', true);
        $this->load->helper('deduction_helper');
    }

    public function get_user($account) {
        $sql = $this->db->where('account', $account)->get('accounts');
        return $sql;
    }

    public function is_free_trial_user($account) {
        $this->db->where('TIMESTAMPDIFF(HOUR, created, expires) BETWEEN 47 AND 49')->where('account', $account);
        if ($this->db->get('accounts')->num_rows() == 1) {
            return TRUE;
        }        
        return FALSE;
    }

    public function get_stalker_user($login) {
        $sql = $this->stb->where('login', $login)->get('users');
        $data = $sql->row();
        return $data;
    }
    
    public function create($options) {
        $created = get_current_datetime();
        $expires = get_expiry_date($options['validity']);
        $account_status = ($options['status'] === ACCOUNT_STATUS_OFF) ?  ACCOUNT_STATUS_OFF : ACCOUNT_STATUS_ON;
        $using_free_trial = ($options['validity'] === 'FREE_TRIAL') ? TRUE : FALSE;

        //extract the values and insert into stalker database
        $stalker_options = array(
            'fname'          => $options['name'],
            'login'          => $options['account'],
            'mac'            => $options['mac'],
            'status'         => $account_status, 
            // 'comment'        => $options['note'],
            'tariff_plan_id' => $options['tariff_plan_id'],
            'created'        => date('Y-m-d H:i:s'),
            'expire_billing_date' => $expires,
        );
        if ($this->stb->insert('users', $stalker_options)) { // todo: check for errors
            $id = $this->stb->insert_id();
            $password = md5(md5($options['password']) . $id);
            $this->stb->set('password', $password);
            $this->stb->where('id', $id);
            if ($this->stb->update('users')) { // todo: check for errors 
                 $expires = get_expiry_date($options['validity']);
                 $accounts_options = array(
                     'full_name' => $options['name'],
                     'account'   => $options['account'],
                     'mac'       => $options['mac'],
                     'status'    => $account_status,
                     'created'   => $created,
                     'expires'   => $expires,
                     // 'note'      => $options['note'],
                     'username'  => $options['username'],
                     'password'  => $options['password'],
                );
                if ($this->db->insert('accounts', $accounts_options)) {
                    if ($using_free_trial == TRUE) {
                        $free_trial_data = array(
                            'mac' => $options['mac'],
                            'free_trial_end_date' => $expires,
                        ); 
                        $this->db->insert('free_trial_users', $free_trial_data);                         
                        // todo: check for errors
                    } else {
                        // Debit credits from dealer or reseller account
                        $this->transaction_model->add($options['validity'], "DBIT", $options['username'], $options['account'], $expires, date("Y-m-d H:i:s"));
                        // Insert user credit summarize
                        $this->creditsummarize_model->create($options['account'], date("Y-m-d H:i:s"), $expires, $options['validity']);
                        // todo: check for errors
                    }
                    $result = array('id' => $id, 'status' => TRUE);
                    return $result;
                } 
                $result = array('id' => NULL, 'status' => FALSE);
                return $result;
            }
        } else {
            $result = array('id' => NULL, 'status' => FALSE);
            return $result;
        }
    }

    public function change_status($status, $username) {
        log_debug_msg("admin/models/Users_model.php/change_status(): [username: $username, status: $status]: entering");
        $user          = $this->get_user($username)->row();
        $expiry_status = $this->stalker_model->check_expired($user->expires);
        if (! ($expiry_status == "Expired" && $status == ACCOUNT_STATUS_ON) ) { // won't allow to activate an expired user
            $user_info = $this->get_stalker_user($username);
            $id = $user_info->id;
            if ($user_info->status != $status) { // no need to change if the status is already set as requested
                if ($status == ACCOUNT_STATUS_ON) {
                    log_debug_msg("admin/models/Users_model.php/change_status(): [username: $username, status: $status]: trying to cut_on()");
                    $this->stalker_model->cut_on($id);
                    //$this->stalker_model->restore_package($id);
                } else if ($status == ACCOUNT_STATUS_OFF) {
                    log_debug_msg("admin/models/Users_model.php/change_status(): [username: $username, status: $status]: trying to cut_off()");
                    $this->stalker_model->cut_off($id);
                    //$this->stalker_model->remove_package($id);
                }
            } else {
                log_debug_msg("admin/models/Users_model.php/change_status(): [username: $username, status: $status]: nothing done (\$user_info->status == \$status)");
            }
        } else {
            log_debug_msg("admin/models/Users_model.php/change_status(): [username: $username, status: $status]: nothing done (\$expiry_status == 'Expired' && \$status == ACCOUNT_STATUS_ON)");
        }
        log_debug_msg("admin/models/Users_model.php/change_status(): [username: $username, status: $status]: returning to caller");
    }

    public function check_expired($account) {
        $sql = $this->db->where('account', $account)->get('accounts');
        $row = $sql->row();
        $d1  = strtotime($row->expires);
        if ($d1 < time()) {
            return 'Expired';
        } else {

            $date1 = date_create(date('Y-m-d H:i:s'));
            $date2 = date_create($row->expires);
            $diff  = date_diff($date1, $date2);
            return $diff->format("%a");
        }

    }

    public function update($options) {
        //extract the values and insert into stalker database
        //check if expired or not
        if ($this->check_expired($options['account']) == "Expired") {
            $user_grow =$this->get_stalker_user($options['account']); 
            $status = $user_grow->status;
        } else {
            $status = $options['status'];
        }

        $stalker_options = array(
            'fname'             => $options['name'],
            'mac'               => $options['mac'],
            'status'            => $status,
            'phone'             => $options['phone'],
            'comment'           => $options['note'],
            'tariff_plan_id'    => $options['tariff_plan_id'],
            'parent_password'   => $options['parent_password'],
        );

        $this->change_status($options['status'], $options['account']);
        $this->stb->where('login', $options['account']);
        if ($this->stb->update('users', $stalker_options)) {
            $user     = $this->get_stalker_user($options['account']);
            $id       = $user->id;
            $password = md5(md5($options['password']) . $id);
            $this->stb->set('password', $password);
            $this->stb->where('id', $id);
            if ($this->stb->update('users')) {
                $accounts_options = array(
                    'full_name' => $options['name'],
                    'mac'       => $options['mac'],
                    'phone'     => $options['phone'],
                    'note'      => $options['note'],
                    'status'    => $status,
                    'username'  => $options['username'],
                    'password'  => $options['password'],
                );
                $this->db->where('account', $options['account']);
                if ($this->db->update('accounts', $accounts_options)) {
                    // User cut off or cut on status
                    $result = array('id' => $id, 'status' => true);
                    return $result;
                } else {
                    return false;
                }
            }
        }
    }

    public function delete($account) {

        $this->stb->where('login', $account);
        // $this->stb->delete('users');
        if ($this->stb->delete('users')) {
            $this->db->where('account', $account);
            if ($this->db->delete('accounts')) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function get_reseller($username) {
        $sql = $this->db->where('username', $username)->get('users');
        if ($sql->num_rows() == 0) {
            return '';
        } else {
            $data = $sql->row();
            return $data->username_owner;
        }
    }

    public function get_owner($username) {
        $sql  = $this->db->where('username', $username)->get('users');
        $user = $sql->row();
        return $user->type;
    }

    public function get_transactions($username) {
        $sql = $this->db->where('account', $username)->get('transactions');
        return $sql;
    }

    public function renew($username, $credits) {

        log_debug_msg("admin/models/Users_model.php/renew(): [username: $username]: entering");

        $user_sql            = $this->db->where('account', $username)->get('accounts');
        $user                = $user_sql->row();
        $userCredit          = $this->db->where('account', $username)->get('user_credit_summarize')->row();
        $stalker_user        = $this->get_stalker_user($username);
        $coverage_start_date = ($this->stalker_model->check_expired($user->expires) == "Expired") ? date('Y-m-d H:i:s') : $user->expires;
        $expiry_date = get_expiry_date($credits, $coverage_start_date);

        if ($this->transaction_model->add($credits, "DBIT", $user->username, $username, $expiry_date, $coverage_start_date) == true) {
            //first update expiry date
            $this->db->set('expires', $expiry_date);
            $this->db->where('account', $username);
            if (!$this->db->update('accounts')) {
                log_debug_msg("admin/models/Users_model.php/renew(): [username: $username]: \$this->db->update() has failed to set the expires field");
                // todo: handle this error
            }

            // Update user_credit_summarize
            $arrayDeductions = arrayDataCreditDeduction();
            $credits = $arrayDeductions[$credits] ?? $credits;
            $expiredDateOld = new DateTime($userCredit->start_date);
            $currentDate = new DateTime();

            if ($expiredDateOld > $currentDate) {
                $this->db->set('start_date', date('Y-m-d H:i:s'));
                $userCredit->max_credit_recoverable = 0;
            }

            $this->db->set('expiry_date', $expiry_date);
            $this->db->set('max_credit_recoverable', ($userCredit->max_credit_recoverable + $credits));
            $this->db->where('account', $username);
            $this->db->update('user_credit_summarize');

			// Update on Stalker database
			$this->stb->set('expire_billing_date', $expiry_date);
			$this->stb->where('login', $username);
    		if (!$this->stb->update('users')) {
                log_debug_msg("admin/models/Users_model.php/renew(): [username: $username]: \$this->stb->update() has failed to set the expire_billing_date field");
                // todo: handle this error
            }


            //$this->stalker_model->cut_on($stalker_user->id);
			$this->db->set('status', ACCOUNT_STATUS_ON);
			$this->db->where('account', $username);
			if (!$this->db->update('accounts')) {
                log_debug_msg("admin/models/Users_model.php/renew(): [username: $username]: \$this->db->update() has failed to set the status field");
                // todo: handle this error
            }

            log_debug_msg("admin/models/Users_model.php/renew(): [username: $username]: calling \$this->users_model->change_status(" . ACCOUNT_STATUS_ON . ", $username)");
			$this->users_model->change_status(ACCOUNT_STATUS_ON, $username);

            log_debug_msg("admin/models/Users_model.php/renew(): [username: $username]: calling \$this->stalker_model->cut_on(" . $stalker_user->id . ")");
			$this->stalker_model->cut_on($stalker_user->id);

            //log_debug_msg("admin/models/Users_model.php/renew(): [username: $username]: calling \$this->stalker_model->restore_package(" . $stalker_user->id . ")");
			//$this->stalker_model->restore_package($stalker_user->id);

            return true;
        } else {
            log_debug_msg("admin/models/Users_model.php/renew(): [username: $username]: \$this->transaction_model->add() has failed");
            return false;
        }
    }

    public function get_expired_users($dealer = NULL) {
        if ($dealer == NULL) {
            $sql = $this->db->where(array('expires !=' => '0000-00-00 00:00:00', 'expires <' => date('Y-m-d H:i:s')))->get('accounts');
        } else {
            $sql = $this->db->where(array('username' => $dealer, 'expires !=' => '0000-00-00 00:00:00', 'expires <' => date('Y-m-d H:i:s')))->get('accounts');
        }
        return $sql;
    }

    public function get_active_users($dealer = NULL) {
        if ($dealer == NULL) {
            $sql = $this->db->where('status', ACCOUNT_STATUS_ON)->get('accounts');
        } else {
            $sql = $this->db->where(array('username' => $dealer, 'status' => ACCOUNT_STATUS_ON))->get('accounts');
        }
        return $sql;
    }

    public function recover_credits($account, $credits) {
        $sql     = $this->db->where('account', $account)->get('accounts');
        $user    = $sql->row();
        $userCredit = $this->db->where('account', $account)->get('user_credit_summarize')->row();
        $balance = $userCredit->max_credit_recoverable;
        if ($balance > 0 && $balance >= $credits) {
            $expiry_date = $this->calculate_recover_date($account, $credits, $user->expires);
            //add credits back to dealer
            //$this->transaction_model->add($credits, "CRDT", $user->username);
            //add history to user
            $this->transaction_model->add($credits, "CRDT", $user->username, $account, $expiry_date, $userCredit->start_date);
            // Update expired date
            $this->db->where('account', $account);
            $this->db->set('expires', $expiry_date);
            // Update on Stalker database 
            $this->stb->set('expire_billing_date', $expiry_date);
            $this->stb->where('login', $account);
            $this->stb->update('users');
            if ($this->db->update('accounts')) {
                // Update user_credit_summarize
                $this->db->set('expiry_date', $expiry_date);
                $this->db->set('max_credit_recoverable', ($userCredit->max_credit_recoverable - $credits));
                $this->db->where('account', $account);
                $this->db->update('user_credit_summarize');

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    public function get_balance($account) {
        $sql     = $this->db->where('account', $account)->get('user_credit_summarize');
        $user    = $sql->row();
        $balance = $user->max_credit_recoverable;
        return $balance;
    }

    public function calculate_recover_date($account, $credits, $userExpired) {
        $freeMonth = 0;
        $baseCredits = $credits;
        $arrayDeductions = arrayDataCreditDeduction();

        // Total recover of account
        $getRecover = $this->db
             ->query("select sum(periods) as credit_recover from transactions where account = '" . $account . "' and type = 'CRDT';")
             ->row();

        $credits +=  (int) $getRecover->credit_recover ?? 0;

        $transactions = $this->db
            ->where('account', $account)
            ->where('type', 'DBIT')
            ->order_by('timestamp', 'desc')
            ->get('transactions')->result();
        
        if (count($transactions) > 0) {
            foreach ($transactions as $transaction) {
                if (!$transaction->is_subtract_free_month) {
                    $coverageStart = new DateTime($transaction->coverage_start);
                    $coverageEnd = new DateTime($transaction->coverage_end);

                    $dateInterval = $coverageEnd->diff($coverageStart);
                    $totalMonths = 12 * $dateInterval->y + $dateInterval->m;

                    if (isset($arrayDeductions[$totalMonths])) {
                        $freeMonth += $totalMonths - $arrayDeductions[$totalMonths];
                    }
                    
                    // Update subtract free month
                    $this->db->set('is_subtract_free_month', 1);
                    $this->db->where('account', $account)->where('transaction', $transaction->transaction);
                    $this->db->update('transactions');
                }
                
                if ($credits <= $transaction->periods) {
                    break;
                }
                
                $credits -= $transaction->periods;
            }
        }

        $datetime = new DateTime($userExpired);
        $datetime->modify('-' . ($freeMonth + $baseCredits) . ' month');
        $finale = $datetime->format('Y-m-d H:i:s');

        return $finale;
    }
}

/* End of file Users_model.php */
/* Location: ./application/modules/admin/models/Users_model.php */
