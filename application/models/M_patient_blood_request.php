<?php

class M_patient_blood_request extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_blood_request';
        $this->primary_key = 'id';
    }

}