<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 11/19/15
 * Time: 11:00 AM
 */

class m_permission extends MY_CRUD {
    function __construct() {
        parent::__construct ();
        $this->_table = 'permission';
        $this->primary_key = 'PERID';
    }
}