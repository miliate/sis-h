<?php

class m_dietary_list  extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'dietary_list';
        $this->primary_key = 'id';
    }
  
    public function get_name_by_id($id) {
        $this->db->select('name');
        $this->db->from('dietary_list');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result();
    } 
        
}
