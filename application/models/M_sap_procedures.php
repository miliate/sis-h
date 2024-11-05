<?php
/**
 * This model works with information in table "sap_procedures" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_sap_procedures extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'sap_procedures';
        $this->primary_key = 'id';
        $this->belongs_to = array(
            'group' => array('model' => 'sap_procedure_type', 'primary_key' => 'type_id')
            );
    }


}