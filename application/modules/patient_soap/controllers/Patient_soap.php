<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Patient_soap extends FormController
{

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_soap');
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
        $this->load->model('m_emergency_admission');
        $emr_visit = $this->m_emergency_admission->get($emr_id);
        $pid = $emr_visit->PID;
        $this->create($pid, 'EMR', $emr_id);
    }

    public function create_opd_soap($opd_id)
    {
        $this->load->model('m_opd_visit');
        $opd_visit = $this->m_opd_visit->get($opd_id);
        $pid = $opd_visit->PID;
        $this->create($pid, 'OPD', $opd_id);
    }

    public function create($pid, $ref_type, $ref_id)
    {
        $data = array();
        $data['id'] = 0;
        $data['default_subjective'] = '';
        $data['default_objective'] = '';
        $data['default_assessment'] = '';
        $data['default_plan'] = '';
        $data['pid'] = $pid;

        $data['ref_type'] = $ref_type;
        $data['ref_id'] = $ref_id;

        switch ($ref_type) {
            case ('ADM'):
                $data['admission'] = $this->m_admission->as_array()->get($ref_id);
                break;
            case ('EMR');
                $data['visit_info'] = $this->m_emergency_admission->get_info_by_refid($ref_id)[0];
                break;
            case ('OPD'):
                $data["opd_visits_info"] = $this->m_opd_visit->as_array()->get($ref_id);
                $data["is_discharged"] = $data["opd_visits_info"]["discharge_order"];
                break;
        }

        $this->set_common_validation();

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'PID' => $pid,
                'RefType' => $ref_type,
                'RefId' => $ref_id,
                'subjective' => $this->input->post('subjective'),
                'objective' => $this->input->post('objective'),
                'assessment' => $this->input->post('assessment'),
                'plan' => $this->input->post('plan'),
            );
            $id = $this->m_patient_soap->insert($data);
            $this->session->set_flashdata(
                'msg',
                'Created'
            );
            $this->redirect_if_no_continue('patient/view/' . $ref_id);
        }
    }

    public function edit_created($id)
    {
        $patient_soap = $this->m_patient_soap->get($id);
        if (empty($patient_soap))
            die('Id not exist');
        $data['id'] = $id;
        $data['default_subjective'] = $patient_soap->subjective;
        $data['default_objective'] = $patient_soap->objective;
        $data['default_assessment'] = $patient_soap->assessment;
        $data['default_plan'] = $patient_soap->plan;

        $data['pid'] = $patient_soap->PID;

        $data['ref_type'] = $patient_soap->RefType;
        $data['ref_id'] = $patient_soap->RefID;

        $this->set_common_validation();

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'subjective' => $this->input->post('subjective'),
                'objective' => $this->input->post('objective'),
                'assessment' => $this->input->post('assessment'),
                'plan' => $this->input->post('plan'),
            );
            $this->m_patient_soap->update($id, $data);
            $this->session->set_flashdata(
                'msg',
                'Updated'
            );
            $ref_type = $patient_soap->RefType;
            $ref_id = $patient_soap->RefID;

            $this->redirect_if_no_continue('/home');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('subjective', 'subjective', 'trim');
        //        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');
        //        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
        //        $this->form_validation->set_rules('order_confirm_password', 'Order Password', 'xss_clean|callback_confirm_password_check');
    }

    public function get_previous_soap($ref_type, $ref_id, $continue, $mode = 'HTML')
    {
        $data = array();
        $data["previous_soap_list"] = $this->m_patient_soap->order_by('CreateDate', 'DESC')->get_many_by(array('RefType' => $ref_type, 'RefID' => $ref_id));
        $data['continue'] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_soap');
        } else {
            return $data["previous_soap_list"];
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
                'value' => ':All;Pending:Pending;Done:Done'
            )
        ));
        $page->setAfterInsertRow('function(rowid, data){
        var alertText = \'\';
        for (property in data) {
            alertText +=data[property];
        }
        if (alertText.match(/^.*Pending/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
        }
        if (alertText.match(/^.*Done/))
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
