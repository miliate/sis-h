<?php
/**
 * This model works with information in table "complaints" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */

class m_complaints extends MY_Model {
    public function __construct() {
        parent::__construct();
        $this->_table = 'complaints';
        $this->primary_key = 'COMPID';
    }
}