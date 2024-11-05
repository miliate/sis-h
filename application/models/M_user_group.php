<?php
/**
 * This model works with information in table "user_group" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_user_group extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'user_group';
        $this->primary_key = 'UGID';
    }
}