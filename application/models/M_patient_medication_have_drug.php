<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 19-Fev-2024
 * Time: 11:45 PM
 * This model works with information in table "patient_medication" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_patient_medication_have_drug"
 ****** file name: "M_patient_medication_have_drug.php"
 */
class m_patient_medication_have_drug extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_medication_have_drug';
        $this->primary_key = 'ID';
        $this->belongs_to = array(
            'drug' => array('model' => 'm_who_drug', 'primary_key' => 'DrugID'),
            'dose' => array('model' => 'm_drug_dosage', 'primary_key' => 'DoseID'),
            'frequency' => array('model' => 'm_drug_frequency', 'primary_key' => 'FrequencyID'),
        );
    }
}