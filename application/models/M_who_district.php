<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 1/11/16
 * Time: 1:18 PM
 */
class m_who_district extends MY_CRUD {
    function __construct()
    {
        parent::__construct();
        $this->_table = 'who_districts';
        $this->primary_key = 'district_code';
    }
}