<?php

class m_ward_rooms extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'ward_rooms';
        $this->primary_key = 'RID';
    }

    public function default_all_names()
    {
        $this->db->select('RID, Name');
        $this->db->from($this->_table);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_ward() {
        $this->db->select('WID, Name'); 
        $this->db->from('ward'); 
        $query = $this->db->get();
    
        return $query->result(); 
    }
    
    public function get_all_names($ward_id) {
        $this->db->select('RID, Name');
        $this->db->from('ward_rooms');
        $this->db->where('WID', $ward_id); 
        $query = $this->db->get();
        return $query->result_array();
    }
    

    public function get_all_by_rid($rid) {
        $this->db->select('*');
        $this->db->from('ward_rooms');  
        $this->db->where('RID', $rid);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_room_name_by_rid($rid) {
        $this->db->select('RID, Name');
        $this->db->from('ward_rooms');  
        $this->db->where('RID', $rid);
        $query = $this->db->get();
        return $query->row();
    }

    public function room_exists($ward_id, $room_name)
    {
        $this->db->where('WID', $ward_id);
        $this->db->where('Name', $room_name);
        $query = $this->db->get('ward_rooms');
        
        return $query->num_rows() > 0;
    }

    public function get_active_rooms() {
        $this->db->select('RID, Name');
        $this->db->from('ward_rooms');
        $this->db->where('Active', 1);
        $query = $this->db->get();
        return $query->result();
    }
}
