<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 19-Sep-2020
 * Time: 11:03 PM
 */
class m_sap_bill_item extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'sap_bill_item';
        $this->primary_key = 'id';
        $this->belongs_to = array(
            'group' => array('model' => 'sap_bill', 'primary_key' => 'item_id')
            );
    }
}