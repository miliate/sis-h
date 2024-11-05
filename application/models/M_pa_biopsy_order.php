<?php
/**
 * Created by Trung Hoang.
 * This model works with information in table "pa_biopsy_order" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_pa_biopsy_order extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'pa_biopsy_order';
        $this->primary_key = 'biopsy_ID';
    }

}