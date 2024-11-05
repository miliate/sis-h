<?php
/**
 * This model works with information in table "lab_tests" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_lab_test extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'lab_tests';
        $this->primary_key = 'LABID';
        $this->belongs_to = array(
            'group' => array('model' => 'm_lab_test_group', 'primary_key' => 'GroupID'),
            'department' => array('model' => 'm_lab_test_department', 'primary_key' => 'DepID'
        ));
    }
}