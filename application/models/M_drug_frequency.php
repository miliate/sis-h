<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 30-Oct-15
 * Time: 4:48 PM
 */
class m_drug_frequency extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'drugs_frequency';
        $this->primary_key = 'DFQYID';
    }
}