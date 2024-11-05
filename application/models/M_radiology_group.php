<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 05-Nov-15
 * Time: 10:02 AM
 */
class m_radiology_group extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'radiology_group';
        $this->primary_key = 'Id';
    }
}