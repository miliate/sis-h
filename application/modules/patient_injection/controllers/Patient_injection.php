<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Patient_Injection extends FormController
{

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_injection');
        $this->load->model('m_opd_visit');
        $this->load->model('m_emergency_admission');
        $this->load->model('m_injection');
        $this->load_form_language();
    }

    private function get_list_injection()
    {
        $all_injection = $this->m_injection->get_many_by(array('Active' => true));
        $result = array();
        foreach ($all_injection as $injection) {
            $result[$injection->injection_id] = $injection->name;
        }
        return $result;
    }

    public function create_adm_injection($adm_id)
    {
        $this->load->model('m_admission');
        $visit = $this->m_admission->get($adm_id);
        $pid = $visit->PID;
        $this->create($pid, 'ADM', $adm_id);
    }

    public function create_emr_injection($emr_id)
    {
        $emr_visit = $this->m_emergency_admission->get($emr_id);
        $pid = $emr_visit->PID;
        $this->create($pid, 'EMR', $emr_id);
    }

    public function create_opd_injection($opd_id)
    {
        $opd_visit = $this->m_opd_visit->get($opd_id);
        $pid = $opd_visit->PID;
        $this->create($pid, 'OPD', $opd_id);
    }

    public function create($pid, $ref_type, $ref_id)
    {
        $data = array();
        $data['id'] = 0;
        $data['injection_options'] = $this->get_list_injection();
        $data['default_injection'] = '';
        $data['default_remarks'] = '';
        $data['default_active'] = '';
        $data['pid'] = $pid;

        $data['ref_type'] = $ref_type;
        $data['ref_id'] = $ref_id;

        switch ($ref_type) {
            case ('ADM'):
                $data['admission'] = $this->m_admission->get_info_by_refid($ref_id);
                break;
            case ('EMR');
                $data["visit_info"] = $this->m_emergency_admission->get_info_by_refid($ref_id);
                break;
            case ('OPD'):
                $data["opd_visits_info"] = $this->m_opd_visit->get_info_by_refid($ref_id);
                $data["is_discharged"] = $data["opd_visits_info"]["discharge_order"];
                break;
        }
        $this->form_validation->set_rules('password2', lang('Second Password'), 'trim|required|callback_check_pass2');

        $this->set_common_validation();

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'PID' => $pid,
                'RefType' => $ref_type,
                'RefId' => $ref_id,
                'injection_id' => $this->input->post('injection'),
                'Active' => $this->input->post('active'),
                'remarks' => $this->input->post('remarks'),
                'status' => 'Pending',
                //                'order_by_id' => $this->input->post('order_confirm_user')
            );
            $id = $this->m_patient_injection->insert($data);
            $this->session->set_flashdata(
                'msg',
                'Created'
            );
            $this->redirect_if_no_continue('patient/view/' . $ref_id);
        }
    }

    public function check_pass2($pass2)
    {
        require 'application/config/database.php';
        if ($pass2 != $db['default']['password_2']) {
            $this->form_validation->set_message('check_pass2', lang('Wrong password'));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function edit_created($id)
    {
        $injection_patient = $this->m_patient_injection->get($id);
        if (empty($injection_patient))
            die('Id not exist');
        $data['id'] = $id;
        $data['injection_options'] = $this->get_list_injection();
        $data['default_injection'] = $injection_patient->injection_id;
        $data['default_active'] = $injection_patient->Active;
        $data['default_remarks'] = $injection_patient->remarks;

        $data['pid'] = $injection_patient->PID;

        $data['ref_type'] = $injection_patient->RefType;
        $data['ref_id'] = $injection_patient->RefID;

        $this->set_common_validation();

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'injection_id' => $this->input->post('injection'),
                'Active' => $this->input->post('active'),
                'remarks' => $this->input->post('remarks'),
                'order_by_id' => $this->input->post('order_confirm_user')
            );
            $this->m_patient_injection->update($id, $data);
            $this->session->set_flashdata(
                'msg',
                'Updated'
            );
            $ref_type = $injection_patient->RefType;
            $ref_id = $injection_patient->RefID;

            $this->redirect_if_no_continue('/patient_injection/search');
        }
    }

    public function edit_status($id)
    {
        $patient_injection = $this->m_patient_injection->with('injection')->get($id);
        if (empty($patient_injection))
            die('Id not exist');
        $data['pid'] = $patient_injection->PID;
        $data['id'] = $id;
        $data['default_injection'] = $patient_injection->injection->name;
        $data['default_status'] = $patient_injection->status;
        $data['default_remarks'] = $patient_injection->remarks;
        $data['default_dosage'] = $patient_injection->injection->dosage;

        $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->render('form_patient_injection_status', $data);
        } else {
            $data = array(
                'Status' => $this->input->post('status'),
                'Remarks' => $this->input->post('remarks'),
            );
            $this->m_patient_injection->update($id, $data);
            $this->session->set_flashdata(
                'msg',
                'Updated'
            );
            $this->redirect_if_no_continue('/patient_injection/search');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('injection', lang('Injection'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', lang('Remarks'), 'trim|xss_clean');
        $this->form_validation->set_rules('active', lang('Active'), 'trim|xss_clean');
        //        $this->form_validation->set_rules('order_confirm_password', 'Order Password', 'xss_clean|callback_confirm_password_check');
    }

    public function get_previous_injection($pid, $continue, $mode = 'HTML')
    {
        $data = array();
        $data["previous_injection_list"] = $this->m_patient_injection->with('injection')->order_by('CreateDate', 'DESC')->get_many_by(array('PID' => $pid, 'Active' => true));
        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_injection');
        } else {
            return $data["previous_injection_list"];
        }
    }

    public function search()
    {
        $department = $this->session->userdata('department');
        $qry = "SELECT
                patient_injection.CreateDate,
                patient_injection_id,
                patient_injection.RefType,
                patient.PID,
                CONCAT(patient.Name,' ',patient.OtherName) AS Patient,
                injection.name,
                injection.dosage,
                Status
                FROM patient_injection
                LEFT JOIN injection ON injection.injection_id = patient_injection.injection_id
                LEFT JOIN patient ON patient.PID = patient_injection.PID
                WHERE (patient_injection.Active = 1) AND patient_injection.RefType = '" . $department . "'";
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('patient_injection_id');
        $page->setCaption("");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array(lang("Time"), lang("Order ID"), lang("Department"), lang("Patient ID"), lang("Patient Name"), "Injection", "Dosage", lang("Status")));
        $page->setRowNum(25);
        $page->setColOption("CreateDate", $page->getDateSelector(date('Y-m-d')));
        $page->setColOption("patient_injection_id", array("search" => true, "hidden" => false, "width" => "100"));
        $page->setColOption("PID", array("search" => true, "hidden" => false, "width" => "100"));
        $page->setColOption('Status', array(
            'stype' => 'select',
            'editoptions' => array(
                'value' => ':' . lang('All') . ';' . lang('Pending') . ':' . lang('Pending') . ';' . lang('Done') . ':' . lang('Done')
            )
        ));
        $page->setAfterInsertRow('function(rowid, data){
        var alertText = \'\';
        for (property in data) {
            alertText +=data[property];
        }
        if (alertText.match(/^.*Pending/) || alertText.match(/^.*Pendente/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
        }
        if (alertText.match(/^.*Done/) || alertText.match(/^.*Concluido/))
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
                window.location='" . site_url("/patient_injection/edit_status/") . "/'+rowId+'';
            });
            }";
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $this->render_search($data);
    }
}
