<?php
class m_patient_external_prescription extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert_prescription($data) {
        $this->db->insert('patient_external_prescription', $data);
        return $this->db->insert_id();
    }

    public function get_prescription_by_id($prescription_id) {
        $this->db->select('p.*, CONCAT(user.Title, " ", user.Name, " ", user.OtherName) AS Technician');
        $this->db->from('patient_external_prescription p');
        $this->db->join('user', 'user.UID = p.CreateUser', 'left');
        $this->db->where('p.prescription_id', $prescription_id);
        return $this->db->get()->row();
    }
    
    
    public function get_prescription_by_nid($patient_nid) {
        $this->db->select('*');
        $this->db->from('patient_external_prescription');
        $this->db->where('PID', $patient_nid);
        return $this->db->get()->row();
    }

    public function get_all_health_units() {
        $this->db->select('id, name');
        $this->db->from('health_unit'); 
        $query = $this->db->get();
        return $query->result();
    }
     
    public function get_all_patient_discounts() {
        $this->db->select('name');
        $this->db->from('patient_discounts');
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
    
    
}
?>
