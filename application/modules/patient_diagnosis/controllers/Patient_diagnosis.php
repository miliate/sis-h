<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Patient_diagnosis extends FormController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_diagnosis');
        $this->load->model('m_icd10');
        $this->load->model('m_emergency_admission');
        $this->load->model('m_user');
        $this->load_form_language();

        $this->option_diagnosis_type_1 = [
            '1' => lang('Tentative'),
            '2' => lang('Confirmed')
        ];

        $this->option_diagnosis_type_2 = array(
            '3' => lang('New'),
            '4' => lang('Repeat')
        );
    }

    public function create_diagnosis_for_statistic($pid)
    {
        $this->create($pid, 'STATISTIC', NULL);
    }

    public function get_diagnosis($search)
    {
        $icd10 = $this->m_icd10->get_names_by_prefix($search);

        echo json_encode($icd10);
    }


    public function create_adm_diagnosis($adm_id)
    {
        $this->load->model('m_admission');
        $visit = $this->m_admission->get($adm_id);
        $pid = $visit->PID;
        $this->create($pid, 'ADM', $adm_id);
    }

    public function create_emr_diagnosis($emr_id)
    {
        $this->load->model('m_emergency_admission');
        $emr_visit = $this->m_emergency_admission->get($emr_id);
        $pid = $emr_visit->PID;
        $this->create($pid, 'EMR', $emr_id);
    }

    public function create_opd_diagnosis($opd_id)
    {
        $this->load->model('m_opd_visit');
        $opd_visit = $this->m_opd_visit->get($opd_id);
        $pid = $opd_visit->PID;
        $this->create($pid, 'OPD', $opd_id);
    }

    public function get_all_icd10()
    {
        $res = array();
        $res[""] = "--------------";
        foreach ($this->m_icd10->get_all() as $icd) {
            $res[$icd->ICDID] = $icd->Code . ' ' . $icd->Name;
        }
        return $res;
    }




    public function create($pid, $ref_type, $ref_id)
    {
        $data = array();
        $data['id'] = 0;
        $data['default_diagnosis'] = '';
        $data['default_diagnosis_type_1'] = '';
        $data['default_diagnosis_type_2'] = '';
        $data['default_visit_date'] = '';
        $data['pid'] = $pid;
        $data['is_edit'] = false;
        $data['default_date'] = date('Y-m-d');
        $data['default_time'] = date('H:i:s');
        $data['default_doctor'] = $this->session->userdata('name') . ' ' .$this->session->userdata('other_name');
        $data['ref_type'] = $ref_type;
        $data['ref_id'] = $ref_id;
        switch($ref_type) {
            case ('ADM'):
                $data['admission'] = $this->m_admission->as_array()->get($ref_id);
                break;
            case ('EMR');
            $data['visit_info']=$this->m_emergency_admission->get_info_by_refid($ref_id)[0];
                break;
            case ('OPD'):
                $data["opd_visits_info"] = $this->m_opd_visit->as_array()->get($ref_id);
                $data["is_discharged"] = $data["opd_visits_info"]["discharge_order"];
                break;
        }

        $data['option_diagnosis_type_1'] = $this->option_diagnosis_type_1;

        $last_diagnosis = $this->m_patient_diagnosis->get_last_diagnosis_id_by_refid($ref_id);
        $next_id = isset($last_diagnosis->new_diagnisi_id) ? $last_diagnosis->new_diagnisi_id + 1 : 1;
        $selected_treatments = json_decode($this->input->post('selected_treatments'), true);

        if (is_array($selected_treatments)) {
            foreach ($selected_treatments as $treatment) {

                $data = array(
                    'PID' => $pid,
                    'RefType' => $ref_type,
                    'RefId' => $ref_id,
                    'diagnosis' => $treatment['remarks'],
                    'new_diagnisi_id' => $next_id,
                    'diagnosis_type_1' => $treatment['diagnosis'],
                    'VisitDate' => $this->input->post('visit_date'),
                );
                $this->m_patient_diagnosis->insert($data);
            }
    
            $this->session->set_flashdata('msg', 'Created');
            $this->redirect_if_no_continue('patient/view/' . $pid);
        } else {
            $this->load_form($data);
        }
    }

    public function edit_created($id)
    {
        $patient_diagnosis = $this->m_patient_diagnosis->get($id);
        if (empty($patient_diagnosis))
            die('Id not exist');
        $data['id'] = $id;
        $dateTime = new DateTime($patient_diagnosis->CreateDate);
        $data['default_date'] =  $dateTime->format('Y-m-d');
        $data['default_time'] = $dateTime->format('H:i:s');
        $data['default_doctor'] = $this->m_user->get_name_by_uid($patient_diagnosis->CreateUser);
        $data['default_diagnosis'] = ($patient_diagnosis->diagnosis_id !== null) ? $this->m_icd10->get_name_by_id($patient_diagnosis->diagnosis_id)->Name : $patient_diagnosis->diagnosis;
        $data['default_diagnosis_type_1'] = $this->m_patient_diagnosis->get_diagnosis_name((int)$patient_diagnosis->diagnosis_type_1)[0]['Name'];
        $data['default_visit_date'] = $patient_diagnosis->VisitDate;

        $createDate = strtotime($patient_diagnosis->CreateDate);
        $currentTime = time();
        $editTimeLimit = 86400;

        $data['pid'] = $patient_diagnosis->PID;
        $data['ref_type'] = $patient_diagnosis->RefType;
        $data['ref_id'] = $patient_diagnosis->RefID;

        switch($data['ref_type']) {
            case ('ADM'):
                $data['admission'] = $this->m_admission->as_array()->get($data['ref_id']);
                break;
            case ('EMR');
            $data['visit_info']=$this->m_emergency_admission->get_info_by_refid($data['ref_id'])[0];
                break;
            case ('OPD'):
                $data["opd_visits_info"] = $this->m_opd_visit->as_array()->get($data['ref_id']);
                $data["is_discharged"] = $data["opd_visits_info"]["discharge_order"];
                break;
        }

        $created_by = $this->m_patient_diagnosis->get_created_by($id);
        $current_user_id = $this->session->userdata('uid');
        $data['is_edit'] = true; // !(($current_user_id == $created_by)&&(($currentTime - $createDate) < $editTimeLimit));

        // $diagnosis_name = $this->m_icd10->get_name_by_code($patient_diagnosis->diagnosis_id);

        $data['option_diagnosis_type_1'] = $this->option_diagnosis_type_1;
        $data['option_diagnosis_type_2'] = $this->option_diagnosis_type_2;

        $this->set_common_validation();

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'diagnosis_id' => $this->input->post('diagnosis'),
                'diagnosis_type_1' => $this->input->post('diagnosis_type_1'),
                'diagnosis_type_2' => $this->input->post('diagnosis_type_2'),
                'VisitDate' => $this->input->post('visit_date'),
            );
            $this->m_patient_diagnosis->update($id, $data);
            $this->session->set_flashdata(
                'msg',
                'Updated'
            );
            $ref_type = $patient_diagnosis->RefType;
            $ref_id = $patient_diagnosis->RefID;

            $this->redirect_if_no_continue('/home');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('diagnosis', lang('Direct Diagnosis'), 'required');
        $this->form_validation->set_rules('diagnosis_type_1', lang('Type'), 'required');
        $this->form_validation->set_rules('diagnosis_type_2', lang('Type'), 'required');
        //        $this->form_validation->set_rules('order_confirm_password', 'Order Password', 'xss_clean|callback_confirm_password_check');
    }

    public function get_previous_diagnosis($ref_type, $ref_id, $continue, $mode = 'HTML')
    {
        $data = array();
        $data["previous_diagnosis_list"] = $this->m_patient_diagnosis->order_by('CreateDate', 'DESC')->get_many_by(array('RefType' => $ref_type, 'RefID' => $ref_id));
        // var_dump($data["previous_diagnosis_list"]);
        foreach ($data["previous_diagnosis_list"] as $key => $diagnosis) {
            // var_dump($data["previous_diagnosis_list"]);
            if (!empty($diagnosis->diagnosis_type_1)) {
                $diagnosis_name = $this->m_patient_diagnosis->get_diagnosis_name($diagnosis->diagnosis_type_1)[0]['Name'];
                $data["previous_diagnosis_list"][$key]->diagnosis_type_1_name = $diagnosis_name;
            }
            if($data["previous_diagnosis_list"][$key]->diagnosis_id) {
                $diagnosis_icd10 = $this->m_icd10->get_name_by_id($data["previous_diagnosis_list"][$key]->diagnosis_id)->Name;
                $data["previous_diagnosis_list"][$key]->diagnosis_icd10 = $diagnosis_icd10;
            }
        }
        $data['continue'] = $continue;
        $data['option_diagnosis_type_1'] = $this->option_diagnosis_type_1;
        $data['option_diagnosis_type_2'] = $this->option_diagnosis_type_2;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_diagnosis');
        } else {
            return $data["previous_diagnosis_list"];
        }
    }

    public function get_previous_statistic_diagnosis($PID, $continue, $mode = 'HTML')
    {
        $data = array();
        $data["previous_diagnosis_list"] = $this->m_patient_diagnosis->with('diagnosis')->order_by('CreateDate', 'DESC')->get_many_by(array('PID' => $PID, 'RefType' => 'STATISTIC'));
        $data['continue'] = $continue;
        $data['option_diagnosis_type_1'] = $this->option_diagnosis_type_1;
        $data['option_diagnosis_type_2'] = $this->option_diagnosis_type_2;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_diagnosis');
        } else {
            return $data["previous_diagnosis_list"];
        }
    }

    public function view($id) {
        $diagnosis = $this->m_patient_diagnosis->as_array()->get($id);
        if ($diagnosis["diagnosis_id"] != null) {
            $diagnosis["name"] = $this->m_icd10->get_name_by_id($diagnosis["diagnosis_id"]);
        }
        $diagnosis["type"] = $this->m_patient_diagnosis->get_diagnosis_name(intval($diagnosis["diagnosis_type_1"]))[0]['Name'];
        $diagnosis["doctor"] = $this->m_user->get_name_by_uid($diagnosis["CreateUser"]);
        $this->load->view('patient_diagnosis/patient_view_diagnosis', array('diagnosis' => $diagnosis));
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
