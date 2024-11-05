<?php
/**
 * This model works with information in table "top_menu" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_top_menu extends MY_CRUD {
    function __construct() {
        parent::__construct ();
        $this->_table = 'top_menu';
        $this->primary_key = 'MID';
    }
}