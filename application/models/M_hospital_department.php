<?php
/**
 * This model works with information in table "hospital_department" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */

class m_hospital_department extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'hospital_departments';
        $this->primary_key = 'department_id';
    }
}