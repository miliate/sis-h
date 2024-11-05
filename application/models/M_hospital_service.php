<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 5/17/17
 * Time: 5:08 PM
 */

class m_hospital_service extends MY_CRUD {
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct() {
        parent::__construct();
        $this->_table = 'hospital_services';
        $this->primary_key = 'service_id';
        $this->belongs_to = array(
            'department_id' => array('model' => 'm_hospital_department', 'primary_key' => 'department_id'));
    }

    public function get_services_by_department($department_id) {
        $this->db->select('service_id, abrev, name');
        $this->db->from($this->_table);
        $this->db->where('department_id', $department_id);
        $query = $this->db->get();

        return $query->result_array();
    }
}