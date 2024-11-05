<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Patient_Note extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_note');
        $this->load->model('m_user');
        $this->load->model('m_emergency_admission');
        $this->load->model('m_medical_history');
        $this->load->model('m_patient_examination');
        $this->load->model('ClinicalRecord');
        $this->load->model('Observation');
    }

    public function add_general_note($pid, $ref_id)
    {
        $this->add($pid, 'general', $ref_id);
    }
    public function add_nurse_note($pid, $ref_id)
    {
        $this->add($pid, 'nurse', $ref_id);
    }
    public function add_opd_note($pid, $opd_id)
    {
        $this->add($pid, 'opd', $opd_id);
    }

    public function add_adm_note($pid, $admission_id)
    {
        $this->add($pid, 'adm', $admission_id);
    }

    public function add_emr_note($pid, $emr_id)
    {
        $this->add($pid, 'emr', $emr_id);
    }

    public function add($pid, $type, $ref_id = null)
    {
        $data = $this->get_user_and_datetime();
        $data['id'] = '';
        $data['pid'] = $pid;
        $data['default_note'] = '';
        $data['default_active'] = '';
        $data['default_type'] = $type;
        $data['ref_id'] = $ref_id;
        $data['default_temperature'] = '';
        $data['default_heart_rate'] = '';
        $data['default_respiratory_frequency'] = '';
        $data['default_sys_bp'] = '';
        $data['default_diast_bp'] = '';
        $data['default_pulse'] = '';
        $data['default_oxygen_saturation'] = '';
        $data['default_pulse_value'] = '';
        $data['default_pulse_characteristics'] = '';
        $data['default_vital_signs_note'] = '';
        $data["visit_info"] = $this->m_emergency_admission->as_array()->get($ref_id);
        $this->form_validation->set_rules('note', 'Note', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean|required');
        $this->form_validation->set_rules('temperature', 'Temperature', 'trim|xss_clean|numeric|required');
        $this->form_validation->set_rules('heart_rate', 'Heart Rate', 'trim|xss_clean|numeric|required');
        $this->form_validation->set_rules('respiratory_frequency', 'Respiratory Frequency', 'trim|xss_clean|numeric|required');
        $this->form_validation->set_rules('sys_bp', 'Systolic BP', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('diast_bp', 'Diastolic BP', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('pulse', 'Pulse', 'trim|xss_clean');
        $this->form_validation->set_rules('oxygen_saturation', 'Oxygen Saturation', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('pulse_value', 'Pulse Value', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('pulse_characteristics', 'Pulse Characteristics', 'trim|xss_clean');
        $this->form_validation->set_rules('vital_signs_note', 'Vital Signs Note', 'trim|xss_clean');

        $this->form_validation->set_message('required', 'O campo %s é obrigatório.');

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $user_role = $this->get_user_role();
            $data_patient_notes = array(
                'notes' => $this->input->post('note'),
                'Active' => $this->input->post('active'),
                'Type' => $type,
                'PID' => $pid,
                'Ref_id' => $ref_id,
                'CreateUser' => $this->session->userdata('name'),
                'user_role' => $user_role,
            );
            $data_patient_exam = array(
                'PID' => $pid,
                'Ref_id' => $ref_id,
                'CreateUser' => $this->session->userdata('name'),
                'user_role' => $user_role,
                'temperature' => $this->input->post('temperature'),
                'heart_rate' => $this->input->post('heart_rate'),
                'respiratory_frequency' => $this->input->post('respiratory_frequency'),
                'sys_BP' => $this->input->post('sys_bp'),
                'diast_BP' => $this->input->post('diast_bp'),
                'pulse' => $this->input->post('pulse'),
                'oxygen_saturation' => $this->input->post('oxygen_saturation'),
                'pulse_value' => $this->input->post('pulse_value'),
                'pulse_characteristics' => $this->input->post('pulse_characteristics'),
                'vital_signs_note' => $this->input->post('vital_signs_note')
            );
            $this->m_patient_note->insert($data_patient_notes);
            $this->m_patient_examination->insert($data_patient_exam);
            $this->session->set_flashdata('msg', 'Added');
            $this->redirect_if_no_continue('patient/view/' . $pid);
        }
    }

    public function view($pid, $ref_id = null)
    {
        $data['id'] = '';
        $data['pid'] = $pid;
        $data['default_type'] = 'nurse';
        $data['ref_id'] = $ref_id;
        $data['patient_history'] = $this->loadHistory($pid, $ref_id, 'ADM');
        $data['exams'] = $this->loadExam($pid, $ref_id, 'ADM');
        $this->qch_template->load_form_layout('nurse_entry_note', $data);
    }

    public function add_nurse_entry($pid, $ref_id = null)
    {
        $data = $this->get_user_and_datetime();
        $data['id'] = '';
        $data['pid'] = $pid;
        $data['default_type'] = 'nurse';
        $data['ref_id'] = $ref_id;
        $data['default_name'] = '';
        $data['default_age'] = '';
        $data['default_gender'] = '';
        $data['default_race'] = '';
        $data['default_marital_status'] = '';
        $data['default_country'] = '';
        $data['default_residence'] = '';
        $data['default_main_complaint'] = '';
        $data['default_current_illness_history'] = '';
        $data['default_menarche'] = '';
        $data['default_menopause'] = '';
        $data['default_second_menstruation_date'] = '';
        $data['default_date_last_menstruation'] = '';
        $data['default_cycle_periodicity'] = '';
        $data['default_flow_characteristics'] = '';
        $data['default_chronic_diseases'] = '';

        $this->form_validation->set_rules('main_complaint', 'Queixa Principal', 'trim|xss_clean|required');
        $this->form_validation->set_rules('current_illness_history', 'História da Doença Atual', 'trim|xss_clean|required');
        $this->form_validation->set_rules('chronic_diseases', 'Doenças Crônicas', 'trim|xss_clean');
        $this->form_validation->set_rules('food_allergy', 'Alergia Alimentar', 'trim|xss_clean');
        $this->form_validation->set_rules('other_allergies', 'Outras Alergias', 'trim|xss_clean');
        $this->form_validation->set_rules('previous_diseases', 'Doenças Anteriores', 'trim|xss_clean');
        $this->form_validation->set_rules('menarche', 'Menarca', 'trim|xss_clean');
        $this->form_validation->set_rules('menopause', 'Menopausa', 'trim|xss_clean');
        $this->form_validation->set_rules('second_menstruation_date', 'Data da Penúltima Menstruação', 'trim|xss_clean');
        $this->form_validation->set_rules('date_last_menstruation', 'Data da Última Menstruação', 'trim|xss_clean');
        $this->form_validation->set_rules('cycle_periodicity', 'Periodicidade do Ciclo', 'trim|xss_clean');
        $this->form_validation->set_rules('flow_characteristics', 'Características do Fluxo', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('form_entry_note', $data);
        } else {
            $user_role = $this->get_user_role();
            $entry_data = array(
                'PID' => $pid,
                'Complaint' => $this->input->post('main_complaint'),
                'HistoryOfComplaint' => $this->input->post('current_illness_history'),
                'ChronicDiseases' => $this->save_chronic_diseases(),
                'AllergyMedication' => $this->input->post('medication_allergy'),
                'AllergyFood' => $this->input->post('food_allergy'),
                'OtherAllergies' => $this->input->post('other_allergies'),
                'PreviousDiseases' => $this->input->post('previous_diseases'),
                'Menarche' => $this->input->post('menarche'),
                'Menopause' => $this->input->post('menopause'),
                'SecondMenstruationDate' => $this->input->post('second_menstruation_date'),
                'DateLastMenstruation' => $this->input->post('date_last_menstruation'),
                'CyclePeriodicity' => $this->input->post('cycle_periodicity'),
                'FlowCharacteristics' => $this->input->post('flow_characteristics'),
                'CreateUser' => $this->session->userdata('name'),
                'Ref_id' => $ref_id,
                'user_role' => $user_role
            );
            $this->m_medical_history->insert($entry_data);

            $this->session->set_flashdata('msg', 'Entrada adicionada com sucesso.');
            redirect('patient_note/view/' . $pid . '/' . $ref_id);
        }
    }


    public function edit($note_id)
    {
        $note = $this->m_patient_note->get($note_id);
        if (empty($note)) {
            die('Id wrong');
        }
        $data = $this->get_user_and_datetime();
        $data['id'] = $note_id;
        $data['pid'] = $note->PID;
        $data['ref_id'] = $note->Ref_id;
        $data['default_note'] = $note->notes;
        $data['default_active'] = $note->Active;
        $data['default_type'] = $note->Type;
        $data['default_temperature'] = $note->temperature;
        $data['default_heart_rate'] = $note->heart_rate;
        $data['default_respiratory_frequency'] = $note->respiratory_frequency;
        $data['default_sys_bp'] = $note->sys_BP;
        $data['default_diast_bp'] = $note->diast_BP;
        $data['default_pulse'] = $note->pulse;
        $data['default_oxygen_saturation'] = $note->oxygen_saturation;
        $data['default_pulse_value'] = $note->pulse_value;
        $data['default_pulse_characteristics'] = $note->pulse_characteristics;
        $data['default_vital_signs_note'] = $note->vital_signs_note;

        $this->form_validation->set_rules('note', lang('Note'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', lang('Active'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('temperature', 'Temperature', 'trim|xss_clean|numeric|required');
        $this->form_validation->set_rules('heart_rate', 'Heart Rate', 'trim|xss_clean|numeric|required');
        $this->form_validation->set_rules('respiratory_frequency', 'Respiratory Frequency', 'trim|xss_clean|numeric|required');
        $this->form_validation->set_rules('sys_bp', 'Systolic BP', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('diast_bp', 'Diastolic BP', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('pulse', 'Pulse', 'trim|xss_clean');
        $this->form_validation->set_rules('oxygen_saturation', 'Oxygen Saturation', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('pulse_value', 'Pulse Value', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('pulse_characteristics', 'Pulse Characteristics', 'trim|xss_clean');
        $this->form_validation->set_rules('vital_signs_note', 'Vital Signs Note', 'trim|xss_clean');

        $this->form_validation->set_message('required', 'O campo %s é obrigatório.');

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $update_data = array(
                'notes' => $this->input->post('note'),
                'Active' => $this->input->post('active'),
                'temperature' => $this->input->post('temperature'),
                'heart_rate' => $this->input->post('heart_rate'),
                'respiratory_frequency' => $this->input->post('respiratory_frequency'),
                'sys_BP' => $this->input->post('sys_bp'),
                'diast_BP' => $this->input->post('diast_bp'),
                'pulse' => $this->input->post('pulse'),
                'oxygen_saturation' => $this->input->post('oxygen_saturation'),
                'pulse_value' => $this->input->post('pulse_value'),
                'pulse_characteristics' => $this->input->post('pulse_characteristics'),
                'vital_signs_note' => $this->input->post('vital_signs_note')
            );
            $this->m_patient_note->update($note_id, $update_data);
            $this->session->set_flashdata('msg', 'Updated');
            $this->redirect_if_no_continue('patient/view/' . $note->PID);
        }
    }

    public function get_previous_notes_list($pid, $type, $ref_id = 0, $continue, $mode)
    {
        $data = array();
        $data['type'] = $type;
        $data["previous_notes_list"] = $this->m_patient_note->as_array()->order_by('CreateDate', 'DESC')->get_many_by(array('PID' => $pid, 'Type' => $type, 'Ref_id' => $ref_id));
        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_notes_list');
        } else {
            return $data["nursing_notes_list"];
        }
    }


    public function get_nursing_notes($ref_id = 0, $date = '')
    {

        $data = [];

        $notes_date = empty($date) ? date('Y-m-d') : htmlspecialchars($date);
        $data["patient_notes"] = $this->m_patient_note->get_by_type_and_date(htmlspecialchars($ref_id), $notes_date);

        if (!empty($data) && isset($data['patient_notes'][0])) {

            $note = $data['patient_notes'][0];
            $data['pid'] = $note['PID'] ?? null;
            $data['ref_id'] = $ref_id;
            $this->load->vars($data);
            $this->load->view('nursing_diary_notes');
        }
    }
    private function get_user_and_datetime()
    {
        $data = array();

        // Obtém a data e hora atual
        $default_datetime = date('Y-m-d H:i');
        $data['default_datetime'] = $default_datetime;

        // Obtém os dados do usuário da sessão
        $data['user_data'] = array(
            'title' => $this->session->userdata('title'),
            'name' => $this->session->userdata('name'),
            'other_name' => $this->session->userdata('other_name')
        );

        return $data;
    }

    public function patient_view_note($note_id)
    {
        $examination = $this->m_patient_note->get($note_id);
        $doctor = $this->m_user->get_name_by_uid($examination->CreateUser);
        // Load the view with the examination details
        $this->load->view('patient_note/patient_view_note', array('examination' => $examination, 'doctorName' => $doctor));
    }
    private function get_user_role()
    {
        $user_group_name = $this->session->userdata('user_group_name');

        if (strpos(strtolower($user_group_name), 'nurse') !== false) {
            return 'Nurse';
        } else {
            return 'Doctor';
        }
    }

    public function nursing_diary($pid, $ref_id, $date = '')
    {
        $data = array(
            'ref_id' => $ref_id,
            'pid' => $pid,
            'date' => $date
        );
        $this->qch_template->load_form_layout('nursing_diary', $data);
    }

    public function patient_tempetarature_history($pid, $ref_id)
    {
        $data['patient_exams'] = $this->m_patient_examination->get_by_ref_id($pid, $ref_id);
        $this->load->vars($data);
        $this->load->view('patient_temperature_chart');
    }
    private function loadHistory($pid, $ref_id, $ref_type)
    {
        $user_role = $this->get_user_role();
        $history_data = $this->m_medical_history->get_patient_history_by_pid($pid, $ref_type, $user_role);
        if ($this->db->query($history_data)->num_rows() === 0) {
            return;
        }
        $this->load->model('mpager', 'history_page');
        $history_page = $this->history_page;
        $history_page->setSql($history_data);
        $history_page->setDivId("hist_cont");
        $history_page->setRowid('HID');
        $history_page->setCaption(lang("Clinic History"));
        $history_page->setShowHeaderRow(true);
        $history_page->setShowFilterRow(false);
        $history_page->setColNames(array("", lang("Date"), lang("Complaint"), lang("HistoryOfComplaint"), lang("Clinical"),  lang("Category")));
        $history_page->setRowNum(25);
        $history_page->setColOption("HID", array("hidden" => true));
        $history_page->setColOption("dte", array("hidden" => false));
        $history_page->setColOption("Complaint", array("hidden" => false));
        $history_page->setColOption("HistoryOfComplaint", array("hidden" => false));
        $history_page->setColOption("Doctor", array("hidden" => false));
        $history_page->setColOption("user_role", array("hidden" => false));

        $history_page->gridComplete_JS = "function() {
            $('#hist_cont .jqgrow').click(function() {
                var rowId = $(this).attr('id');
                $.ajax({
                    url: '" . site_url('patient_history/patient_view_history') . "/' + rowId,
                    type: 'GET',
                    success: function(data) {
                        $('#modalContent').html(data);
                        $('#myModal').modal('show');
                    }
                });
            });
        }";

        return $history_page->render(false);
    }

    private function loadExam($pid, $ref_id, $ref_type)
    {
        $exam_data = $this->m_patient_examination->get_patient_exam_by_pid($pid, $ref_type);
        if ($this->db->query($exam_data)->num_rows() === 0) {
            return;
        }
        $this->load->model('mpager', 'exam_page');
        $exam_page = $this->exam_page;
        $exam_page->setSql($exam_data);
        $exam_page->setDivId("exami_cont");
        $exam_page->setRowid('PATEXAMID');
        $exam_page->setCaption(lang("Examinations"));
        $exam_page->setShowHeaderRow(true);
        $exam_page->setShowFilterRow(false);
        $exam_page->setShowPager(false);
        $exam_page->setColNames(array("", lang("Date"), lang("Sys_BP") . ' / ' . lang("Diast_BP"), lang("Weight"), lang("Height"), lang("Temperature"), lang("Category")));
        $exam_page->setRowNum(25);
        $exam_page->setColOption("PATEXAMID", array("hidden" => true));
        $exam_page->setColOption("dte", array("hidden" => false));
        $exam_page->setColOption("bp", array("hidden" => false));
        $exam_page->setColOption("weight", array("hidden" => false));
        $exam_page->setColOption("user_role", array("hidden" => false));


        $exam_page->gridComplete_JS = "function() {
            $('#exami_cont .jqgrow').click(function() {
                var rowId = $(this).attr('id');
                $.ajax({
                    url: '" . site_url('patient_examination/patient_view_exam') . "/' + rowId,
                    type: 'GET',
                    success: function(data) {
                        $('#modalContent').html(data);
                        $('#myModal').modal('show');
                    }
                });
            });
        }";

        return $exam_page->render(false);
    }

    public function save_chronic_diseases()
    {
        $selected_chronic_diseases = $this->input->post('selected_diagnoses');
        $chronic_diseases_array = explode(',', $selected_chronic_diseases);
        $chronic_diseases_json = json_encode($chronic_diseases_array);

        return $chronic_diseases_json;
    }

    public function risk_screening($pid, $ref_id)
    {
        $data = array();
        $data['pid'] = $pid;
        $data['ref_id'] = $ref_id;

        if (!isset($_POST['submit'])) {

            $this->qch_template->load_form_layout('form_risk_screening', $data);
        } else {
            $history = $this->input->post('yes_no_hist');
            $his_classification = $this->input->post('history_classification');

            $diagnose = $this->input->post('yes_no_diag');
            $diag_classification = $this->input->post('diagnose_classification');

            $auxilar = $this->input->post('auxiliar_walk');
            $aux_classification = $this->input->post('auxiliar_classification');

            $therapy = $this->input->post('yes_no_therapy');
            $ther_classification = $this->input->post('therapy_classification');

            $walk = $this->input->post('walk');
            $walk_classification = $this->input->post('walk_classification');

            $mental = $this->input->post('mental');
            $ment_classification = $this->input->post('mental_classification');
            $creator = $this->session->userdata('uid');


            $observations = [];
            $observation1 = [];
            $observation2 = [];
            $observation3 = [];
            $observation4 = [];
            $observation5 = [];
            $observation6 = [];
            $observation1['creator'] = $creator;
            $observation1['obs_key'] = $diagnose;
            $observation1['obs_name'] = lang('Secondary Diagnose');
            $observation1['value_int'] = $diag_classification;
            $observation1['PID'] = $pid;
            $observation1['form_id'] = 1;

            $observation2['creator'] = $creator;
            $observation2['obs_key'] = $history;
            $observation2['obs_name'] = lang('Recent drop history');
            $observation2['value_int'] = $his_classification;
            $observation2['PID'] = $pid;
            $observation2['form_id'] = 1;

            $observation3['creator'] = $creator;
            $observation3['obs_key'] = $auxilar;
            $observation3['obs_name'] = lang('auxilar walk');
            $observation3['value_int'] = $aux_classification;
            $observation3['PID'] = $pid;
            $observation3['form_id'] = 1;

            $observation4['creator'] = $creator;
            $observation4['obs_key'] = $therapy;
            $observation4['obs_name'] = lang('Therapy');
            $observation4['value_int'] = $ther_classification;
            $observation4['PID'] = $pid;
            $observation4['form_id'] = 1;

            $observation5['creator'] = $creator;
            $observation5['obs_key'] = $walk;
            //$observation5['obs_key'] = 'walk';
            $observation5['obs_name'] = lang('Walk');
            $observation5['value_int'] = $walk_classification;
            $observation5['PID'] = $pid;
            $observation5['form_id'] = 1;

            $observation6['creator'] = $creator;
            $observation6['obs_key'] = $mental;
            $observation6['obs_name'] = lang('Mental Status');
            $observation6['value_int'] = $ment_classification;
            $observation6['PID'] = $pid;
            $observation6['form_id'] = 1;


            $observations = [$observation1, $observation2, $observation3, $observation4, $observation5, $observation6];

            $this->m_observation->insert_batch($observations);

            $this->redirect_if_no_continue('patient/view/' . $pid . '/' . $ref_id);
        }
    }




    public function braden_scale($pid, $ref_id)
    {
        $data = [];
        $data['pid'] = $pid;
        $data['ref_id'] = $ref_id;

        $this->form_validation->set_rules('sensory_perception', 'Percepção Sensorial', 'trim|xss_clean|required');
        $this->form_validation->set_rules('moisture', 'Humidade', 'trim|xss_clean|required');
        $this->form_validation->set_rules('activity', 'Atividade', 'trim|xss_clean|required');
        $this->form_validation->set_rules('mobility', 'Modalidade', 'trim|xss_clean|required');
        $this->form_validation->set_rules('nutrition', 'Nutrição', 'trim|xss_clean|required');
        $this->form_validation->set_rules('friction_shear', 'Fricção e Cisalhamento', 'trim|xss_clean|required');

        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('form_braden_scale', $data);
        } else {

            $obss = [];

            $post_data = $this->input->post();
            foreach ($post_data as $input_name => $value) {
                $obs = new Observation($input_name);
                $obs->setValue($value);
                $obss[] = $obs;
            }

            $record = new ClinicalRecord();

            $record->createClinicaRecord($pid, 3, $obss);


            $this->qch_template->load_form_layout('patient/view', $data);
        }
    }
}
