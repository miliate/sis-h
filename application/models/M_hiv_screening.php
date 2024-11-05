<?php
/**
 * Created by Rodrigues.
 * Date: 20/08/2024
 * Time: 11:08 PM
 */

class m_hiv_screening extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'hiv_screening'; 
        $this->primary_key = 'id'; 
    }

    public function insert_screening($data) {
        return $this->db->insert($this->_table, $data);
    }    

    public function get_screening($id) {
        $this->db->where($this->primary_key, $id);
        $query = $this->db->get($this->_table);
        return $query->row_array();
    }    

    public function get_screenings_by_patient($PID) {
        $this->db->where('patient_id', $PID);
        $this->db->where('Active', 1);
        $query = $this->db->get($this->_table);
        return $query->result_array();
    }
    
    public function invalidate_screening($id) {
        $data = array('Active' => 0);
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->_table, $data);
    }
    
}
