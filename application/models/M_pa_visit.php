<?php
/**
 * This model works with information in table "pa_visit" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_pa_visit extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'pa_visits';
        $this->primary_key = 'PA_ID';
        $this->belongs_to = array(
            'Doctor' => array('model' => 'm_user', 'primary_key' => 'Doctor')
        );
    }

}