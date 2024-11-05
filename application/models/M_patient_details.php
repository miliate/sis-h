<?php
/**
 * This model works with information in table "patient_emr_contact" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_patient_details extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_details';
        $this->primary_key = 'id';
    }

    public function get_patient_details_by_patient($patient_id) {
        $this->db->from($this->_table);
        $this->db->where('PID', $patient_id);
        $this->db->where('active', 1);
        $query = $this->db->get();

        return $query->result_array();
    }
}