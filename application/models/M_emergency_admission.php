<?php

/**
 * This model works with information in table "emergency_admission" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_emergency_admission extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'emergency_admission';
        $this->primary_key = 'EMRID';
    }

    public function get_info_by_pid($pid)
    {
        $this->db->where('PID', $pid);
        $this->db->order_by('CreateDate', 'DESC'); // Assuming 'CreateDate' is your datetime or timestamp column
        $query = $this->db->get($this->_table);

        // Check if any result is found
        if ($query->num_rows() > 0) {
            $result = $query->row(); // Get the first row of the result
            return $result->EMRID; // Return the EMRID from the first row
        } else {
            return null; // Return null if no result is found
        }
    }

    public function get_info_by_refid($ref_id)
    {
        $this->db->SELECT("*");
        $this->db->where('EMRID', $ref_id);
        $query =  $this->db->get('emergency_admission');
        return $query->result_array();
    }

    public function get_emr_id_by_pid($PID) {
        $this->db->SELECT("EMRID");
        $this->db->where('PID', $PID);
        $query =  $this->db->get('emergency_admission');
      
        if ($query->num_rows() > 0) {
            $result = $query->row(); 
            return $result->EMRID; 
        } else {
            return null; 
        }
    }
}
