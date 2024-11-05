<?php

class Patient_Anamnese extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_anamnese_psychological');
        $this->load->model('m_patient');
        $this->load_form_language();
    }

    public function create_adm_anamnese($pid, $ref_id)
    {
        $this->add($pid, 'ADM', $ref_id);
    }

    public function create_emr_anamnese($pid, $ref_id)
    {
        $this->add($pid, 'EMR', $ref_id);
    }

    public function create_opd_anamnese($pid, $ref_id)
    {
        $this->add($pid, 'OPD', $ref_id);
    }

    public function add($pid, $type = null, $ref_id = null)
    {
        if (!Modules::run('permission/check_permission', 'patient_history', 'create')) {
            // Handle permission error
        }
    
        $data = array();
        $data['id'] = '';
        $data['pid'] = $pid;
        $data['ref_id'] = $ref_id;
        $data['default_doctor'] = $this->session->userdata('name');
        $data['is_edit'] = false;
    
        $data['default_main_complaint'] = '';
        $data['default_mental_care'] = '';
        $data['default_family_problem'] = '';
        $data['default_specify'] = '';
        $data['default_frequency_sad'] = '';
        $data['default_frequency_anxious'] = '';
    
        $data['default_frequency_restless'] = '';
        $data['default_frequency_alcohol'] = '';
        $data['default_alcohol_quantity'] = '';
        $data['default_increased_alcohol'] = '';
        $data['default_external_influence'] = '';
        $data['default_conspiracy'] = '';
        $data['default_hearing_voices'] = '';
        $data['default_suicidal_thoughts'] = '';
        $data['default_suicide_plan'] = '';
        $data['default_evaluation_result'] = '';
        $data['default_intervention_done'] = '';
        $data['default_referred'] = '';
        $data['default_referred_to'] = '';
    
        $data['default_gender'] = $this->m_patient->get_patient_by_pid($pid)->Gender;
    

        $data_first = $this->session->userdata('first_form_data');
    
        if (!$data_first) {
            $this->form_validation->set_rules('main_complaint', 'Main Complaint', 'trim|required');
            $this->form_validation->set_rules('mental_care', 'Mental Care', 'trim|required');
            $this->form_validation->set_rules('family_problem', 'Family Problem', 'trim|required');
    
            if ($this->form_validation->run() == FALSE) {
                $this->load_form($data);
            } else {
                $data_first = array(
                    'MainComplaint' => $this->input->post('main_complaint'),
                    'MentalCare' => $this->input->post('mental_care'),
                    'FamilyProblem' => $this->input->post('family_problem'),
                    'Specify' => $this->input->post('specify'),
                    'FrequencySad' => $this->input->post('frequency_sad'),
                    'FrequencyAnxious' => $this->input->post('frequency_anxious'),
                    'FrequencyRestless' => $this->input->post('frequency_restless'),
                    'Doctor' => $this->session->userdata('name'),
                    'PID' => $pid,
                    'RefType' => $type,
                    'RefID' => $ref_id
                );
    
                if ($data_first['FrequencySad'] == 'Nunca' && 
                    $data_first['FrequencyAnxious'] == 'Nunca' && 
                    $data_first['FrequencyRestless'] == 'Nunca') {
                    $this->session->set_flashdata('msg', 'Added');
                    $this->m_patient_anamnese_psychological->insert($data_first);
                    $this->redirect_if_no_continue(site_url('/patient/view/' . $pid));
                } else {
                    $this->session->set_userdata('first_form_data', $data_first);
                    $this->qch_template->load_form_layout('second_patient_anamnese_form', array('pid' => $pid, 'ref_id' => $ref_id, 'data' => $data));
                }
            }
        } 
        else {
            $this->form_validation->set_rules('frequency_alcohol', 'Frequency of Alcohol', 'trim|required');
            $this->form_validation->set_rules('alcohol_quantity', 'Alcohol Quantity', 'trim|required');
        
            if ($this->form_validation->run() == FALSE) {
                $this->qch_template->load_form_layout('second_patient_anamnese_form', array('pid' => $pid, 'ref_id' => $ref_id, 'data' => $data));
            } else {
                $data_second = array(
                    'FrequencyAlcohol' => $this->input->post('frequency_alcohol'),
                    'AlcoholQuantity' => $this->input->post('alcohol_quantity'),
                    'IncreasedAlcohol' => $this->input->post('increased_alcohol'),
                    'ExternalInfluence' => $this->input->post('external_influence'),
                    'Conspiracy' => $this->input->post('conspiracy'),
                    'HearingVoices' => $this->input->post('hearing_voices'),
                    'SuicidalThoughts' => $this->input->post('suicidal_thoughts'),
                    'SuicidePlan' => $this->input->post('suicide_plan'),
                    'EvaluationResult' => $this->input->post('evaluation_result'),
                    'InterventionDone' => $this->input->post('intervention_done'),
                    'Referred' => $this->input->post('referred'),
                    'ReferredTo' => $this->input->post('referred_to')
                );
    
                $data_combined = array_merge($data_first, $data_second);
                $this->m_patient_anamnese_psychological->insert($data_combined);
    
                $this->session->unset_userdata('first_form_data');
                $this->session->set_flashdata('msg', 'Added');
                $this->redirect_if_no_continue(site_url('/patient/view/' . $pid));
            }
        }
    }
    
    public function get_previous_anamnese($pid, $continue, $mode = 'HTML')
    {
        $data["patient_anamnese_list"] =  $this->m_patient_anamnese_psychological->get_patient_anamnese_psychological_by_pid($pid);
        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_anamnese');
        } else {
            return $data["patient_anamnese_list"];
        }
    }
    
    
    public function patient_view_anamnese($anamneseid)
    {
        $anamnese = $this->m_patient_anamnese_psychological->get($anamneseid);
        $this->load->view('patient_anamnese/patient_view_anamnese', array('anamnese' => $anamnese, 'pid' => $anamnese->PID, 'ref_id' => $anamnese->RefID));
    }


        public function edit($anamneseid)
    {
        // Check if the user has permission to edit
        if (!Modules::run('permission/check_permission', 'patient_history', 'edit')) {
            die('Você não tem permissão!');
        }

        // Fetch the existing anamnesis data
        $anamnese = $this->m_patient_anamnese_psychological->get($anamneseid);
        if (empty($anamnese)) {
            die('Anamnese não encontrada');
        }

        // Check if the user is the one who created the record and if it is within 24 hours
        $currentUser = $this->session->userdata('uid');
        $createDate = new DateTime($anamnese->CreateDate);
        $currentDate = new DateTime();
        $interval = $currentDate->diff($createDate);
        $canEdit = ($anamnese->CreateUser === $currentUser) && ($interval->h < 24 && $interval->days == 0);

        // Populate data to be sent to the form
        $data = array();
        $data['id'] = $anamneseid;
        $data['pid'] = $anamnese->PID;
        $data['default_main_complaint'] = $anamnese->MainComplaint;
        $data['default_mental_care'] = $anamnese->MentalCare;
        $data['default_family_problem'] = $anamnese->FamilyProblem;
        $data['default_specify'] = $anamnese->Specify;
        $data['default_frequency_sad'] = $anamnese->FrequencySad;
        $data['default_frequency_anxious'] = $anamnese->FrequencyAnxious;
        $data['default_frequency_restless'] = $anamnese->FrequencyRestless;
        $data['default_frequency_alcohol'] = $anamnese->FrequencyAlcohol;
        $data['default_alcohol_quantity'] = $anamnese->AlcoholQuantity;
        $data['default_increased_alcohol'] = $anamnese->IncreasedAlcohol;
        $data['default_external_influence'] = $anamnese->ExternalInfluence;
        $data['default_conspiracy'] = $anamnese->Conspiracy;
        $data['default_hearing_voices'] = $anamnese->HearingVoices;
        $data['default_suicidal_thoughts'] = $anamnese->SuicidalThoughts;
        $data['default_suicide_plan'] = $anamnese->SuicidePlan;
        $data['default_evaluation_result'] = $anamnese->EvaluationResult;
        $data['default_intervention_done'] = $anamnese->InterventionDone;
        $data['default_referred'] = $anamnese->Referred;
        $data['default_referred_to'] = $anamnese->ReferredTo;
        $data['default_doctor'] = $anamnese->Doctor;
        
        // Add a flag to control field editability
        $data['can_edit'] = $canEdit;

        // Define form validation rules
        $this->form_validation->set_rules('main_complaint', 'Main Complaint', 'trim|required');
        $this->form_validation->set_rules('mental_care', 'Mental Care', 'trim|required');
        $this->form_validation->set_rules('family_problem', 'Family Problem', 'trim|required');
        $this->form_validation->set_rules('frequency_sad', 'Frequency of Sadness', 'trim|required');
        $this->form_validation->set_rules('frequency_anxious', 'Frequency of Anxiety', 'trim|required');
        $this->form_validation->set_rules('frequency_restless', 'Frequency of Restlessness', 'trim|required');
        $this->form_validation->set_rules('frequency_alcohol', 'Alcohol Consumption Frequency', 'trim|required');
        $this->form_validation->set_rules('alcohol_quantity', 'Alcohol Consumption Quantity', 'trim|required');
        $this->form_validation->set_rules('increased_alcohol', 'Increased Alcohol Consumption', 'trim|required');
        $this->form_validation->set_rules('external_influence', 'External Influence on Thoughts', 'trim|required');
        $this->form_validation->set_rules('conspiracy', 'Belief in Conspiracy Theories', 'trim|required');
        $this->form_validation->set_rules('hearing_voices', 'Hearing Voices', 'trim|required');
        $this->form_validation->set_rules('suicidal_thoughts', 'Suicidal Thoughts', 'trim|required');
        $this->form_validation->set_rules('suicide_plan', 'Suicide Plan', 'trim|required');
        $this->form_validation->set_rules('evaluation_result', 'Evaluation Result', 'trim|required');
        $this->form_validation->set_rules('intervention_done', 'Intervention Done', 'trim|required');
        $this->form_validation->set_rules('referred', 'Referred', 'trim|required');
        $this->form_validation->set_rules('referred_to', 'Referred To', 'trim|required');

        // Run form validation
        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form with existing data
            $this->qch_template->load_form_layout('patient_view_anamnese', array('data' => $data, 'pid' => $anamnese->PID, 'ref_id' => $anamnese->RefID));
        } else {
            // Gather data from form submission
            $update_data = array(
                'MainComplaint' => $this->input->post('main_complaint'),
                'MentalCare' => $this->input->post('mental_care'),
                'FamilyProblem' => $this->input->post('family_problem'),
                'Specify' => $this->input->post('specify'),
                'FrequencySad' => $this->input->post('frequency_sad'),
                'FrequencyAnxious' => $this->input->post('frequency_anxious'),
                'FrequencyRestless' => $this->input->post('frequency_restless'),
                'FrequencyAlcohol' => $this->input->post('frequency_alcohol'),
                'AlcoholQuantity' => $this->input->post('alcohol_quantity'),
                'IncreasedAlcohol' => $this->input->post('increased_alcohol'),
                'ExternalInfluence' => $this->input->post('external_influence'),
                'Conspiracy' => $this->input->post('conspiracy'),
                'HearingVoices' => $this->input->post('hearing_voices'),
                'SuicidalThoughts' => $this->input->post('suicidal_thoughts'),
                'SuicidePlan' => $this->input->post('suicide_plan'),
                'EvaluationResult' => $this->input->post('evaluation_result'),
                'InterventionDone' => $this->input->post('intervention_done'),
                'Referred' => $this->input->post('referred'),
                'ReferredTo' => $this->input->post('referred_to'),
                'Doctor' => $this->session->userdata('name')
            );

            // Update the database record
            $this->m_patient_anamnese_psychological->update($anamneseid, $update_data);

            // Set a flash message and redirect back to the patient's view
            $this->session->set_flashdata('msg', 'Anamnese Atualizada com Sucesso');
            redirect(site_url('/patient/view/' . $anamnese->PID));
        }
    }


}
