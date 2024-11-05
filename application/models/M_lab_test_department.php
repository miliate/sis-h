<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 05-Nov-15
 * Time: 10:02 AM
 */
class m_lab_test_department extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'lab_test_department';
        $this->primary_key = 'LABDEPTID';
    }
}