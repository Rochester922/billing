<?php
defined('BASEPATH') or exit('No direct script access allowed');

function get_balance($username)
{
    $CI  = &get_instance();
    $sql = $CI->db->query("select sum(case when type = 'CRDT' then periods else -periods end) as balance from transactions where username = '" . $username . "';");
    $row = $sql->row();
    return (int) $row->balance;
}
function trans_status($type, $account = null)
{
    if ($type == "DBIT") {$status = '<span class="label label-sm label-danger red-thunderbird block">USED</span>';}
    if ($type == "CRDT" && empty($account)) {$status = '<span class="label label-sm label-success block gree-jungle">PURCHASED</span>';}
    if ($type == "CRDT" && !empty($account)) {$status = '<span class="label label-sm label-warning block gree-jungle">RECOVERED</span>';}
    return $status;
}
function trans_id($transaction)
{
    $trans_number = str_pad($transaction, 8, "0", STR_PAD_LEFT);
    return $trans_number;
}
