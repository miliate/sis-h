<?php
class m_hospital_names extends CI_Model {
    public function get_hospital_name() {
        $query = $this->db->get('hospital_names');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->Name; 
        }
    }
}
