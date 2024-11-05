<?php

class m_taken_drugs extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'taken_drugs';
        $this->primary_key = 'TDID';
    }

    public function create($data)
    {
        $this->db->insert('taken_drugs', $data);
    }

    public function get_taken_drugs_by($ref_id)
    {
        $this->db->select('taken_drugs.*, user.Name, user.OtherName');
        $this->db->from($this->_table);
        $this->db->where('taken_drugs.RefID', $ref_id);
        $this->db->join('user', 'taken_drugs.DispensedBy = user.UID', 'left');
        $this->db->order_by('taken_drugs.TakenDateTime', 'desc');

        return $this->db->get()->result();
    }
}
