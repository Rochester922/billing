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
        $arrayMonth = [];

        if (count($deductions) > 0) {
            foreach ($deductions as $value) {
                $this->form_validation->set_rules("month_{$value->id}", 'month', 'required');
                $this->form_validation->set_rules("month_deduction_{$value->id}", 'credit deduction', 'required|integer');

                // Validate unique month
                if (isset($arrayMonth[$this->input->post("month_{$value->id}")])) {
                    $this->msg('Updated credit deductions failed due to overlapping months!', 'danger');
                    redirect('admin/deductions/index', 'refresh');
                }

                $arrayMonth[$this->input->post("month_{$value->id}")] = $this->input->post("month_{$value->id}");
            }
        }

        if ($this->form_validation->run() == true) {
            // Remmove all deduction
            $this->deduction_model->delete_all();

            foreach ($deductions as $value) {
                // Insert deduction
                $this->deduction_model->create([
                    'month' => $this->input->post("month_{$value->id}"),
                    'month_deduction' => $this->input->post("month_deduction_{$value->id}"),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            $this->msg('Credit deductions was updated successfully!');
            redirect('admin/deductions/index', 'refresh');
        } else {
            $this->render('deductions/index');
        }
    }
}
