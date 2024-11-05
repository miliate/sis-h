<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 29-Oct-15
 * Time: 2:27 PM
 */
class m_patient_soap extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_soap';
        $this->primary_key = 'patient_soap_id';
    }
}