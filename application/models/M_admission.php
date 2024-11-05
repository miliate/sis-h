<?php
/**
 * This model works with information in table "admission" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_admission extends MY_CRUD {
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct() {
        parent::__construct();
        $this->_table = 'admission';
        $this->primary_key = 'ADMID';

        $this->belongs_to = array(
            'Doctor' => array('model' => 'm_user', 'primary_key' => 'Doctor'),
        );
    }

    public function return_all($pid) {
        $this->db->select('*');
        $this->db->from('admission');
        $this->db->where('PID', $pid);
        $query = $this->db->get();
    
        // Check if any row is returned, if not return null
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }
    
    public function get_info_by_refid($ref_id)
    {
        $this->db->SELECT("*");
        $this->db->where('ADMID', $ref_id);
        $query =  $this->db->get('admission');
        return $query->result_array()[0];
    }
}