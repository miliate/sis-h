<?php

class M_drug_route_administration extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'drug_route_administration';
        $this->primary_key = 'DRAID';
    }
}