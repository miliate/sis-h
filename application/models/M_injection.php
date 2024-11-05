<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 29-Oct-15
 * Time: 2:27 PM
 */
class m_injection extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'injection';
        $this->primary_key = 'injection_id';
    }
}