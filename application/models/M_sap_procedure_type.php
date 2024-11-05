<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 01-Jul-2020
 * Time: 06:02 PM
 */
class m_sap_procedure_type extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'sap_procedure_type';
        $this->primary_key = 'id';
       
    }
}