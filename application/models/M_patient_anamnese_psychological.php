<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_patient_anamnese_psychological extends MY_CRUD {

    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_anamnese_psychological';
        $this->primary_key = 'PAPID';
    }

    public function get_patient_anamnese_psychological_by_pid($pid) {
        $this->db->select('*');
        $this->db->from('patient_anamnese_psychological');
        $this->db->where('PID', $pid);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

}
?>