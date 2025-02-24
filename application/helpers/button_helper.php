<?php
defined('BASEPATH') or exit('No direct script access allowed');
function edit_button($path)
{
    $url    = site_url($path);
    $anchor = '<a href="' . $url . '" class="btn btn-xs btn-success action-left blue-steel"  data-popup="tooltip" title="Edit"><i class="icon-pencil7"></i> Edit </a>';
    return $anchor;
}
function del_button($path, $title = null)
{
    $url     = site_url($path);
    $onclick = "return confirm('Do you want really delete this " . $title . " account?');";
    $anchor  = '<a href="' . $url . '"    class="btn btn-xs action-right btn-danger red-thunderbird tooltips" data-popup="tooltip" title="Delete" onclick="' . $onclick . '" title="Delete"><i class="icon-trash"></i> Del </a>';
    return $anchor;
}
function del_dis_button($msg)
{
    $anchor = '<a href="javascript:void(0);" class="btn  action-right btn-xs btn-danger bg-danger-300 tooltips" data-container="body" data-popup="tooltip" title="' . $msg . '" ><i class="icon-trash"></i> Del </a>';
    return $anchor;
}
function renew_button($path)
{
    $url    = site_url($path);
    $anchor = '<a href="' . $url . '" class="btn btn-xs btn-succes tooltips" data-container="body" data-placement="top" data-original-title="Renew"><i class="fa fa-refresh"></i> Ren </a>';
    return $anchor;
}
function credits_button($path)
{
    $url    = site_url($path);
    $anchor = '<a href="' . $url . '" class="btn btn-xs btn-success tooltips" data-container="body" data-placement="top" data-original-title="Add Credits"><i class="fa fa-money"></i> Credits </a>';
    return $anchor;
}
function msg_button($path)
{
    $url    = site_url($path);
    $anchor = '<a href="' . $url . '" class="btn btn-xs btn-default blue-chambray tooltips" data-container="body" data-placement="top" data-original-title="Send Message" ><i class="fa fa-envelope"></i> Msg </a>';
    return $anchor;
}
function status_label($status)
{
    if ($status == 'A') {
        $label = '<span class="label label-success block">ACTIVE</span>';
    } else {
        $label = '<span class="label label-danger block">INACTIVE</span>';
    }
    return $label;
}
function user_action_buttons($account, $module)
{
    $CI = &get_instance();

    $button = '';

        
        $button .= edit_button($module . '/users/edit/' . $account);
    if ($CI->users_model->check_expired($account) !== "Expired") {
        if ($module == 'manager' or $module == 'admin') {
            $onclick           = "return confirm('This account still active, Do you want really delete this user account?');";
            $button .=  '<a href="'.site_url($module . '/users/delete/' . $account).'" onclick="' . $onclick . '" class="btn  action-right btn-xs btn-danger bg-danger-300 tooltips" data-container="body" data-popup="tooltip" title="this account still active" ><i class="icon-trash"></i> Del </a>';
        } else {

            $button .= del_dis_button("You can't delete active user account");
        }
    } else {
        $button .= del_button($module . "/users/delete/" . $account, 'User');
    }

    return $button;
}
function dealer_action_buttons($account, $module)
{
    $CI     = &get_instance();
    $button = '';
    $button .= edit_button($module . '/dealers/edit/' . $account);
    if ($CI->dealer_model->has_users($account) == true) {
        $button .= del_dis_button("You can't delete this dealer account");
    } else {
        $button .= del_button($module . "/dealers/delete/" . $account, 'dealer');
    }
    return $button;
}
function reseller_action_buttons($account, $module)
{
    $CI     = &get_instance();
    $button = '';
    $button .= edit_button($module . '/resellers/edit/' . $account);
    if ($CI->reseller_model->has_dealers($account) == true or $CI->reseller_model->has_users($account) == true) {
        $button .= del_dis_button("You can't delete this reseller account");
    } else {
        $button .= del_button($module . "/resellers/delete/" . $account, 'reseller');
    }
    return $button;
}
function manager_action_buttons($account, $module)
{
    $CI = &get_instance();
    $button = '';
    $button .= edit_button($module . '/managers/edit/' . $account);
    if ($CI->reseller_model->has_dealers($account) == true) {
        $button .= del_dis_button("You can't delete this manager account");
    } else {
        $button .= del_button($module . "/managers/delete/" . $account, 'manager');
    }
    return $button;
}
