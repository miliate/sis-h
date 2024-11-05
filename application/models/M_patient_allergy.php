<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 22-Oct-15
 * Time: 11:07 PM
 */
class m_patient_allergy extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_allergy';
        $this->primary_key = 'ALLERGYID';
    }
}