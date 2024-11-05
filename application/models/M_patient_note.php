<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 23-Oct-15
 * Time: 4:28 PM
 */
class m_patient_note extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'patient_notes';
        $this->primary_key = 'patient_notes_id';
    }

    public function get_by_type_and_date($ref_id, $date)
    {

        $this->db->select('patient_notes.*,CONCAT(user.name," ", user.OtherName) as UserName');
        $this->db->from($this->_table);
        $this->db->join('user', 'user.UID = patient_notes.CreateUser');
        $this->db->where('patient_notes.Ref_id', $ref_id);
        $this->db->where('DATE(patient_notes.CreateDate)', $date);
        $this->db->where('patient_notes.Active', '1');
        $query = $this->db->get();
        return $query->result_array();
    }
}
