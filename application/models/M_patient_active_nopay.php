<?php
/**
 * Created by JCOLOLO.
 * User: qch
 * Date: 14/Fev/19
 * Time: 11:54 AM
 */

class m_patient_active_nopay extends MY_Model {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_active_nopay';
        $this->primary_key = 'id';
    }
}
