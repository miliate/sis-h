<?php
/**
 * This model works with information in table "opd_visits" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_opd_visit extends MY_CRUD {
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct() {
        parent::__construct();
        $this->_table = 'opd_visits';
        $this->primary_key = 'OPDID';
        $this->belongs_to = array(
            'Doctor' => array('model' => 'm_user', 'primary_key' => 'Doctor')
        );
    }
    public function get_info_by_refid($ref_id)
    {
        $this->db->SELECT("*");
        $this->db->where('OPDID', $ref_id);
        $query =  $this->db->get('opd_visits');
        return $query->result_array()[0];
    }
}