<?php
class m_patient_external_prescription_have_drug extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert_prescription_drug($data) {
        return $this->db->insert('patient_external_prescription_have_drug', $data);
    }


    public function get_drugs_by_prescription_id($prescription_id) {
        $this->db->select('who_drug.name AS DrugName, who_drug.wd_id As DrugID, prescription_have_drug.DoseID, prescription_have_drug.FrequencyID, prescription_have_drug.Period');
        $this->db->from('patient_external_prescription_have_drug AS prescription_have_drug');
        $this->db->join('who_drug', 'prescription_have_drug.DrugID = who_drug.wd_id');
        $this->db->where('prescription_have_drug.prescription_id', $prescription_id);
        return $this->db->get()->result();
    }
}
?>
