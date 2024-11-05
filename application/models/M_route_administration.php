<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 30-Oct-15
 * Time: 4:43 PM
 */
class m_route_administration extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'drug_route_administration';
        $this->primary_key = 'DRAID';
    }
}