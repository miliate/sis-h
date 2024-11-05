<?php
/**
 * Created by Trung Hoang.
 */
class m_pa_cytology_order extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'pa_cytology_order';
        $this->primary_key = 'cytology_ID';
    }

}