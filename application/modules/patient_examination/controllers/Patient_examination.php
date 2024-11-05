<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Patient_Examination extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_examination');
        $this->load->model('m_emergency_admission');
        $this->load_form_language();
    }

    public function create_adm_examination($pid, $ref_id)
    {
        $this->add($pid, 'ADM', $ref_id);
    }

    public function create_emr_examination($pid, $ref_id)
    {
        $this->add($pid, 'EMR', $ref_id);
    }

    public function create_opd_examination($pid, $ref_id)
    {
        $this->add($pid, 'OPD', $ref_id);
    }
    public function create_inw_nurse_examination($pid, $ref_id = null) {
        $data['id'] = '';
        $data['pid'] = $pid;
        $data['default_type'] = 'nurse';
        $data['ref_id'] = $ref_id;
        $data['default_eye_opening'] = ''; 
        $data['default_verbal_response'] = '';
        $data['default_motor_response'] = '';
        $data['default_general_status'] = '';
        $data['default_biotype'] = '';
        $data['default_weight'] = '';
        $data['default_height'] = '';
        $data['default_imc'] = '';
        $data['default_temperature'] = '';
        $data['default_heart_rate'] = '';
        $data['default_respiratory_frequency'] = '';
        $data['default_sys_bp'] = '';
        $data['default_diast_bp'] = '';
        $data['default_pulse'] = '';
        $data['default_skin'] = '';
        $data['default_mucous'] = '';
        $data['default_skin'] = '';
        $data['default_mucous'] = '';
        $data['default_body_hair'] = '';
        $data['default_nails'] = '';
        $data['default_skull'] = '';
        $data['default_hair'] = '';
        $data['default_paranasal_sinuses'] = '';
        $data['default_eyes'] = '';
        $data['default_ears'] = '';
        $data['default_nose'] = '';
        $data['default_mouth'] = '';
        $data['default_neck'] = '';
        $data['default_thorax'] = '';
        $data['default_respiratory_exam'] = '';
        $data['default_cardiovascular_exam'] = '';
        $data['default_abdomen'] = '';
        $data['default_lower_limbs'] = '';
        $data['default_neurological_exams'] = '';
        $data['default_pulse_value'] = '';
        $data['default_pulse_characteristics'] = '';

        $this->form_validation->set_rules('weight', 'Weight', 'numeric');
        $this->form_validation->set_rules('height', 'Height', 'trim|xss_clean');
        $this->form_validation->set_rules('sys_bp', 'Systolic BP', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('diast_bp', 'Diastolic BP', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('temperature', 'Temperature', 'trim|xss_clean');
        $this->form_validation->set_rules('heart_rate', 'Heart Rate', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('respiratory_frequency', 'Respiratory Frequency', 'trim|xss_clean');
    
        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('form_nurse_examination', $data);

        } else {
            $user_role = $this->get_user_role();
            $ref_type= $this->session->userdata('department');
            $data = array(
                'PID' => $pid,
                'Ref_id' => $ref_id,
                'Ref_type' => $ref_type,
                'user_role' => $user_role,
                'EyeOpening' => $this->input->post('eye_opening'),     
                'VerbalResponse' => $this->input->post('verbal_response'), 
                'MotorResponse' => $this->input->post('motor_response'),   
                'general_status' => $this->input->post('general_status'),   
                'Biotype' => $this->input->post('biotype'),                 
                'Weight' => $this->input->post('weight'),                   
                'Height' => $this->input->post('height'),                   
                'IMC' => $this->input->post('imc'),                         
                'Temperature' => $this->input->post('temperature'),         
                'heart_rate' => $this->input->post('heart_rate'),           
                'respiratory_frequency' => $this->input->post('respiratory_frequency'), 
                'sys_BP' => $this->input->post('sys_bp'),                   
                'diast_BP' => $this->input->post('diast_bp'),               
                'pulse' => $this->input->post('pulse'),                     
                'Skin' => $this->input->post('skin'),                       
                'Mucous' => $this->input->post('mucous'),                   
                'BodyHair' => $this->input->post('body_hair'),             
                'Nails' => $this->input->post('nails'),                     
                'Skull' => $this->input->post('skull'),                     
                'Hair' => $this->input->post('hair'),                       
                'ParanasalSinuses' => $this->input->post('paranasal_sinuses'), 
                'Eyes' => $this->input->post('eyes'),                       
                'Ears' => $this->input->post('ears'),                       
                'Nose' => $this->input->post('nose'),                       
                'Mouth' => $this->input->post('mouth'),                     
                'Neck' => $this->input->post('neck'),                       
                'Thorax' => $this->input->post('thorax'),                   
                'RespiratoryExam' => $this->input->post('respiratory_exam'), 
                'CardiovascularExam' => $this->input->post('cardiovascular_exam'), 
                'abdomen' => $this->input->post('abdomen'),                 
                'LowerLimbs' => $this->input->post('lower_limbs'),         
                'neurological_exams' => $this->input->post('neurological_exams'),
                'pulse_value' => $this->input->post('pulse_value'),
                'pulse_characteristics' => $this->input->post('pulse_characteristics'),
            );
            $this->m_patient_examination->insert($data);
            redirect('patient_note/view/' . $pid . '/' . $ref_id);

        }
    }
    

    public function add($pid, $type = null, $ref_id = null)
    {
        $data = array();
        $data['id'] = '';
        $data['is_edit'] = false;
        $data['pid'] = $pid;
        $data['ref_id'] = $ref_id;
        $data['default_exam_date'] = date("Y-m-d H:i:s");
        $data['default_weight'] = '';
        $data['default_height'] = '';
        $data['default_sys_bp'] = '';
        $data['default_diast_bp'] = '';
        $data['default_temperature'] = '';
        $data['default_heart_rate'] = '';
        $data['default_respiratory_frequency'] = '';
        $data['default_active'] = '';
        $data['default_general_status'] = '';
        $data['default_biotype'] = '';
        $data['default_glasgow_score'] = '';
        $data['default_skin'] = '';
        $data['default_mucous'] = '';
        $data['default_body_hair'] = '';
        $data['default_nails'] = '';
        $data['default_skull'] = '';
        $data['default_hair'] = '';
        $data['default_paranasal_sinuses'] = '';
        $data['default_eyes'] = '';
        $data['default_ears'] = '';
        $data['default_nose'] = '';
        $data['default_mouth'] = '';
        $data['default_neck'] = '';
        $data['default_thorax'] = '';
        $data['default_respiratory_exam'] = '';
        $data['default_cardiovascular_exam'] = '';
        $data['default_abdomen'] = '';
        $data['default_lower_limbs'] = '';
        $data['default_neurological_exams'] = '';
        $data['default_remarks'] = '';
        $data['default_imc'] = '';
        $data['default_pulse'] = '';
        $data['default_members'] = '';
        $data['default_altura'] = '';
        $data['default_peso'] = '';
        $data['default_biotipo'] = '';
        $data['default_estado_geral'] = '';
        $data['default_motor_response'] = '';
        $data['default_verbal_response'] = '';
        $data['default_eye_opening'] = '';
        $data['default_pulse_value'] = '';
        $data['default_pulse_characteristics'] = '';

        $this->form_validation->set_rules('password2', 'Password 2', 'trim|required|callback_check_pass2');

        $this->form_validation->set_rules('examination_date', 'Examination Date', 'trim|xss_clean|required');
        $this->form_validation->set_rules('weight', 'Weight', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('height', 'Height', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('sys_bp', 'sys BP', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('diast_bp', 'diast BP', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('temperature', 'Temperature', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'ExamDate' => $this->input->post('examination_date'),
                'Weight' => $this->input->post('weight'),
                'Height' => $this->input->post('height'),
                'sys_BP' => $this->input->post('sys_bp'),
                'diast_BP' => $this->input->post('diast_bp'),
                'Temperature' => $this->input->post('temperature'),
                'heart_rate' => $this->input->post('heart_rate'),
                'respiratory_frequency' => $this->input->post('respiratory_frequency'),
                'Active' => $this->input->post('active'),
                'general_status' => $this->input->post('general_status'),
                'p_a' => $this->input->post('p_a'),
                'c_a' => $this->input->post('c_a'),
                'abdomen' => $this->input->post('abdomen'),
                'genitals' => $this->input->post('genitals'),
                'members' => $this->input->post('members'),
                'neurological_exams' => $this->input->post('neurological_exams'),
                'remarks' => $this->input->post('remarks'),
                'PID' => $pid,

                'Biotype' => $this->input->post('biotipo'),
                'Skin' => $this->input->post('skin'),
                'Mucous' => $this->input->post('mucous'),
                'BodyHair' => $this->input->post('body_hair'),
                'Nails' => $this->input->post('nails'),
                'Skull' => $this->input->post('skull'),
                'Hair' => $this->input->post('hair'),
                'ParanasalSinuses' => $this->input->post('paranasal_sinuses'),
                'Eyes' => $this->input->post('eyes'),
                'Ears' => $this->input->post('ears'),
                'Nose' => $this->input->post('nose'),
                'Mouth' => $this->input->post('mouth'),
                'Neck' => $this->input->post('neck'),
                'Thorax' => $this->input->post('thorax'),
                'RespiratoryExam' => $this->input->post('respiratory_exam'),
                'CardiovascularExam' => $this->input->post('cardiovascular_exam'),
                'LowerLimbs' => $this->input->post('lower_limbs'),
                'Imc' => $this->input->post('imc'),
                'Pulse' => $this->input->post('pulse'),
                'MotorResponse' => $this->input->post('motor_response'),
                'VerbalResponse' => $this->input->post('verbal_response'),
                'EyeOpening' => $this->input->post('eye_opening'),
                'PulseValue' => $this->input->post('pulse_value'),
                'PulseCharacteristics' => $this->input->post('pulse_characteristics'),
                'Ref_type' => $type,
                'Ref_id' => $ref_id
            );
            $data_emr = array(
                'Weight' => $this->input->post('weight'),
                'Height' => $this->input->post('height'),
                'sys_BP' => $this->input->post('sys_bp'),
                'diast_BP' => $this->input->post('diast_bp'),
                'Temprature' => $this->input->post('temperature')
            );
            $emrid = $this->m_emergency_admission->get_info_by_pid($pid);
            $this->m_emergency_admission->update($emrid, $data_emr);

            $this->m_patient_examination->insert($data);
            $this->session->set_flashdata(
                'msg',
                'REC: Examination created for ' . $pid
            );

            // $emrid = $this->m_emergency_admission->get_info_by_pid($pid);
            // $this->redirect_if_no_continue('/patient/view/' . $pid);
            $this->redirect_if_no_continue('emergency_visit/add_observe/' . $emrid);
            //$this->redirect_if_no_continue('/patient_diagnosis/create_emr_diagnosis/' . $emr_id);
        }
    }

    public function edit($examination_id)
    {
        if (!Modules::run('permission/check_permission', 'patient_examination', 'edit')) {
            die('You do not have permission!');
        }
        $exam = $this->m_patient_examination->get($examination_id);
        if (empty($exam)) {
            die('Id wrong');
        }
        $data = array();
        $data['id'] = $examination_id;
        $data['pid'] = $exam->PID;
        $data['ref_id'] = $exam->Ref_id;
        $data['default_exam_date'] = substr($exam->ExamDate, 0, 10);
        $data['default_weight'] = $exam->Weight;
        $data['default_height'] = $exam->Height;
        $data['default_sys_bp'] = $exam->sys_BP;
        $data['default_diast_bp'] = $exam->diast_BP;
        $data['default_temperature'] = $exam->Temperature;
        $data['default_active'] = $exam->Active;
        $data['default_general_status'] = $exam->general_status;
        $data['default_pa'] = $exam->p_a;
        $data['default_ca'] = $exam->c_a;
        $data['default_abdomen'] = $exam->abdomen;
        $data['default_genitals'] = $exam->genitals;
        $data['default_members'] = $exam->members;
        $data['default_neurological_exams'] = $exam->neurological_exams;
        $data['default_remarks'] = $exam->remarks;
        $data['default_heart_rate'] = $exam->heart_rate;
        $data['default_respiratory_frequency'] = $exam->respiratory_frequency;

        // New columns
        $data['default_biotype'] = $exam->Biotype;
        $data['default_skin'] = $exam->Skin;
        $data['default_mucous'] = $exam->Mucous;
        $data['default_body_hair'] = $exam->BodyHair;
        $data['default_nails'] = $exam->Nails;
        $data['default_skull'] = $exam->Skull;
        $data['default_hair'] = $exam->Hair;
        $data['default_paranasal_sinuses'] = $exam->ParanasalSinuses;
        $data['default_eyes'] = $exam->Eyes;
        $data['default_ears'] = $exam->Ears;
        $data['default_nose'] = $exam->Nose;
        $data['default_mouth'] = $exam->Mouth;
        $data['default_neck'] = $exam->Neck;
        $data['default_thorax'] = $exam->Thorax;
        $data['default_respiratory_exam'] = $exam->RespiratoryExam;
        $data['default_cardiovascular_exam'] = $exam->CardiovascularExam;
        $data['default_lower_limbs'] = $exam->LowerLimbs;
        $data['default_imc'] = $exam->Imc;
        $data['default_pulse'] = $exam->Pulse;
        $data['default_motor_response'] = $exam->MotorResponse;
        $data['default_verbal_response'] = $exam->VerbalResponse;
        $data['default_eye_opening'] = $exam->EyeOpening;

        $this->form_validation->set_rules('weight', 'Weight', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('height', 'Height', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('sys_bp', 'sys BP', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('diast_bp', 'diast BP', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('temperature', 'Temperature', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
        // Recupera o user_id do usuário atual da sessão
        $current_user_id = $this->session->userdata('uid');

        // Verifica se já se passaram mais de 30 segundos desde a criação
        $createDate = strtotime($exam->CreateDate);
        $currentTime = time();
        $editTimeLimit = 86400; // 86.400 segundos o mesmo que 24h de limite para edição
        // Recupera o user_id do criador do registro médico
        $created_by = $this->m_patient_examination->get_created_by($examination_id);

        // Recupera o user_id do usuário atual da sessão
        $current_user_id = $this->session->userdata('uid');

        // Verificação de permissão
        $data['is_edit'] = !(($current_user_id == $created_by) && (($currentTime - $createDate) < $editTimeLimit));
        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $update_data = array(
                'ExamDate' => $this->input->post('examination_date'),
                'Weight' => $this->input->post('weight'),
                'Height' => $this->input->post('height'),
                'sys_BP' => $this->input->post('sys_bp'),
                'diast_BP' => $this->input->post('diast_bp'),
                'Temperature' => $this->input->post('temperature'),
                'Active' => $this->input->post('active'),
                'general_status' => $this->input->post('general_status'),
                'p_a' => $this->input->post('p_a'),
                'c_a' => $this->input->post('c_a'),
                'abdomen' => $this->input->post('abdomen'),
                'genitals' => $this->input->post('genitals'),
                'members' => $this->input->post('members'),
                'neurological_exams' => $this->input->post('neurological_exams'),
                'remarks' => $this->input->post('remarks'),
                'heart_rate' => $this->input->post('heart_rate'),
                'respiratory_frequency' => $this->input->post('respiratory_frequency'),
                // New columns
                'Biotype' => $this->input->post('biotipo'),
                'Skin' => $this->input->post('skin'),
                'Mucous' => $this->input->post('mucous'),
                'BodyHair' => $this->input->post('body_hair'),
                'Nails' => $this->input->post('nails'),
                'Skull' => $this->input->post('skull'),
                'Hair' => $this->input->post('hair'),
                'ParanasalSinuses' => $this->input->post('paranasal_sinuses'),
                'Eyes' => $this->input->post('eyes'),
                'Ears' => $this->input->post('ears'),
                'Nose' => $this->input->post('nose'),
                'Mouth' => $this->input->post('mouth'),
                'Neck' => $this->input->post('neck'),
                'Thorax' => $this->input->post('thorax'),
                'RespiratoryExam' => $this->input->post('respiratory_exam'),
                'CardiovascularExam' => $this->input->post('cardiovascular_exam'),
                'LowerLimbs' => $this->input->post('lower_limbs'),
                'Imc' => $this->input->post('imc'),
                'Pulse' => $this->input->post('pulse'),
                'MotorResponse' => $this->input->post('motor_response'),
                'VerbalResponse' => $this->input->post('verbal_response'),
                'EyeOpening' => $this->input->post('eye_opening')
            );

            $this->m_patient_examination->update($examination_id, $update_data);
            $this->session->set_flashdata(
                'msg',
                'Updated'
            );
            $this->redirect_if_no_continue('/patient/view/' . $pid);
        }
    }

    public function patient_view_exam($patexamid)
    {
        $examination = $this->m_patient_examination->get($patexamid);

        // Load the view with the examination details
        $this->load->view('patient_examination/patient_view_exam', array('examination' => $examination));
    }

    public function get_previous_exams($pid, $continue, $mode = 'HTML')
    {
        $data = array();
        $data["patient_exams_list"] = $this->m_patient_examination->as_array()->order_by('CreateDate', 'DESC')->get_many_by(array('PID' => $pid));
        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_exam');
        } else {
            return $data["patient_exams_list"];
        }
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
}
