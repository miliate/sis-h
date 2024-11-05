<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12/8/15
 * Time: 1:13 PM
 */
class m_refer_to_adm extends MY_CRUD {
    function __construct() {
        parent::__construct ();
        $this->_table = 'refer_to_adm';
        $this->primary_key = 'ID';
    }
}