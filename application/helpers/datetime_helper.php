<?php
defined('BASEPATH') or exit('No direct script access allowed');

/* Returns a new end_date.
*  If $date is expired (that is, in the past) it is ignored and the new end_date is calculated by adding $months to the present time 
*/
function get_expiry_date($month, $date=NULL) {

    $month = strval($month);
    $datetime_now = new DateTime(date('Y-m-d H:i:s'));

    if ($date == NULL) { 
        $datetime = new DateTime(date('Y-m-d H:i:s'));
    } else {
        $datetime = new DateTime($date);
    }
    
    if ($datetime_now > $datetime) { //make sure $datetime does not represent a time in the past (an expired date) 
        $datetime = $datetime_now;      
    }       
    
    if ($month != 'FREE_TRIAL') { // if not a free trial
        $datetime->modify('+' . $month . ' month');
    } else { // if free trial
        $datetime->modify('+2 day');
    }
    
    return $datetime->format('Y-m-d H:i:s');
}

function get_expiry_date___old($month, $date = null)
{
    if ($date == null) {
        $datetime = new DateTime(date('Y-m-d 00:00:00'));
    } else {
        $datetime = new DateTime($date);
    }
    $datetime->modify('+' . $month . ' month');
    $finale = $datetime->format('Y-m-d H:i:s');
    //$cur_date=date('Y-m-d H:i:s', strtotime('-1 day', strtotime($finale)));
    return $finale;
}

function get_months($exp_date)
{
    //$exp_date = "2016-12-24 21:34:43";
    $currentDateTime     = new DateTime;
    $dateTimeInTheFuture = new DateTime($exp_date);

    if ($dateTimeInTheFuture < $currentDateTime) {
        return 0;
    }    
    
    $dateInterval        = $dateTimeInTheFuture->diff($currentDateTime);
    $totalMonths         = 12 * $dateInterval->y + $dateInterval->m;
    return $totalMonths;
}

function recover_date($expi_date, $month)
{
    $datetime = new DateTime($expi_date);
    $datetime->modify('-' . $month . ' month');
    $finale = $datetime->format('Y-m-d H:i:s');
    //$cur_date=date('Y-m-d H:i:s', strtotime('-1 day', strtotime($finale)));
    return $finale;
}

// returns the current datetime
function get_current_datetime() {
    $datetime_now = new DateTime(date('Y-m-d H:i:s'));
    return $datetime_now->format('Y-m-d H:i:s');
}

// compares datetimes in the format 'Y-m-d H:i:s'
// result is: 
//   . greater than 0 if $dt1 > $dt2
//   . lees than 0 if $dt1 < $dt2
//   . equal to 0 if $dt1 == $dt2
function compare_datetimes($dt1, $dt2) {
    if ($dt1 > $dt2) { 
        return 1;
    } elseif ($dt1 < $dt2) {
        return -1;
    } else {
        return 0;   
    }       
} 

function user_subscr_current_month_end($user_subscr_end) {
    $dt_now = new DateTime(date('Y-m-d H:i:s'));
    $user_subscr_end = new DateTime($user_subscr_end);
    //  echo($dt_now->format('Y-m-d H:i:s') . "\n");
    //  echo($user_subscr_end->format('Y-m-d H:i:s') . "\n");
    if ($user_subscr_end < $dt_now) {
        return NULL;
    }
    $diff = $user_subscr_end->diff($dt_now);
    $months_delta = ($diff->format('%y') * 12) + $diff->format('%m') + 5; // '+ 5' gives some slack, just in case
    // echo($months_delta . "\n");
    $tmp = new DateTime($user_subscr_end->format('Y-m-d H:i:s'));
    while (true) {
        $tmp->modify('-' . $months_delta . ' months');
        //echo($tmp->format('Y-m-d H:i:s') . "\n");
        if ($tmp >= $dt_now) break;
        --$months_delta;
        $tmp = new DateTime($user_subscr_end->format('Y-m-d H:i:s'));
    }
    return $tmp->format('Y-m-d H:i:s');
}

function substract_months($datetime_str, $months) {
    $dt = new DateTime($datetime_str);
    $dt->modify("-" . $months . " month"); 
    return $dt->format('Y-m-d H:i:s');
}

function user_max_period_reversal($user_subscr_end) {
    $dt_user_subscr_end = new DateTime($user_subscr_end);
    $dt_d = new DateTime(user_subscr_current_month_end($user_subscr_end));
    if ($dt_d >= $dt_user_subscr_end) return 0;
    $diff = $dt_user_subscr_end->diff($dt_d);
    $max_periods = ($diff->format('%y') * 12) + $diff->format('%m');
    return ($max_periods >= 0 ? $max_periods : 0);
}

function user_max_credit_reversal($user_subscr_end) {
    return user_max_period_reversal($user_subscr_end);
}

