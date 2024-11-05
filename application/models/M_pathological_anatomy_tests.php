<?php
/**
 * Created by Trung Hoang.
 */

class m_pathological_anatomy_tests extends MY_Model {
    public function __construct() {
        parent::__construct();
        $this->_table = 'pathological_anatomy_tests';
        $this->primary_key = 'PA_test_ID';
    }
}