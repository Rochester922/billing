<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Creditsummarize_model extends CI_Model
{
    protected $table = 'user_credit_summarize';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('deduction_helper');
    }

    public function create($account, $startDate, $expiryDate, $credits) 
    {
        $arrayDeductions = arrayDataCreditDeduction();
        $credits = $arrayDeductions[$credits] ?? $credits;

        return $this->db->insert($this->table, [
            'account' => $account,
            'start_date' => $startDate,
            'max_credit_recoverable' => $credits - 1,
            'expiry_date' => $expiryDate,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
    }

    public function before_update($account) 
    {
        $userCredit = $this->db->where('account', $account)->get($this->table)->row();

        $startDate = new DateTime($userCredit->start_date);
        $expiredDate = new DateTime($userCredit->expiry_date);
        $updatedAt = new DateTime($userCredit->updated_at);
        $currentDate = new DateTime();

        if ($updatedAt->format('Y-m-d') == $currentDate->format('Y-m-d')) {
            return;
        }

        $maxCreditRecoverable = $userCredit->max_credit_recoverable;

        if ($expiredDate < $currentDate) {
            $maxCreditRecoverable = 0;
        } else {
            $dateInterval        = $currentDate->diff($startDate);
            $totalMonths         = 12 * $dateInterval->y + $dateInterval->m;
            $maxCreditRecoverable = max(0, $userCredit->max_credit_recoverable - $totalMonths);
        }

        $this->db->where('account', $account);

        return $this->db->update($this->table, [
            'max_credit_recoverable' => $maxCreditRecoverable,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
    }
}
