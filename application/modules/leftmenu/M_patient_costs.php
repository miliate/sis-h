<?php
/**
 * Created by JCOLOLO.
 * User: qch
 * Date: 05/Jan/18
 * Time: 10:50 AM
 */

class m_patient_costs extends MY_Model {
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_costs';
        $this->primary_key = 'id';
    }
}
