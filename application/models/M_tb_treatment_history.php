<?php
/**
 * Created by Rodrigues.
 * Date: 19/08/2024
 * Time: 09:08 PM
 */

class m_tb_treatment_history extends MY_CRUD
{
    public function __construct() {
        parent::__construct();
        $this->_table = 'tb_treatment_history'; 
        $this->primary_key = 'id'; 
    }

    public function create_treatment($data) {
        return $this->db->insert($this->_table, $data);
    }

    public function update_treatment($id, $data) {
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->_table, $data);
    }

    public function get_treatment_by_id($id) {
        $this->db->where($this->primary_key, $id);
        $query = $this->db->get($this->_table);
        return $query->row_array();
    }

    public function get_treatments_by_patient_id($patient_id) {
        $this->db->where('patient_id', $patient_id);
        $this->db->where('Active', 1); 
        $query = $this->db->get($this->_table);
        return $query->result_array();
    }

    public function get_all_treatments() {
        $query = $this->db->get($this->_table);
        return $query->result_array();
    }

    public function deactivate_treatment($id) {
        $data = array('Active' => 0);
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->_table, $data);
    }

}