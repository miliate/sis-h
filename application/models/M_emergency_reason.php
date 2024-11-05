<?php
/**
 * Created by @jordao.cololo.
 * User: HCQ
 * Date: 28-Nov-16
 * Time: 2:44 PM
 */
class m_emergency_reason extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_emr_reasons';
        $this->primary_key = 'PEMRRID';
    }
}