<?php
defined('BASEPATH') or exit('No direct script access allowed');
function receiver_status($account)
{
    $CI     = &get_instance();
    $status = $CI->stalker_model->receiver_staus($account);
    return $status;
}
function expiry_date($account)
{
    $CI     = &get_instance();
    $status = $CI->stalker_model->expiry_date($account);
    return $status;
}

function get_tariff_name($account)
{
    $CI     = &get_instance();
    $status = $CI->stalker_model->get_tariff_name($account);
    return $status;
}
function ip($account)
{
    $CI     = &get_instance();
    $status = $CI->stalker_model->user_info($account, 'ip');
    return $status;
}

function user_status($status)
{
    $status = ($status == 0) ? '<span class="label label-sm label-success block">Active</span>' : '<span class="label label-sm label-danger block">INACTIVE</span>';
    return $status;
}

function admin_user_del_button($account)
{
    $CI = &get_instance();
    if ($CI->users_model->check_expired($account) !== "Expired") {
        $button = del_dis_button("You can't delete active user account");
    } else {
        $button = del_button("admin/users/delete/" . $account, 'User');
    }
    return $button;
}

function get_dealer($account)
{
    $CI = &get_instance();
    if ($CI->users_model->get_owner($account) == "RSLR") {
        $data = $account;
    } else {
        $data = '';
    }
    return $data;
}
function get_reseller($account)
{
    $CI = &get_instance();
    if ($CI->users_model->get_owner($account) == "RSLR") {
        $data = $CI->users_model->get_reseller($account);
        // $data = $CI->users_model->get_reseller($dealer);
    } else if ($CI->users_model->get_owner($account) == "SRSLR") {
        $data = $account;
    }
    return $data;
}
function get_manager($account)
{
    $CI = &get_instance();
    if ($CI->users_model->get_owner($account) == "RSLR") {
        $dealer = $CI->users_model->get_reseller($account);
        $data   = $CI->users_model->get_reseller($dealer);
    } else if ($CI->users_model->get_owner($account) == "SRSLR") {
        $data = $CI->users_model->get_reseller($account);
    }
    return $data;
}
