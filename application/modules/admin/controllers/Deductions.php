<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Deductions extends AdminController
{
    protected $module_name = 'credit deductions';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('deduction_model');
    }

    public function index()
    {
        // Get all data credit deduction
        $deductions = $this->deduction_model->get_all();

        $this->data['title'] = 'Manage ' . $this->module_name;
        $this->data['deductions'] = $deductions;

        if (count($deductions) > 0) {
            foreach ($deductions as $value) {
                $this->form_validation->set_rules("month_deduction_{$value->month}", 'credit deduction', 'required|integer');
            }
        }

        if ($this->form_validation->run() == true) {
            foreach ($deductions as $value) {
                $data = [
                    'month_deduction' => $this->input->post("month_deduction_{$value->month}")
                ];

                $this->deduction_model->update($data, $value->id);
            }

            $this->msg('Credit deductions was updated successfully!');
            redirect('admin/deductions/index', 'refresh');
        } else {
            $this->render('deductions/index');
        }  
    }
}
