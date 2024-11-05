<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 29-Oct-15
 * Time: 4:13 PM
 */
class m_patient_injection extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_injection';
        $this->primary_key = 'patient_injection_id';
        $this->belongs_to = array('injection' => array('model' => 'm_injection', 'primary_key' => 'injection_id'));
    }
}