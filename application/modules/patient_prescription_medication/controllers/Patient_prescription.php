<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 30-Oct-15
 * Time: 3:06 PM
 */
class Patient_Prescription extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admission');
        $this->load->model('m_opd_visit');
        $this->load->model('m_emergency_admission');
        $this->load->model('m_patient_prescription');
        $this->load->model('m_patient_prescription_have_drug');
        $this->load->model('m_patient_medication');
        $this->load->model('m_patient_medication_have_drug');
        $this->load_form_language();
    }

    public function check_pass2($pass2)
    {
        return true;
//        require 'application/config/database.php';
//        if ($pass2 != $db['default']['password_2'])
//        {
//            $this->form_validation->set_message('check_pass2', 'The password 2 you supplied does not match your existing password 2.');
//            return FALSE;
//        }
//        else {
//            return TRUE;
//        }
    }

    public function prescribe($ref_type, $ref_id)
    {
        $data['ref_type'] = strtoupper($ref_type);
        $data['ref_id'] = $ref_id;
        switch ($ref_type) {
            case 'opd':
                $data["visits_info"] = $this->m_opd_visit->get($ref_id);
                break;
            case 'emr':
                $data["visits_info"] = $this->m_emergency_admission->get($ref_id);
                break;
            case 'adm':
                $data["visits_info"] = $this->m_admission->get($ref_id);
                break;
        }
        $data["PID"] = $data["visits_info"]->PID;

//        $this->form_validation->set_rules('password2', 'Password 2', 'trim|required|callback_check_pass2');
        $this->form_validation->set_rules('drug_id_selected[]', 'Drug', 'xss_clean|required');
        $this->form_validation->set_rules('dose_id_selected[]', 'Dose', 'xss_clean|required');
        $this->form_validation->set_rules('frequency_id_selected[]', 'Frequency', 'xss_clean|required');
        $this->form_validation->set_rules('period_id_selected[]', 'Period', 'xss_clean|required');
//        $this->form_validation->set_rules('order_confirm_password', 'Order Password', 'xss_clean|callback_confirm_password_check');

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            foreach ($this->input->post('drug_id_selected') as $key => $value) {
                $drug_id_selected[$key] = $value;
            }
            foreach ($this->input->post('dose_id_selected') as $key => $value) {
                $dose_id_selected[$key] = $value;
            }
            foreach ($this->input->post('frequency_id_selected') as $key => $value) {
                $frequency_id_selected[$key] = $value;
            }
            foreach ($this->input->post('period_id_selected') as $key => $value) {
                $period_id_selected[$key] = $value;
            }
            $patient_prescription = array(
                'PID' => $data["PID"],
                'RefType' => strtoupper($ref_type),
                'RefID' => $ref_id,
                'Status' => 'Pending',
            );
            $patient_prescription_id = $this->m_patient_prescription->insert($patient_prescription);
            $patient_medication_id = $this->m_patient_medication->insert($patient_prescription);
            $drug_list = array();
            $order = 1;
            foreach ($this->input->post('drug_id_selected') as $index => $drug) {
                $drug_order = array();
                $drug_order['PID'] = $data['PID'];
                $drug_order['PrescriptionID'] = $patient_prescription_id;
                $drug_order['Order'] = $order++;
                $drug_order['DrugID'] = $drug_id_selected[$index];//The right way is not working in Ubuntu $this->input->post('drug_id_selected')[$index];
                $drug_order['DoseID'] = $dose_id_selected[$index];//$this->input->post('dose_id_selected')[$index];
                $drug_order['FrequencyID'] = $frequency_id_selected[$index];//$this->input->post('frequency_id_selected')[$index];
                $drug_order['Period'] = $period_id_selected[$index];//$this->input->post('period_id_selected')[$index];
                array_push($drug_list, $drug_order);
            }
            foreach ($drug_list as $drug_order) {
                $this->m_patient_prescription_have_drug->insert($drug_order);
            } 

            $drug_list = array();
            $order = 1;
            foreach ($this->input->post('drug_id_selected') as $index => $drug) {
                $drug_order = array();
                $drug_order['PID'] = $data['PID'];
                $drug_order['MedicationID'] = $patient_medication_id;
                $drug_order['Order'] = $order++;
                $drug_order['DrugID'] = $drug_id_selected[$index];//The right way is not working in Ubuntu $this->input->post('drug_id_selected')[$index];
                $drug_order['DoseID'] = $dose_id_selected[$index];//$this->input->post('dose_id_selected')[$index];
                $drug_order['FrequencyID'] = $frequency_id_selected[$index];//$this->input->post('frequency_id_selected')[$index];
                $drug_order['Period'] = $period_id_selected[$index];//$this->input->post('period_id_selected')[$index];
                array_push($drug_list, $drug_order);
            }
            foreach ($drug_list as $drug_order) {
                $this->m_patient_medication_have_drug->insert($drug_order);
            }
            switch ($ref_type) {
                default:
                    $this->redirect_if_no_continue('opd_visit/view/' . $ref_id);
            }
        }
    }

    public function cardex($ref_type, $ref_id)
    {
        $data['ref_type'] = strtoupper($ref_type);
        $data['ref_id'] = $ref_id;
        switch ($ref_type) {
            case 'opd':
                $data["visits_info"] = $this->m_opd_visit->get($ref_id);
                break;
            case 'emr':
                $data["visits_info"] = $this->m_emergency_admission->get($ref_id);
                break;
            case 'adm':
                $data["visits_info"] = $this->m_admission->get($ref_id);
                break;
        }
        $data["PID"] = $data["visits_info"]->PID;

//        $this->form_validation->set_rules('password2', 'Password 2', 'trim|required|callback_check_pass2');
        $this->form_validation->set_rules('drug_id_selected[]', 'Drug', 'xss_clean|required');
        $this->form_validation->set_rules('dose_id_selected[]', 'Dose', 'xss_clean|required');
        $this->form_validation->set_rules('frequency_id_selected[]', 'Frequency', 'xss_clean|required');
        $this->form_validation->set_rules('period_id_selected[]', 'Period', 'xss_clean|required');
//        $this->form_validation->set_rules('order_confirm_password', 'Order Password', 'xss_clean|callback_confirm_password_check');

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            foreach ($this->input->post('drug_id_selected') as $key => $value) {
                $drug_id_selected[$key] = $value;
            }
            foreach ($this->input->post('dose_id_selected') as $key => $value) {
                $dose_id_selected[$key] = $value;
            }
            foreach ($this->input->post('frequency_id_selected') as $key => $value) {
                $frequency_id_selected[$key] = $value;
            }
            foreach ($this->input->post('period_id_selected') as $key => $value) {
                $period_id_selected[$key] = $value;
            }
            $patient_prescription = array(
                'PID' => $data["PID"],
                'RefType' => strtoupper($ref_type),
                'RefID' => $ref_id,
                'Status' => 'Pending',
            );
            $patient_prescription_id = $this->m_patient_prescription->insert($patient_prescription);
            $patient_medication_id = $this->m_patient_medication->insert($patient_prescription);
            $drug_list = array();
            $order = 1;
            foreach ($this->input->post('drug_id_selected') as $index => $drug) {
                $drug_order = array();
                $drug_order['PID'] = $data['PID'];
                $drug_order['PrescriptionID'] = $patient_prescription_id;
                $drug_order['Order'] = $order++;
                $drug_order['DrugID'] = $drug_id_selected[$index];//The right way is not working in Ubuntu $this->input->post('drug_id_selected')[$index];
                $drug_order['DoseID'] = $dose_id_selected[$index];//$this->input->post('dose_id_selected')[$index];
                $drug_order['FrequencyID'] = $frequency_id_selected[$index];//$this->input->post('frequency_id_selected')[$index];
                $drug_order['Period'] = $period_id_selected[$index];//$this->input->post('period_id_selected')[$index];
                array_push($drug_list, $drug_order);
            }
            foreach ($drug_list as $drug_order) {
                $this->m_patient_medication_have_drug->insert($drug_order);
            } 

            $drug_list = array();
            $order = 1;
            foreach ($this->input->post('drug_id_selected') as $index => $drug) {
                $drug_order = array();
                $drug_order['PID'] = $data['PID'];
                $drug_order['MedicationID'] = $patient_medication_id;
                $drug_order['Order'] = $order++;
                $drug_order['DrugID'] = $drug_id_selected[$index];//The right way is not working in Ubuntu $this->input->post('drug_id_selected')[$index];
                $drug_order['DoseID'] = $dose_id_selected[$index];//$this->input->post('dose_id_selected')[$index];
                $drug_order['FrequencyID'] = $frequency_id_selected[$index];//$this->input->post('frequency_id_selected')[$index];
                $drug_order['Period'] = $period_id_selected[$index];//$this->input->post('period_id_selected')[$index];
                array_push($drug_list, $drug_order);
            }
            foreach ($drug_list as $drug_order) {
                $this->m_patient_medication_have_drug->insert($drug_order);
            }
            switch ($ref_type) {
                default:
                //    $this->redirect_if_no_continue('opd_visit/view/' . $ref_id);
            }
        }
    }

    public function view($prescription_id)
    {
        $this->load->model('m_who_drug');
        $this->load->model('m_drug_dosage');
        $this->load->model('m_drug_frequency');
//        $patient_prescription = $this->m_patient_prescription->get($prescription_id);

        $patient_prescription_have_drug = $this->m_patient_prescription_have_drug->get_many_by(array('PrescriptionID' => $prescription_id));
        $data['drug_list'] = array();
        foreach ($patient_prescription_have_drug as $raw_drug) {
            $tmp_data = array();
            $tmp_data['order'] = $raw_drug->Order;
            if ($raw_drug->DrugID > 0) {
                $drug = $this->m_who_drug->get($raw_drug->DrugID);
                $tmp_data['drug_info'] = $drug->name . ' ' . $drug->default_num . ' ' . $drug->formulation . '/' . $drug->dose;
            } else {
                $tmp_data['drug_info'] = '';
            }
            if ($raw_drug->DoseID > 0) {
                $tmp_data['dose'] = $this->m_drug_dosage->get($raw_drug->DoseID)->Dosage;
            } else {
                $tmp_data['dose'] = '';
            }
            if ($raw_drug->FrequencyID > 0) {
                $tmp_data['frequency'] = $this->m_drug_frequency->get($raw_drug->FrequencyID)->Frequency;
            } else {
                $tmp_data['frequency'] = '';
            }

            $tmp_data['period'] = $raw_drug->Period;
            array_push($data['drug_list'], $tmp_data);
        }
        $this->render('view_prescription', $data);
    }

    public function search()
    {
        $this->set_top_selected_menu('patient_prescription');
        $qry = "SELECT
                patient_prescription.CreateDate,
                PrescriptionID,
                RefType,
                patient.PID,
                CONCAT(patient.Firstname,' ',patient.Name) AS Patient,
                CONCAT(user.Title, ' ', user.Name,' ',user.OtherName) AS Doctor,
                patient_prescription.Status
                FROM patient_prescription
                LEFT JOIN patient ON patient.PID = patient_prescription.PID
                LEFt JOIN user ON user.UID = patient_prescription.CreateUser";
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

    public function dispense($prescription_id)
    {
        $this->load->model('m_who_drug');
        $this->load->model('m_drug_dosage');
        $this->load->model('m_drug_frequency');
        $data['patient_prescription'] = $this->m_patient_prescription->get($prescription_id);
        $data['pid'] = $data['patient_prescription']->PID;
        $data['ref_type'] = $data['patient_prescription']->RefType;
        $data['ref_id'] = $data['patient_prescription']->RefID;

        $this->form_validation->set_rules('quantity[]', 'Quantity', 'xss_clean|required');

        if ($this->form_validation->run() == FALSE) {
            $patient_prescription_have_drug = $this->m_patient_prescription_have_drug->get_many_by(array('PrescriptionID' => $prescription_id));
            $data['drug_list'] = array();
            foreach ($patient_prescription_have_drug as $raw_drug) {
                $tmp_data = array();
                $tmp_data['order'] = $raw_drug->Order;
                if ($raw_drug->DrugID > 0) {
                    $drug = $this->m_who_drug->get($raw_drug->DrugID);
                    $tmp_data['drug_info'] = $drug->name . ' ' . $drug->default_num . ' ' . $drug->formulation . ' ' . $drug->dose;
                } else {
                    $tmp_data['drug_info'] = '';
                }
                if ($raw_drug->DoseID > 0) {
                    $tmp_data['dose'] = $this->m_drug_dosage->get($raw_drug->DoseID)->Dosage;
                } else {
                    $tmp_data['dose'] = '';
                }
                if ($raw_drug->FrequencyID > 0) {
                    $tmp_data['frequency'] = $this->m_drug_frequency->get($raw_drug->FrequencyID)->Frequency;
                } else {
                    $tmp_data['frequency'] = '';
                }
                if ($data['patient_prescription']->Status === 'Dispensed') {
                    $tmp_data['quantity'] = $raw_drug->Quantity;
                }   else {
                    $tmp_data['quantity'] = '';
                }

                $tmp_data['period'] = $raw_drug->Period;
                array_push($data['drug_list'], $tmp_data);
            }
            $this->render('dispense_prescription', $data);

        } else {
            foreach ($this->input->post('quantity') as $order => $quantity) {
                $where = array('PrescriptionID' => $prescription_id, 'Order' => $order);
                $update = array('Quantity' => $quantity);
                $this->m_patient_prescription_have_drug->update_by($where, $update);
            }
            $this->m_patient_prescription->update($prescription_id, array('Status' => 'Dispensed'));
            $this->redirect_if_no_continue('patient_prescription/search');
        }
    }

    public function get_previous_prescription($ref_type, $ref_id, $continue, $mode = 'HTML')
    {
        $data = array();
        $data["previous_prescription_list"] = $this->m_patient_prescription->with('order_by')->order_by('CreateDate', 'DESC')->get_many_by(array('RefType' => $ref_type, 'RefID' => $ref_id));
        foreach ($data['previous_prescription_list'] as $prescription) {
            $prescription->prescription_have_drugs = $this->m_patient_prescription_have_drug->with('drug')->with('dose')->with('frequency')->get_many_by(array('PrescriptionID' => $prescription->PrescriptionID));
        }

        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_prescription');
        } else {
            return $data["previous_prescription_list"];
        }
    }
}