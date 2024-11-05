<?php
/**
 * This model works with information in table "area" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_area extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'emergency_area';
        $this->primary_key = 'AREAID';
    }

}