<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function add($credits, $type, $username, $account = null) {
        // $type = 'CRDT';
        // $transaction_id = $this->db->query("select max(transaction) + 1 from transactions where username = '" . $username . "'")->row();
        $transaction_id = $this->db->select_max('transaction')->where('username', $username)->get('transactions')->row();
        $transaction    = $transaction_id->transaction + 1;

        $options = array(
            'username'    => $username,
            'type'        => $type,
            "transaction" => (int) $transaction,
            "periods"     => (int) $credits,
            "timestamp"   => date("Y-m-d H:i:s"),
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
