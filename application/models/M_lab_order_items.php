<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 05-Nov-15
 * Time: 4:02 PM
 */
class m_lab_order_items extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'lab_order_items';
        $this->primary_key = 'LAB_ORDER_ITEM_ID';
        $this->belongs_to = array('lab_test' => array('model' => 'm_lab_test', 'primary_key' => 'LABID'));
    }
}