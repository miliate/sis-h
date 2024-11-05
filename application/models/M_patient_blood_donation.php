<?php
/**
 * Created by Cololo.
 * User: qch
 * Date: 11/21/15
 * Time: 6:50 AM
 */

class m_patient_blood_donation extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_blood_donation';
        $this->primary_key = 'blood_donation_id';
    }
}
