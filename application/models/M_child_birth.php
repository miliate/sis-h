<?php
/**
 * This model works with information in table "child_birth" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_child_birth extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'child_birth';
        $this->primary_key = 'ChildBirthID';
    }
}