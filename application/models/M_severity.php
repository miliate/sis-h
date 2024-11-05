<?php
/**
 * Created by @jordao.cololo.
 * User: kivegun
 * Date: 11/7/16
 * Time: 8:31 PM
 */
class m_severity extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'severity';
        $this->primary_key = 'SEVEID';
    }

}