<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 29-Jun-2020
 * Time: 10:33 AM
 */
class m_medical_certificate extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'medical_certificates';
        $this->primary_key = 'id';
    }
}