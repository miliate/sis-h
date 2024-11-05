<?php

/**
 * Created by Nelson Mahumane
 * User: 
 * Date: 30-Oct-2024
 * Time: 10:20 AM
 */
class m_observation extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'observation';
        $this->primary_key = 'obsrvation_id';
    }

    public function insert_batch($data)
    {
        return $this->db->insert_batch($this->_table, $data);
    }
}
