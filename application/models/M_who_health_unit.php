<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 1/11/16
 * Time: 1:18 PM
 */
class m_who_health_unit extends MY_CRUD {
    function __construct()
    {
        parent::__construct();
        $this->_table = 'who_health_units';
        $this->primary_key = 'CUUS';
    }
}