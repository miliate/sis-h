<?php
/**
 * Created by Trung Hoang.
 */
class m_pa_cv_cytology_order extends MY_CRUD {
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct() {
        parent::__construct();
        $this->_table = 'pa_cv_cytology_order';
        $this->primary_key = 'cv_cytology_ID';
    }

}