<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_who_drug_adjustment extends MY_CRUD {

    public function __construct() {
        parent::__construct();
        $this->_table = 'who_drug_adjustments';
        $this->primary_key = 'adjustment_id';
    }

    /**
     * Insert a new adjustment record.
     *
     * @param array $data Data to be inserted
     * @return int|bool Insert ID or false on failure
     */
    public function insert_adjustment($data) {
        $this->db->insert($this->_table, $data);
        return $this->db->insert_id();
    }

    /**
     * Get adjustment details by adjustment ID.
     *
     * @param int $adjustment_id Adjustment ID
     * @return object|null Adjustment details or null if not found
     */
    public function get_adjustment($adjustment_id) {
        $query = $this->db->get_where($this->_table, array('adjustment_id' => $adjustment_id));
        return $query->row();
    }

    /**
     * Update an existing adjustment record.
     *
     * @param int $adjustment_id Adjustment ID
     * @param array $data Data to be updated
     * @return bool True on success, false on failure
     */
    public function update_adjustment($adjustment_id, $data) {
        $this->db->where('adjustment_id', $adjustment_id);
        return $this->db->update($this->_table, $data);
    }

    /**
     * Delete an adjustment record.
     *
     * @param int $adjustment_id Adjustment ID
     * @return bool True on success, false on failure
     */
    public function delete_adjustment($adjustment_id) {
        return $this->db->delete($this->_table, array('adjustment_id' => $adjustment_id));
    }

    /**
     * Get adjustments by drug ID.
     *
     * @param int $wd_id Drug ID
     * @return array List of adjustments
     */
    public function get_adjustments_by_wd_id($wd_id) {
        $this->db->where('wd_id', $wd_id);
        $query = $this->db->get($this->_table);
        return $query->result();
    }
}
