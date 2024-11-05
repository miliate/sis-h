<?php

class m_request_item extends MY_CRUD {

    function __construct() {
        parent::__construct ();
        $this->_table = 'request_item';
        $this->primary_key = 'id';
    }

     // Function to get all items by request ID
     public function get_items_by_request_id($request_id) {
        $this->db->where('request_id', $request_id);
        $query = $this->db->get($this->_table);
        return $query->result_array();
    }

    // Function to get a request item by its ID
    public function get_item_by_id($item_id) {
        $this->db->where('id', $item_id);
        $query = $this->db->get($this->_table);
        return $query->row_array();
    }

}