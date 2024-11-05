<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12/04/19
 * Time: 08:20 PM
 */
class m_discharge_outcome extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'discharge_outcome';
        $this->primary_key = 'id';
    }
}
