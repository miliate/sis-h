<?php

class m_request extends MY_CRUD
{

    function __construct()
    {
        parent::__construct();
        $this->_table = 'request';
        $this->primary_key = 'id';
    }

    /**
     * Insert a new request and return the inserted ID
     */
    public function insert_get_id($data)
    {
        $this->db->insert($this->_table, $data);
        return $this->db->insert_id();
    }

    public function get_all_requests($ref_type)
    {
        $this->db->select('request.*, user.Name as CreateUserName');
        $this->db->from('request');
        $this->db->where("COALESCE(request.ref_type, 'EMR') =", $ref_type);    
        $this->db->join('user', 'user.UID = request.CreateUser');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function get_request_by_id($request_id)
    {
        $this->db->select('*');
        $this->db->from($this->_table);
        $this->db->where('id', $request_id);
        $query = $this->db->get();
        return $query->row_array(); // Retorna apenas um único resultado como array associativo
    }

    // Adicionar método para obter o último código de requisição
    public function get_last_request_code()
    {
        $this->db->select('request_code');
        $this->db->from($this->_table);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->request_code;
        } else {
            return null;
        }
    }

    // Método para obter os detalhes da requisição e seus itens
    public function get_request_details($request_id)
    {
        $query = $this->db->query("
            SELECT
                r.id AS request_id,
                r.request_code,
                r.request_type,
                r.request_date,
                u.name AS request_create_user,
                r.status,
                ri.id AS request_item_id,
                ri.who_drugs_id,
                ri.requested_quantity,
                ri.CreateUser AS item_create_user,
                wd.name,
                wd.fnm,
                wd.pharmaceutical_form,
                wd.formulation,
                wd.dose,
                wd.dosage,
                wd.presentation,
                wd.lot_number
            FROM
                request r
            INNER JOIN
                request_item ri ON r.id = ri.request_id
            INNER JOIN
                who_drug wd ON ri.who_drugs_id = wd.wd_id
            INNER JOIN
                user u ON r.CreateUser = u.uid
            WHERE
                r.id = ?
        ", array($request_id));

        return $query->result_array();
    }

    public function update_request_item($item_data)
    {
        $this->db->where('request_id', $item_data['request_id']);
        $this->db->where('who_drugs_id', $item_data['who_drugs_id']);
        $query = $this->db->get('request_item');

        if ($query->num_rows() > 0) {
            // Atualizar item existente
            $this->db->where('request_id', $item_data['request_id']);
            $this->db->where('who_drugs_id', $item_data['who_drugs_id']);
            $this->db->update('request_item', array(
                'requested_quantity' => $item_data['requested_quantity']
            ));
        } else {
            // Inserir novo item
            $this->db->insert('request_item', $item_data);
        }
    }

    public function update_request_status($request_code, $updateData)
    {
        $this->db->where('request_code', $request_code);
        return $this->db->update('request', $updateData);
    }
}
