<?php
/**
 * Created by Cololo.
 * User: qch
 * Date: 06/12/2018
 * Time: 14:05 PM
 */

class m_patient_processo_clinico  extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_processo_clinico';
        $this->primary_key = 'id';
    }
}
