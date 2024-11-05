<?php

class m_prescription_no_drugs  extends MY_CRUD {

    public function __construct() {
        parent::__construct();
        $this->_table = 'prescription_no_drugs';
        $this->primary_key = 'ID';
    }

    public function get_all_dietetic($type) {
        $this->db->select('*');
        $this->db->from('prescription_no_drugs');
        $this->db->where('Active', 1);
        $this->db->where('Type', $type);
        $this->db->order_by('ID', 'DESC'); 
        $query = $this->db->get();
        return $query->result_array();
    }     

}