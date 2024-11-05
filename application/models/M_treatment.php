<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 28-Oct-15
 * Time: 10:20 AM
 */
class m_treatment extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'treatment';
        $this->primary_key = 'TREATMENTID';
    }

    public function get_type_in($array_data)
    {

        $this->db->where_in('Type', $array_data);
        $query = $this->db->get($this->_table);
        $result = $query->result_array();
        return $result;
    }
}
