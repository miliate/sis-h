<?php
/**
 * This model works with information in table "department" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_department extends MY_Model {
    public function __construct() {
        parent::__construct();
        $this->_table = 'department';
        $this->primary_key = 'DEPID';
    }
}