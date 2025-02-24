<?php
defined('BASEPATH') or exit('No direct script access allowed');

function get_expiry_date($month, $date = null)
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
