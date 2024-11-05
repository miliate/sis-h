<?php
/**
 * Created by @jordao.cololo.
 * User: qch
 * Date: 11/21/15
 * Time: 6:50 AM
 */

class m_doctor extends MY_Model {
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp */
    public function __construct() {
        parent::__construct();
        $this->_table = 'doctor';
        $this->primary_key = 'Doctor_ID';
    }
}
