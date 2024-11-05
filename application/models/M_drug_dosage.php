<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 30-Oct-15
 * Time: 4:43 PM
 */
class m_drug_dosage extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'drugs_dosage';
        $this->primary_key = 'DDSGID';
    }
}