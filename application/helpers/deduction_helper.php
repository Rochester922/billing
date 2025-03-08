<?php
defined('BASEPATH') or exit('No direct script access allowed');

function arrayDataCreditDeduction()
{
    $CI     = &get_instance();
    // Load model của module admin
    $CI->load->model('admin/deduction_model');
    // Get all data credit deduction
    $deductions = $CI->deduction_model->get_all();

    $arrayMonthDeduction = [];

    foreach ($deductions as $key => $value) {
        $start = $deductions[$key]->month;
        $end = $deductions[$key + 1]->month ?? null;

        if ($key + 1 == count($deductions) && $value->month < 24) {
            $end = 24;
        }

        if (!is_null($end) && $end) {
            for ($i = $start; $i <= $end; $i++) {
                $arrayMonthDeduction[$i] = $i - $value->month_deduction;
            }
        }
    }

    return $arrayMonthDeduction;
}
