<?php

class m_characterization_tuberculosis extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'characterization_tuberculosis';
        $this->primary_key = 'id';
    }


    // Método para obter registros por Patient_id
    public function get_by_patient_id($patient_id)
    {
        $this->db->select('*');
        $this->db->from($this->_table);
        $this->db->where('Patient_id', $patient_id);
        $this->db->where('Active', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_characterization_tuberculosis($cid) {
        $sql = "SELECT 
                    ct.Location,
                    ct.TestDate AS TDate,
                    ct.TbLocation AS LocationDescription,
                    ct.Bacteriological AS ConfirmationDescription,
                    ct.Tests AS TestDescription,
                    ct.Resistance AS ResistanceProfileDescription,
                    ct.AnotherResistance,
                    ct.PriorTreatment AS PriorTreatmentDescription,
                    ct.OtherPriorTreatment
                FROM 
                    characterization_tuberculosis ct
                WHERE 
                    ct.Patient_id = ?;
                ";
        
        $query = $this->db->query($sql, array($cid));
        $result = $query->result_array();
        return $result;
    }
    

    // Método para obter um registro específico por id
    public function get_characterization($id)
    {
        $this->db->select('*');
        $this->db->from($this->_table);
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    // Método para inserir um novo registro
    public function insert_record($data)
    {
        return $this->db->insert($this->_table, $data);
    }

    public function invalidate_tb_characterization($id) {
        $data = array('Active' => 0);
        $this->db->where('id', $id);
        return $this->db->update($this->_table, $data);
    }    

}
