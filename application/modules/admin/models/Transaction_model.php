<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->helper('deduction_helper');
    }

    public function add($credits, $type, $username, $account = null, $expires = null, $coverageStart = null) {
        $arrayDeductions = arrayDataCreditDeduction();
        
        $remarks = null;

        // $type = 'CRDT';
        // $transaction_id = $this->db->query("select max(transaction) + 1 from transactions where username = '" . $username . "'")->row();
        $transaction_id = $this->db->select_max('transaction')->where('username', $username)->get('transactions')->row();
        $transaction    = $transaction_id->transaction + 1;

        if (isset($arrayDeductions[$credits]) && $type != "CRDT" && !is_null($account)) {
            $numberFree = $credits - $arrayDeductions[$credits];
            // Handle Insert Transaction Bonus
            $this->db->insert('transactions', [
                'username'    => $username,
                'account'     => $account,
                'type'        => 'BONUS',
                "transaction" => (int) $transaction,
                "periods"     => 0,
                "timestamp"   => date("Y-m-d H:i:s"),
                "coverage_start" => $coverageStart,
                "coverage_end" => $expires,
                "remarks" => "Credit <strong>From</strong>: $username <strong>To</strong>: $account ($numberFree credits free)"
            ]);

            $credits = $arrayDeductions[$credits];
            $transaction += 1;
        }

        if ($type != "CRDT") {
            $remarks = "Credit <strong>From</strong>: $username <strong>To</strong>: $account";
        }

        $options = array(
            'username'    => $username,
            'type'        => $type,
            "transaction" => (int) $transaction,
            "periods"     => (int) $credits,
            "timestamp"   => date("Y-m-d H:i:s"),
            "coverage_start" => $coverageStart,
            "coverage_end" => $expires,
            "remarks" => $remarks
        );
        if (!empty($account)) {
            $options['account'] = $account;
        }

        if ($this->db->insert('transactions', $options)) {
            return true;
        } else {
            return false;
        }

    }

    public function get_credit_balance($username) {
        $sql = $this->db->query("select sum(case when type = 'CRDT' then periods else -periods end) as balance from transactions where username = '" . $username . "';");
        $row = $sql->row();
        return (int) $row->balance;
    }

    public function get_all($username) {
        $sql = $this->db->query("select transaction,username, type, " .
            "case when type = 'CRDT' then periods else -periods end as periods, amount, account, coverage_start,coverage_end, timestamp from transactions where username = '" . $username . "' order by timestamp");
        $result = $sql;
        return $result;
    }
}

/* End of file Transaction_model.php */
/* Location: ./application/modules/admin/models/Transaction_model.php */
