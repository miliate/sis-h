<?php
/**
 * This model works with information in table "patient_tracker" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */

class m_patient_tracker extends MY_Model {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_tracker';
        $this->primary_key = 'id';
    }
}
