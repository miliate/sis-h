<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 1/12/16
 * Time: 2:04 PM
 */
class m_radiology_order extends MY_CRUD {
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct() {
        parent::__construct ();
        $this->_table = 'radiology_order';
        $this->primary_key = 'radiology_order_id';
        $this->belongs_to = array(
//            'group' => array('model' => 'm_lab_test_group', 'primary_key' => 'TestGroupID'),
            'order_by' => array('model' => 'm_user', 'primary_key' => 'OrderBy')
        );
    }
}