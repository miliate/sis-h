<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 1/12/16
 * Time: 2:04 PM
 */
class m_radiology_order_have_radiology extends MY_CRUD {
    function __construct() {
        parent::__construct ();
        $this->_table = 'radiology_order_have_radiology';
        $this->primary_key = 'id';
        $this->belongs_to = array(
            'radiology' => array('model' => 'm_radiology', 'primary_key' => 'radiology_id'),
        );
    }
}