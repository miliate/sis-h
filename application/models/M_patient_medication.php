<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 19-Fev-2024
 * Time: 11:45 PM
 * This model works with information in table "patient_medication" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_patient_medication"
 ****** file name: "M_patient_medication.php"
 */
class m_patient_medication extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_medication';
        $this->primary_key = 'MedicationID';
        $this->belongs_to = array(
            'order_by' => array('model' => 'm_user', 'primary_key' => 'CreateUser'),
        );
    }
}