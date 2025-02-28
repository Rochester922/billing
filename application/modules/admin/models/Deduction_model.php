<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Deduction_model extends CI_Model
{
    protected $table = 'credit_deductions';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db->order_by('month', 'asc')->get($this->table)->result();
    }

    public function update($data, $id) 
    {
        if (empty($data) || empty($id)) {
            return false;
        }

        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function create($data) 
    {
        return $this->db->insert($this->table, $data);
    }

    public function delete_all()
    {
        return $this->db->truncate($this->table);
    }
}
