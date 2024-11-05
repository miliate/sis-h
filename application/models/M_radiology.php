<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 1/12/16
 * Time: 2:04 PM
 */
class m_radiology extends MY_CRUD {
    function __construct() {
        parent::__construct ();
        $this->_table = 'radiology';
        $this->primary_key = 'radiology_id';
    }
}