<?php
/**
 * Created by Rodrigues.
 * Date: 15/08/2024
 * Time: 02:08 PM
 */

class m_comorbidities extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'comorbidity'; 
        $this->primary_key = 'id'; 
    }

    public function insert_comorbidity($data) {
        return $this->db->insert($this->_table, $data);
    }

    public function update_comorbidity($id, $data) {
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->_table, $data);
    }


    public function get_comorbidity($id) {
        $this->db->where($this->primary_key, $id);
        $query = $this->db->get($this->_table);
        return $query->row_array();
    }

    public function get_comorbidities_by_patient($PID) {
        $this->db->where('PID', $PID);
        $this->db->where('Active', 1); 
        $query = $this->db->get($this->_table);
        return $query->result_array();
    }

    public function get_comorbidities_with_patology($PID) {
        $this->db->select('comorbidity.*, patology.name as patology_name');
        $this->db->from($this->_table);
        $this->db->join('patology', 'comorbidity.patology_id = patology.id');
        $this->db->where('comorbidity.PID', $PID);
        $this->db->where('comorbidity.Active', 1); 
        $query = $this->db->get();
        return $query->result_array();
    }

    public function invalidate_comorbidity($id) {
        $data = array('Active' => 0);
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->_table, $data);
    }
}
