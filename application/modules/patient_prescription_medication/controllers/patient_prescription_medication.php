<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 01-APR-2024
 * Time: 06:20 PM
 */
class Patient_Prescription_Medication extends FormController
{
        public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admission');
        $this->load->model('m_patient_prescription');
        $this->load->model('m_patient_prescription_have_drug');
        $this->load->model('m_patient_medication');
        $this->load->model('m_patient_medication_have_drug');
        $this->load_form_language();
    }


function index()
    {
        $this->set_top_selected_menu('patient_prescription');
        $qry = "SELECT
                m_patient_medication.CreateDate,
                PrescriptionID,
                RefType,
                patient.PID,
                CONCAT(patient.Firstname,' ',patient.Name) AS Patient,
                CONCAT(user.Title, ' ', user.Name,' ',user.OtherName) AS Doctor,
                m_patient_medication.Status
                FROM m_patient_medication
                LEFT JOIN patient ON patient.PID = m_patient_medication.PID
                LEFt JOIN user ON user.UID = m_patient_medication.CreateUser";
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('PrescriptionID');
        $page->setCaption('');
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array(lang("Time"), lang("Order ID"), lang("Department"), lang("Patient ID"), lang("Name"), lang("Doctor"), lang("Status")));
        $page->setRowNum(25);
        $page->setColOption("CreateDate", $page->getDateSelector(date('Y-m-d')));
        $page->setColOption('PrescriptionID', array("hidden" => true));
        $page->setColOption('PID', array('width' => '50'));
        $page->setColOption('RefType', array('stype' => 'select',
            'editoptions' => array(
                'value' => ':All;EMR:EMR;OPD:OPD;ADM:ADM'
            ), 'width' => '50'));
        $page->setColOption('Status', array('stype' => 'select',
            'editoptions' => array(
                'value' => ':All;Pending:Pending;Dispensed:Dispensed'
            ), 'width' => '70'));
        $page->setAfterInsertRow('function(rowid, data){
        var alertText = \'\';
        for (property in data) {
            alertText +=data[property];
        }
        if (alertText.match(/^.*Pending/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
        }
        if (alertText.match(/^.*Dispensed/))
        {
            $(\'#\'+rowid).css({\'background\':\'#7deaea\'});
        }
       }');
        $page->gridComplete_JS
            = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                window.location='" . site_url("/patient_prescription/dispense") . "/'+rowId+'';
            });
            }";
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $this->load->vars($data);
        $this->qch_template->load_form_layout('search');
    }

}

