<?php
/**
 * Created by Cololo.
 * User: qch
 * Date: 11/21/15
 * Time: 6:50 AM
 */

class m_patient_blood_donation_result extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_blood_donation_result';
        $this->primary_key = 'patient_blood_donation_result_id';
    }
}
