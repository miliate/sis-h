<?php
/**
 * Created by Trung Hoang.
 * This model works with information in table "pathological_anatomy_order" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_pathological_anatomy_order extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'pathological_anatomy_order';
        $this->primary_key = 'PA_order_ID';
        $this->belongs_to = array(
            'collected_by' => array('model' => 'm_user', 'primary_key' => 'SampleCollectedBy')
        );
    }

}