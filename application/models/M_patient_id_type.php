<?php
/**
 * This model works with information in table "patient_id_type" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */

class m_patient_id_type extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_id_type';
        $this->primary_key = 'id_type';
    }
}