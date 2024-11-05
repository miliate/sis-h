<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Patient_History extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_medical_history');
        $this->load->model('m_patient_active_list');
        $this->load->model('m_emergency_admission');
        $this->load->model('m_who_drug');
        $this->load->model('m_icd10');
        $this->load->model('m_patient');
        $this->load_form_language();
    }

    public function create_adm_history($pid, $ref_id)
    {
        $this->add($pid, 'ADM', $ref_id);
    }

    public function create_emr_history($pid, $ref_id)
    {
        $this->add($pid, 'EMR', $ref_id);
    }

    public function create_opd_history($pid, $ref_id)
    {
        $this->add($pid, 'OPD', $ref_id);
    }

    public function add($pid, $type = null, $ref_id = null)
    {
        if (!Modules::run('permission/check_permission', 'patient_history', 'create'))
            die('You do not have permission!');
        $data = array();
        $data['id'] = '';
        $data['pid'] = $pid;
        $data['ref_id'] = $ref_id;
        $data['default_family_history'] = '';
        $data['default_travel_history'] = '';
        $data['default_remarks'] = '';
        $data['default_doctor'] = $this->session->userdata('name');
        $data['default_active'] = '';
        $data['is_edit'] = false;
        $data['default_history_of_complaint'] = '';
        $data['default_complaint'] = '';
        $data['default_chronic_diseases'] = '';
        $data['default_previous_diseases'] = '';
        $data['default_allergy_medication'] = '';
        $data['default_other_allergies'] = '';
        $data['default_allergy_food'] ='';
        $data['default_alcohol_habits'] = '';
        $data['default_smoking_habits'] ='';
        $data['default_menarche'] ='';
        $data['default_menopause'] = '';
        $data['default_date_last_menstruation'] ='';
        $data['default_second_menstruation_date'] ='';
        $data['default_flow_characteristics'] = '' ;
        $data['default_cycle_periodicity'] = '';
        $data['default_general_complaints'] = '';
        $data['default_gastrointestinal'] = '';
        $data['default_genitourinary'] = '';
        $data['default_nervous_system'] = '';
        $data['default_hematolymphopoietic_system'] = '';
        $data['default_osteo_mio_articular'] ='';
        $data['default_endocrine_system'] ='';
        $data['default_respiratory_cardiovascular'] = '';
        $data['default_drug_select'] = '';
        $data['default_drug_select_id'] = '';
        $data['default_dietary_history'] = '';
        $data['gender'] = $this->m_patient->get_patient_by_pid($pid)->Gender;

        $this->form_validation->set_rules('active', 'Examination Date', 'trim|required');
        $this->form_validation->set_rules('complaint', lang('Main Complaint'), 'trim|required');
        $this->form_validation->set_rules('history_of_complaint',lang('Current Illness History'), 'trim|required');
        $this->form_validation->set_rules('dietary_history',lang('Diet History'), 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Complaint' => $this->input->post('complaint'),
                'HistoryOfComplaint' => $this->input->post('history_of_complaint'),
                'GeneralComplaints' => $this->input->post('general_complaints'),
                'Gastrointestinal' => $this->input->post('gastrointestinal'),
                'Genitourinary' => $this->input->post('genitourinary'),
                'NervousSystem' => $this->input->post('nervous_system'),
                'HematolymphopoieticSystem' => $this->input->post('hematolymphopoietic_system'),
                'OsteoMioArticular' => $this->input->post('osteo-mio-articular'),
                'EndocrineSystem' => $this->input->post('endocrine_system'),
                'RespiratoryCardiovascular' => $this->input->post('respiratory_cardiovascular'),
                'ChronicDiseases' => $this->save_data(),
                'PreviousDiseases' => $this->input->post('previous_diseases'),
                'AllergyMedication' => $this->input->post('drug_select'),
                'OtherAllergies' => $this->input->post('other_allergies'),
                'AllergyFood' => $this->input->post('allergy_food'),
                'AlcoholHabits' => $this->input->post('alcohol_habits'),
                'SmokingHabits' => $this->input->post('smoking_habits'),
                'Menarche' => $this->input->post('menarche'),
                'Menopause' => $this->input->post('menopause'),
                'DateLastMenstruation' => $this->input->post('date_last_menstruation'),
                'SecondMenstruationDate' => $this->input->post('second_menstruation_date'),
                'FlowCharacteristics' => $this->input->post('flow_characteristics'),
                'CyclePeriodicity' => $this->input->post('cycle_periodicity'),
                'FamilyHistory' => $this->input->post('family_history'),
                'TravelHistory' => $this->input->post('travel_history'),
                'DietHistory' => $this->input->post('dietary_history'),
                'Remarks' => $this->input->post('remarks'),
                'Doctor' => $this->input->post('doctor'),
                'Active' => $this->input->post('active'),
                'PID' => $pid,
                'Ref_type' => $type,
                'Ref_id' => $ref_id
            );

            $data_emr = array(
                'Complaint' => $this->input->post('complaint')
            );
            $this->m_medical_history->insert($data);
            $emrid = $this->m_emergency_admission->get_info_by_pid($pid);
            $this->m_emergency_admission->update($emrid,$data_emr);

            $this->session->set_flashdata(
                'msg', 'Added'
            );
           // $this->redirect_if_no_continue(site_url('/patient/view/' . $pid));
           $this->redirect_if_no_continue(site_url('/patient_examination/add/' . $pid));
          // $this->redirect_if_no_continue('emergency_visit/add_observe/' . $this->m_patient_active_list->get_last_visit_id_by_pid($pid));
           
        }
    }

    public function save_data()
    {
        $selected_chronic_diseases = $this->input->post('selected_diagnoses');
        $chronic_diseases_array = explode(',', $selected_chronic_diseases);
        $chronic_diseases_json = json_encode($chronic_diseases_array);

        return $chronic_diseases_json;
    }

    public function edit($history_id) {
        if (!Modules::run('permission/check_permission', 'patient_history', 'edit')) {
            die('You do not have permission!');
        }

        $history = $this->m_medical_history->get($history_id);
        if (empty($history)) {
            die('History ID not found');
        }
        
        $data = array();
        $data['id'] = $history_id;
        $data['pid'] = $history->PID;
        $data['ref_id'] = $history->Ref_type;
        
        $data['default_complaint'] = !empty($history->Complaint) ? $history->Complaint : lang('No Records');
        $data['default_history_of_complaint'] = !empty($history->HistoryOfComplaint) ? $history->HistoryOfComplaint : lang('No Records');
        $data['default_other_complaint'] = !empty($history->OtherComplaint) ? $history->OtherComplaint : lang('No Records');
        $data['default_general_complaints'] = !empty($history->GeneralComplaints) ? $history->GeneralComplaints : lang('No Records');
        $data['default_gastrointestinal'] = !empty($history->Gastrointestinal) ? $history->Gastrointestinal : lang('No Records');
        $data['default_genitourinary'] = !empty($history->Genitourinary) ? $history->Genitourinary : lang('No Records');
        $data['default_nervous_system'] = !empty($history->NervousSystem) ? $history->NervousSystem : lang('No Records');
        $data['default_hematolymphopoietic_system'] = !empty($history->HematolymphopoieticSystem) ? $history->HematolymphopoieticSystem : lang('No Records');
        $data['default_osteo_mio_articular'] = !empty($history->OsteoMioArticular) ? $history->OsteoMioArticular : lang('No Records');
        $data['default_endocrine_system'] = !empty($history->EndocrineSystem) ? $history->EndocrineSystem : lang('No Records');
        $data['default_respiratory_cardiovascular'] = !empty($history->RespiratoryCardiovascular) ? $history->RespiratoryCardiovascular : lang('No Records');

        $diagnosis_name = $this->m_icd10->get_name_by_code($history->ChronicDiseases);
        $data['default_chronic_diseases'] = !empty($diagnosis_name) ? $diagnosis_name : lang('No Records');
        
        $data['default_previous_diseases'] = !empty($history->PreviousDiseases) ? $history->PreviousDiseases : lang('No Records');
        $data['default_drug_select'] = !empty($history->AllergyMedication) ? $history->AllergyMedication : lang('No Records');
        $data['default_other_allergies'] = !empty($history->OtherAllergies) ? $history->OtherAllergies : lang('No Records');
        $data['default_allergy_food'] = !empty($history->AllergyFood) ? $history->AllergyFood : lang('No Records');
        $data['default_alcohol_habits'] = !empty($history->AlcoholHabits) ? $history->AlcoholHabits : lang('No Records');
        $data['default_smoking_habits'] = !empty($history->SmokingHabits) ? $history->SmokingHabits : lang('No Records');
        $data['default_menarche'] = !empty($history->Menarche) ? $history->Menarche : lang('No Records');
        $data['default_menopause'] = !empty($history->Menopause) ? $history->Menopause : lang('No Records');
        $data['default_date_last_menstruation'] = !empty($history->DateLastMenstruation) ? $history->DateLastMenstruation : lang('No Records');
        $data['default_second_menstruation_date'] = !empty($history->SecondMenstruationDate) ? $history->SecondMenstruationDate : lang('No Records');
        $data['default_flow_characteristics'] = !empty($history->FlowCharacteristics) ? $history->FlowCharacteristics : lang('No Records');
        $data['default_cycle_periodicity'] = !empty($history->CyclePeriodicity) ? $history->CyclePeriodicity : lang('No Records');
        $data['default_family_history'] = !empty($history->FamilyHistory) ? $history->FamilyHistory : lang('No Records');
        $data['default_dietary_history'] = !empty($history->DietHistory) ? $history->DietHistory : lang('No Records');
        $data['default_travel_history'] = !empty($history->TravelHistory) ? $history->TravelHistory : lang('No Records');
        $data['default_remarks'] = !empty($history->Remarks) ? $history->Remarks : lang('No Records');
        $data['default_doctor'] = $history->Doctor;
        $data['default_active'] = $history->Active;
        // Recupera o user_id do usuário atual da sessão
        $current_user_id = $this->session->userdata('uid');

        // Verifica se já se passaram mais de 30 segundos desde a criação
        $createDate = strtotime($history->CreateDate);
        $currentTime = time();
        $editTimeLimit = 86400; // 86.400 segundos o mesmo que 24h de limite para edição

 
        // Recupera o user_id do criador do registro médico
        $created_by = $this->m_medical_history->get_created_by($history_id);
        
        // Recupera o user_id do usuário atual da sessão
        $current_user_id = $this->session->userdata('uid');

        // Verificação de permissão
            // $data['is_edit'] = !(($current_user_id == $created_by)&&(($currentTime - $createDate) < $editTimeLimit));
            $data['is_edit'] = true;

            $this->form_validation->set_rules('active', 'Examination Date', 'trim|xss_clean|required');
    
            if ($this->form_validation->run() == FALSE) {
                $this->load_form($data);
            } else {
                $update_data = array(
                    'Complaint' => $this->input->post('complaint'),
                    'HistoryOfComplaint' => $this->input->post('history_of_complaint'),
                    'GeneralComplaints' => $this->input->post('general_complaints'),
                    'Gastrointestinal' => $this->input->post('gastrointestinal'),
                    'Genitourinary' => $this->input->post('genitourinary'),
                    'NervousSystem' => $this->input->post('nervous_system'),
                    'HematolymphopoieticSystem' => $this->input->post('hematolymphopoietic_system'),
                    'OsteoMioArticular' => $this->input->post('osteo-mio-articular'),
                    'EndocrineSystem' => $this->input->post('endocrine_system'),
                    'RespiratoryCardiovascular' => $this->input->post('respiratory_cardiovascular'),
                    'ChronicDiseases' => $this->input->post('chronic_diseases'),
                    'PreviousDiseases' => $this->input->post('previous_diseases'),
                    'AllergyMedication' => $this->input->post('drug_select'),
                    'OtherAllergies' => $this->input->post('other_allergies'),
                    'AllergyFood' => $this->input->post('allergy_food'),
                    'AlcoholHabits' => $this->input->post('alcohol_habits'),
                    'SmokingHabits' => $this->input->post('smoking_habits'),
                    'Menarche' => $this->input->post('menarche'),
                    'Menopause' => $this->input->post('menopause'),
                    'DateLastMenstruation' => $this->input->post('date_last_menstruation'),
                    'SecondMenstruationDate' => $this->input->post('second_menstruation_date'),
                    'FlowCharacteristics' => $this->input->post('flow_characteristics'),
                    'CyclePeriodicity' => $this->input->post('cycle_periodicity'),
                    'FamilyHistory' => $this->input->post('family_history'),
                    'DietHistory' => $this->input->post('dietary_history'),
                    'TravelHistory' => $this->input->post('travel_history'),
                    'Remarks' => $this->input->post('remarks'),
                    'Doctor' => $this->input->post('doctor'),
                    'Active' => $this->input->post('active')
                );
                $this->m_medical_history->update($history_id, $update_data);
                $this->session->set_flashdata('msg', 'Updated');
                $this->redirect_if_no_continue(site_url('/patient/view/' . $history->PID));
            }


        }

  public function load_history($pid)
    {
        $qry
            = "SELECT HID, SUBSTRING(CreateDate,1,10) as dte, Doctor
	FROM medical_history
	where (PID ='" . $pid . "') and (Active = 1)";
        $this->load->model('mpager', 'history_page');
        $history_page = $this->history_page;
        $history_page->setSql($qry);
        $history_page->setDivId("his_cont"); //important
        $history_page->setDivClass('');
        $history_page->setRowid('HID');
        $history_page->setCaption("History");
        $history_page->setShowHeaderRow(false);
        $history_page->setShowFilterRow(false);
        $history_page->setShowPager(false);
        $history_page->setColNames(array("PID", "", ""));
        $history_page->setRowNum(25);
        $history_page->setColOption("HID", array("search" => false, "hidden" => true));
        $history_page->setColOption("dte", array("search" => false, "hidden" => false, "width" => 70));
        $history_page->setColOption("Doctor", array("search" => false, "hidden" => false, "width" => 70));
        $history_page->gridComplete_JS = "function() {
        $('#his_cont .jqgrow').mouseover(function(e) {
            var rowId = $(this).attr('id');
            $(this).css({'cursor':'pointer'});
        }).mouseout(function(e){
        }).click(function(e){
            var rowId = $(this).attr('id');
           window.location='" . site_url("patient_history/edit") . "/'+rowId+'?CONTINUE=patient/view/" . $pid . "';
        });
        }";
        $history_page->setOrientation_EL("L");
        echo $history_page->render(false);
    }

    public function get_previous_history($pid, $continue, $mode = 'HTML')
    {
        $data["patient_history_list"] =  $this->m_medical_history->get_patient_history($pid);
        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_history');
        } else {
            return $data["patient_history_list"];
        }
    }
    public function patient_view_history($patexamid)
{
    $examination = $this->m_medical_history->get($patexamid);

    // Load the view with the examination details
    $this->load->view('patient_history/patient_view_history', array('examination' => $examination));
}
}