<?php
/**
 * Created by Jordao Cololo, on 2nd Dec 2022.
 * This model works with information in table "operation_order" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_operation_order"
 ****** file name: "M_operation_order.php"
 */
class m_operation_order extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'operation_order';
        $this->primary_key = 'OP_order_ID';
        $this->belongs_to = array(
            'Doctor' => array('model' => 'm_user', 'primary_key' => 'Doctor')
        );
    }

}