<?php
/**
 * This model works with information in table "sap_companies_type" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_sap_companies_patient"
 ****** file name: "M_sap_companies_patient.php"
 */
class m_sap_companies_patient extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'sap_companies_patient';
        $this->primary_key = 'id';
    }
}