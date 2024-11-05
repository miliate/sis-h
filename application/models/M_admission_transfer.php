<?php
/**
 * This model works with information in table "admission_transfer" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_admission_transfer extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'admission_transfer';
        $this->primary_key = 'ADTR';
        $this->belongs_to = array(
            'transfer_from' => array('model' => 'm_ward', 'primary_key' => 'TransferFrom'),
            'transfer_to' => array('model' => 'm_ward', 'primary_key' => 'TransferTo'),
        );
    }
}