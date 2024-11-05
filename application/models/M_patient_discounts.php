<?php

class m_patient_discounts extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_discounts';
        $this->primary_key = 'id';
    }
}